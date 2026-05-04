<?php
declare (strict_types=1);

namespace App\Entity\Repertory;

use App\Model\User;
use Kernel\Util\Str;

class Order
{
    
    public User $customer;

    public string $tradeNo;

    public int $repertoryItemId;

    public int $repertoryItemSkuId;

    public int $quantity;

    public string $tradeIp;

    public function __construct(User $customer, int $repertoryItemId, int $repertoryItemSkuId, int $quantity, string $tradeIp, ?string $tradeNo = null)
    {
        $this->customer = $customer;
        $this->repertoryItemId = $repertoryItemId;
        $this->repertoryItemSkuId = $repertoryItemSkuId;
        $this->quantity = $quantity;
        $this->tradeIp = $tradeIp;
        if (!$tradeNo) {
            $tradeNo = Str::generateTradeNo();
        }
        $this->tradeNo = $tradeNo;
    }
}