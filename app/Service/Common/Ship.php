<?php
declare (strict_types=1);

namespace App\Service\Common;

use App\Const\RepertoryItemSkuCache;
use App\Model\RepertoryOrder;
use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Common\Bind\Ship::class)]
interface Ship
{

    public function getShip(int $repertoryItemSkuId, ?RepertoryOrder $order = null): ?\Kernel\Plugin\Handle\Ship;

    public function stock(int $repertoryItemSkuId, int $action = RepertoryItemSkuCache::ACTION_READ_CACHE): string;

    
    public function inspection(int $repertoryItemSkuId, array $map): bool;

    public function hasEnoughStock(int $repertoryItemSkuId, int $quantity = 1, int $action = RepertoryItemSkuCache::ACTION_READ_CACHE): bool;
}