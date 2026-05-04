<?php
declare (strict_types=1);

namespace App\Entity\Repertory;

class Trade
{
    public ?int $customerId = null;
    public int $skuId;
    public int $quantity;
    public string $tradeNo;
    public string $mainTradeNo;
    public array $widget = [];
    public string $amount = "0.00";

    public function __construct(?int $customerId, int $skuId, int $quantity)
    {
        $this->customerId = $customerId;
        $this->skuId = $skuId;
        $this->quantity = $quantity;
    }

    public function setTradeNo(string $tradeNo): void
    {
        $this->tradeNo = $tradeNo;
    }

    public function setMainTradeNo(string $mainTradeNo): void
    {
        $this->mainTradeNo = $mainTradeNo;
    }

    public function setWidget(array $widget): void
    {
        $this->widget = $widget;
    }

    public function setAmount(string|float|int $amount): void
    {
        $this->amount = (string)$amount;
    }
}