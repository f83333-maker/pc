<?php
declare (strict_types=1);

namespace App\Controller\User\API\Index;

use App\Controller\User\Base;
use App\Interceptor\PostDecrypt;
use App\Interceptor\Visitor;
use App\Interceptor\Waf;
use App\Model\OrderItem;
use App\Validator\Common;
use Kernel\Annotation\Inject;
use Kernel\Annotation\Interceptor;
use Kernel\Annotation\Validator;
use Kernel\Context\Interface\Response;
use Kernel\Exception\JSONException;
use Kernel\Exception\NotFoundException;
use Kernel\Exception\RuntimeException;
use Kernel\Language\Language;
use Kernel\Plugin\Const\Plugin as PGC;
use Kernel\Plugin\Const\Point;
use Kernel\Plugin\Plugin;
use Kernel\Plugin\Usr;
use Kernel\Util\Date;
use Kernel\Validator\Method;
use Kernel\Waf\Filter;

#[Interceptor(class: [PostDecrypt::class, Waf::class, Visitor::class], type: Interceptor::API)]
class Order extends Base
{

    #[Inject]
    protected \App\Service\User\Order $order;

    /**
     * 校验当前请求方对订单的归属权（修复 B3/B4 水平越权）。
     *
     * 订单归属规则：
     *   - 已登录用户：order.customer_id 必须 == 当前 getUser()->id
     *   - 未登录访客：order.client_id 必须 == 当前 cookie('client_id')
     *
     * 任一规则不满足都视为非本人订单，统一抛 "订单不存在"，避免泄露订单是否存在的信息。
     *
     * @param \App\Model\Order|null $order 上游用 trade_no 查到的订单实体
     * @throws JSONException
     */
    private function assertOrderOwnership(?\App\Model\Order $order): void
    {
        if (!$order) {
            throw new JSONException("订单不存在");
        }

        $user = $this->getUser();
        $clientId = (string)$this->request->cookie("client_id");

        if ($user) {
            // 已登录：customer_id 必须匹配
            if ((int)$order->customer_id === (int)$user->id) {
                return;
            }
            // 兼容历史数据：老订单可能仅有 client_id 没有 customer_id
            if ((int)$order->customer_id === 0 && $clientId !== "" && (string)$order->client_id === $clientId) {
                return;
            }
        } else {
            // 访客：client_id 必须存在且匹配；空 client_id 直接拒绝避免命中 0/null
            if ($clientId !== "" && (string)$order->client_id === $clientId) {
                return;
            }
        }

        throw new JSONException("订单不存在");
    }

    #[Validator([[Common::class, "clientId"]], Method::COOKIE)]
    public function trade(): Response
    {
        if (($hook = Plugin::instance()->hook(Usr::inst()->getEnv(), Point::CONTROLLER_ORDER_TRADE_BEFORE, PGC::HOOK_TYPE_HTTP, $this->request, $this->response)) instanceof Response) return $hook;
        $items = (array)$this->request->post("items");
        $trade = $this->order->trade(
            items: $items,
            clientId: (string)$this->request->cookie("client_id"),
            createIp: $this->request->clientIp(),
            createUa: $this->request->header("UserAgent"),
            customer: $this->getUser(),
            user: $this->getSiteOwner(),
            invite: $this->getInviter()
        );
        if (($hook = Plugin::instance()->hook(Usr::inst()->getEnv(), Point::CONTROLLER_ORDER_TRADE_AFTER, PGC::HOOK_TYPE_HTTP, $trade, $this->request, $this->response)) instanceof Response) return $hook;
        return $this->json(200, "success", $trade->toArray());
    }

    #[Validator([[\App\Validator\User\Order::class, "tradeNo"]])]
    public function cancel(): Response
    {
        $tradeNo = $this->request->post("trade_no");

        // 取消订单前先校验归属，避免攻击者凭 trade_no 取消他人未支付订单（DoS 干扰）
        $orderModel = \App\Model\Order::query()->where("trade_no", $tradeNo)->first();
        $this->assertOrderOwnership($orderModel);

        $this->order->cancel($tradeNo);
        return $this->json();
    }

    #[Validator([
        [\App\Validator\User\Order::class, ["itemId", "tradeNo"]]
    ], Method::POST)]
    public function getOrder(): Response
    {
        $tradeNo = $this->request->post("trade_no");
        $itemId = $this->request->post("item_id", Filter::INTEGER);

        // 修复 B3 水平越权：先用 trade_no 拉主单做归属校验，再用 item_id 查明细
        $orderModel = \App\Model\Order::query()->where("trade_no", $tradeNo)->first();
        $this->assertOrderOwnership($orderModel);

        $order = OrderItem::query()
            ->where("order_id", $orderModel->id)
            ->find($itemId, "order_item.*");

        if (!$order) {
            throw new JSONException("订单不存在");
        }

        $orderItem = $this->order->getOrderItem($order);

        if (!$orderItem) {
            throw new JSONException("订单不存在");
        }

        return $this->json(200, "success", $orderItem->toArray());
    }

    #[Validator([
        [\App\Validator\User\Order::class, ["itemId", "tradeNo"]]
    ], Method::GET)]
    public function downloadOrder(int $itemId, string $tradeNo): Response
    {

        // 修复 B4 水平越权：先用 trade_no 校验归属，再读 order_item 内容（卡密 / treasure）
        $orderModel = \App\Model\Order::query()->where("trade_no", $tradeNo)->first();
        $this->assertOrderOwnership($orderModel);

        $order = OrderItem::query()
            ->where("order_id", $orderModel->id)
            ->find($itemId, "order_item.*");

        if (!$order || !in_array($order->status, [1, 3, 4])) {
            throw new JSONException("订单不存在");
        }

        $orderItem = $this->order->getOrderItem($order);

        if (!$orderItem || $orderItem->render) {
            throw new JSONException("订单不存在");
        }

        return $this->response
            ->withHeader("Content-Type", "application/octet-stream")
            ->withHeader("Content-Transfer-Encoding", "binary")
            ->withHeader("Content-Disposition", sprintf('filename=%s(%s)-%s.txt', Language::inst()->output(strip_tags($orderItem->item->name)), Language::inst()->output(strip_tags($orderItem->sku->name)), Date::current()))
            ->raw((string)$orderItem->treasure);
    }
}
