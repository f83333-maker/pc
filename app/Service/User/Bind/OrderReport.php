<?php
declare (strict_types=1);

namespace App\Service\User\Bind;

use App\Entity\Report\Handle;
use App\Entity\Report\Order;
use App\Entity\Report\Reply;
use App\Model\OrderItem;
use App\Model\OrderReportMessage;
use App\Model\RepertoryOrder;
use Hyperf\Database\Model\Relations\HasOne;
use Kernel\Annotation\Inject;
use Kernel\Database\Db;
use Kernel\Exception\ServiceException;
use Kernel\Util\Date;
use App\Service\User\Bill;
use App\Service\User\Balance;
use Kernel\Util\Decimal;

class OrderReport implements \App\Service\User\OrderReport
{

    #[Inject]
    private Bill $bill;

    #[Inject]
    private Balance $balance;

    public function apply(Order $order): void
    {
        Db::transaction(function () use ($order) {
            $orderItem = OrderItem::query()->with([
                'order' => function (HasOne $hasOne) {
                    $hasOne->with('customer');
                },
                'sku' => function (HasOne $hasOne) {
                    $hasOne->with(['repertoryItemSku' => function (HasOne $hasOne) {
                        $hasOne->with(['repertoryItem']);
                    }]);
                }
            ])->lock(true)->find($order->orderItemId);

            if (
                !$orderItem ||
                !$orderItem->order ||
                !$orderItem->sku ||
                !$orderItem->order->customer ||
                !$orderItem->sku->repertoryItemSku ||
                $orderItem->order->customer->id != $order->customerId ||
                $orderItem->order->status != 1 ||
                !in_array($orderItem->status, [0, 1, 2])
            ) {
                throw new ServiceException("订单不存在");
            }

            var_dump($order->expect , $orderItem?->sku?->repertoryItemSku?->repertoryItem?->refund_mode);

            if ($order->expect != 0 && $orderItem?->sku?->repertoryItemSku?->repertoryItem?->refund_mode == 0) {
                throw new ServiceException("此商品不支持该维权方式");
            }

            $orderItem->status = 4;
            $orderItem->save();

            $orderReport = new \App\Model\OrderReport();
            $orderReport->order_item_id = $orderItem->id;
            $orderItem->sku->repertoryItemSku->user_id > 0 && ($orderReport->supply_id = $orderItem->sku->repertoryItemSku->user_id);
            $orderItem->user_id > 0 && ($orderReport->merchant_id = $orderItem->user_id);
            $orderReport->customer_id = $order->customerId;
            $orderReport->status = 0;
            $orderReport->type = $order->type;
            $orderReport->expect = $order->expect;
            $orderReport->create_time = Date::current();
            $orderReport->save();

            $orderReportMessage = new OrderReportMessage();
            $orderReportMessage->order_report_id = $orderReport->id;
            $orderReportMessage->message = $order->message;
            $orderReportMessage->role = 2;
            $orderReportMessage->create_time = $orderReport->create_time;
            $order->imageUrl && ($orderReportMessage->image_url = $order->imageUrl);
            $orderReportMessage->save();
        });
    }

    public function handle(Handle $handle): void
    {
        Db::transaction(function () use ($handle) {
            $orderReport = \App\Model\OrderReport::with([
                'orderItem' => function (HasOne $one) {
                    $one->with(['order']);
                }
            ])->find($handle->reportId);

            if (!$orderReport) {
                throw new ServiceException("订单不存在#1");
            }

            $orderItem = $orderReport->orderItem;

            if (!$orderItem) {
                throw new ServiceException("订单不存在#2");
            }

            $repertoryOrder = RepertoryOrder::query()->where("item_trade_no", $orderItem->trade_no)->first();

            if (!$repertoryOrder) {
                throw new ServiceException("上游订单不存在，该投诉请联系客户手动处理");
            }

            $order = $orderItem->order;

            if (!$order) {
                throw new ServiceException("订单不存在#4");
            }

            if ($orderReport->status == 3 || $orderItem->status != 4 || $repertoryOrder->status == 3 || $order->status != 1) {
                throw new ServiceException("订单无法被处理");
            }

            $refundMode = $orderItem->refund_mode;
            $orderReport->status = 2;

            $refundAmount = 0;
            $refundMerchantAmount = 0;

            switch ($handle->type) {
                case 1:

                    if (!$handle->treasure) {
                        throw new ServiceException("发货信息不能为空");
                    }
                    $orderReport->handle_type = 1;
                    $orderItem->treasure = $handle->treasure; 
                    $repertoryOrder->contents = $handle->treasure;  
                    break;
                case 2:

                    (!$refundMode || $refundMode == 0) && (throw new ServiceException("该订单不支持退款"));
                    $handle->refundAmount <= 0 && (throw new ServiceException("退款金额必须大于0"));
                    $handle->refundMerchantAmount <= 0 && (throw new ServiceException("退款给商家的金额必须大于0"));
                    $orderReport->handle_type = 2;
                    $orderReport->status = 3;
                    $orderItem->status = 5;
                    $repertoryOrder->status = 3;

                    if ($repertoryOrder->user_id > 0) {
                        $this->balance->deduct(
                            userId: $repertoryOrder->user_id,
                            amount: (new Decimal($handle->refundAmount))->add($handle->refundMerchantAmount)->getAmount(),
                            type: \App\Const\Balance::TYPE_PAY_ORDER_REFUND,
                            tradeNo: $orderItem->trade_no
                        );
                    }
                    $refundAmount = $handle->refundAmount;
                    $refundMerchantAmount = $handle->refundMerchantAmount;
                    break;
                case 3:

                    ($refundMode != 2) && (throw new ServiceException("该订单不支持全额退款"));
                    $orderReport->handle_type = 3;
                    $orderReport->status = 3;
                    $orderItem->status = 5;
                    $repertoryOrder->status = 3;

                    $this->bill->rollback($orderItem->trade_no);
                    $refundAmount = (string)$orderItem->amount;
                    $refundMerchantAmount = (string)(RepertoryOrder::query()->where("item_trade_no", $orderItem->trade_no)->first())?->amount ?: "0";
                    break;
            }

            if ($handle->type == 2 || $handle->type == 3) {
                $orderReport->refund_amount = $refundAmount;
                $orderReport->refund_merchant_amount = $refundMerchantAmount;

                if ($orderItem->user_id > 0 && $refundMerchantAmount > 0) {
                    $this->balance->add(
                        userId: $orderItem->user_id,
                        amount: $refundMerchantAmount,
                        type: \App\Const\Balance::TYPE_ORDER_REFUND,
                        isWithdraw: false,
                        status: \App\Const\Balance::STATUS_DIRECT,
                        tradeNo: $orderItem->trade_no
                    );
                }

                $this->balance->add(
                    userId: $order->customer_id,
                    amount: $refundAmount,
                    type: \App\Const\Balance::TYPE_ORDER_REFUND,
                    isWithdraw: false,
                    status: \App\Const\Balance::STATUS_DIRECT,
                    tradeNo: $orderItem->trade_no
                );
            }

            $orderReportMessage = new OrderReportMessage();
            $orderReportMessage->order_report_id = $orderReport->id;
            $orderReportMessage->message = $handle->message;
            $orderReportMessage->role = $handle->role;
            $orderReportMessage->create_time = Date::current();
            $handle->imageUrl && ($orderReportMessage->image_url = $handle->imageUrl);

            $orderReportMessage->save();
            $orderReport->save();
            $orderItem->save();
            $repertoryOrder->save();
        }, \Kernel\Database\Const\Db::ISOLATION_SERIALIZABLE);
    }

    public function reply(Reply $reply): void
    {
        Db::transaction(function () use ($reply) {
            $orderReport = \App\Model\OrderReport::find($reply->reportId);
            if (!$orderReport || $orderReport->customer_id != $reply->userId) {
                throw new ServiceException("订单不存在#1");
            }

            if ($orderReport->status != 2) {
                throw new ServiceException("商家还未处理");
            }

            $orderReport->status = 1;
            $orderReport->save();

            $orderReportMessage = new OrderReportMessage();
            $orderReportMessage->order_report_id = $orderReport->id;
            $orderReportMessage->message = $reply->message;
            $orderReportMessage->role = 2;
            $orderReportMessage->create_time = Date::current();
            $reply->imageUrl && ($orderReportMessage->image_url = $reply->imageUrl);
            $orderReportMessage->save();

        }, \Kernel\Database\Const\Db::ISOLATION_SERIALIZABLE);
    }
}