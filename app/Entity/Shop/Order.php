<?php
declare (strict_types=1);

namespace App\Entity\Shop;

use Kernel\Component\ToArray;
use App\Entity\Pay\Order as PayOrder;

class Order
{

    use ToArray;

    public int $id;
    public string $tradeNo;
    public string $totalAmount;
    public int $status;
    public int $type;
    public string $createTime;
    public ?string $payTime = null;

    public array $items = [];

    public ?PayOrder $payOrder = null;

    public ?Product $product = null;

    public function __construct(\App\Model\Order $order)
    {
        $this->id = $order->id;
        $this->tradeNo = $order->trade_no;
        $this->totalAmount = (string)$order->total_amount;
        $this->status = $order->status;
        $this->type = $order->type;
        $this->createTime = $order->create_time;
        $this->payTime = $order->pay_time;
    }

    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    public function setPayOrder(PayOrder $payOrder): void
    {
        $this->payOrder = $payOrder;
    }

    
    public function setItems(array $items): void
    {
        $this->items = $items;
    }
}