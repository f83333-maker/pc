<?php
declare (strict_types=1);

namespace App\Service\User;

use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\User\Bind\Bill::class)]
interface Bill
{

    public function unfreeze(string $tradeNo): void;

    
    public function rollback(string $tradeNo): void;
}