<?php
declare(strict_types=1);

namespace App\Entity\Shop;

use Kernel\Component\ToArray;

class QuantityRestriction
{
    use ToArray;

    public int $min = 1;

    public int $max = 0;

    
    public int $total = 0;

    
    public function __construct(int $min = 1, int $max = 0, int $total = 0)
    {
        $this->min = $min > 0 ? $min : 1;
        $this->max = $max;
        $this->total = $total;
    }

    
    public function setMin(int $min): void
    {
        $this->min = $min > 0 ? $min : 1;
    }

    public function setMax(int $max): void
    {
        $this->max = $max;
    }

    public function setTotal(int $total): void
    {
        $this->total = $total;
    }
}