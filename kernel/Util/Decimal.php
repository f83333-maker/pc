<?php
declare (strict_types=1);

namespace Kernel\Util;

class Decimal
{
    
    private string $amount;

    private int $scale;

    public function __construct(string|float|int $amount, int $scale = 2)
    {
        $this->amount = (string)$amount;
        $this->scale = $scale;
    }

    public function add(string|float|int $other): Decimal
    {
        $result = bcadd($this->amount, (string)$other, $this->scale);
        return new Decimal($result, $this->scale);
    }

    public function sub(string|float|int $other): Decimal
    {
        $result = bcsub($this->amount, (string)$other, $this->scale);
        return new Decimal($result, $this->scale);
    }

    public function mul(string|float|int $factor): Decimal
    {
        $result = bcmul($this->amount, (string)$factor, $this->scale);
        return new Decimal($result, $this->scale);
    }

    public function div(string|float|int $divisor): Decimal
    {
        $result = bcdiv($this->amount, (string)$divisor, $this->scale);
        return new Decimal($result, $this->scale);
    }

    public function getAmount(?int $scale = 2): string
    {
        return bcadd($this->amount, '0', $scale);
    }
}