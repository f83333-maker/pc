<?php
declare (strict_types=1);

namespace App\Entity\Repertory;

use App\Model\RepertoryOrder;
use Kernel\Component\ToArray;

class Deliver
{
    use ToArray;

    public string $contents;

    public string $tradeNo;

    public string $amount;

    public int $status;

    public string $itemTradeNo;

    public string $tradeTime;

    public function __construct(RepertoryOrder $repertoryOrder)
    {
        $this->tradeNo = $repertoryOrder->trade_no;
        $this->itemTradeNo = $repertoryOrder->item_trade_no;
        $this->contents = $repertoryOrder->contents;
        $this->amount = (string)$repertoryOrder->amount;
        $this->status = $repertoryOrder->status;
        $this->tradeTime = $repertoryOrder->trade_time;
    }
}