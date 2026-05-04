<?php
declare (strict_types=1);

namespace App\Service\Store;

use App\Entity\Store\Authentication;
use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Store\Bind\Pay::class)]
interface Pay
{
    
    public function getList(Authentication $authentication, int $equipment = 1): array;

    
    public function getPayOrder(Authentication $authentication, string $tradeNo): array;
}