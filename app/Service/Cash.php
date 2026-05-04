<?php
declare(strict_types=1);

namespace App\Service;

use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Bind\Cash::class)]
interface Cash
{
    
    public function settlement(float $amount): void;
}