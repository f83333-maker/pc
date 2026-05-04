<?php
declare (strict_types=1);

namespace App\Entity\Shop;

use Kernel\Component\ToArray;

class Trade
{
    use ToArray;

    public ?string $tradeNo;

    
    public ?string $totalAmount;

    
    public ?string $createTime;

    
    public bool $isFree = false;

    
    public function __construct(?string $tradeNo = null, ?string $totalAmount = null, ?string $createTime = null)
    {
        $this->tradeNo = $tradeNo;
        $this->totalAmount = $totalAmount;
        $this->createTime = $createTime;
    }

    
    public function setIsFree(bool $isFree): static
    {
        $this->isFree = $isFree;
        return $this;
    }
}