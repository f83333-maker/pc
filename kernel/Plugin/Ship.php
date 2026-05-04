<?php
declare (strict_types=1);

namespace Kernel\Plugin;

use App\Model\RepertoryItem;
use App\Model\RepertoryItemSku;
use App\Model\RepertoryOrder;
use Kernel\Component\Singleton;
use Kernel\Plugin\Handle\ForeignShip as b;
use Kernel\Plugin\Handle\Ship as a;

class Ship
{
    use Singleton;

    public function getShipHandle(string $name, string $env, RepertoryItem $item, RepertoryItemSku $sku, ?RepertoryOrder $order = null): ?a
    {
        return Plugin::inst()->getHandle($name, $env, a::class, $item, $sku, $order);
    }

    public function getForeignShipHandle(string $name, string $env, array $config): ?b
    {
        return Plugin::inst()->getHandle($name, $env, b::class, $config);
    }
}