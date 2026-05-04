<?php
declare (strict_types=1);

namespace App\Service\Common\Bind;

use App\Const\RepertoryItemSkuCache;
use App\Model\RepertoryItem;
use App\Model\RepertoryItemSku;
use App\Model\RepertoryOrder;
use Kernel\Container\Di;
use Kernel\Plugin\Usr;

class Ship implements \App\Service\Common\Ship
{

    private function getRepertoryItemSkuService(): \App\Service\Common\RepertoryItemSku
    {
        return Di::inst()->make(\App\Service\Common\RepertoryItemSku::class);
    }

    public function getShip(int $repertoryItemSkuId, ?RepertoryOrder $order = null): ?\Kernel\Plugin\Handle\Ship
    {

        $repertoryItemSku = RepertoryItemSku::with(["repertoryItem"])->find($repertoryItemSkuId);
        if (!$repertoryItemSku) {
            return null;
        }

        $repertoryItem = $repertoryItemSku->repertoryItem;
        if (!$repertoryItem) {
            return null;
        }

        $env = Usr::instance()->userToEnv($repertoryItem->user_id);

        return \Kernel\Plugin\Ship::instance()->getShipHandle($repertoryItem->plugin, $env, $repertoryItem, $repertoryItemSku, $order);
    }

    public function stock(int $repertoryItemSkuId, int $action = RepertoryItemSkuCache::ACTION_READ_CACHE): string
    {
        $repertoryItemSku = $this->getRepertoryItemSkuService();
        $cache = $repertoryItemSku->getCache($repertoryItemSkuId, RepertoryItemSkuCache::TYPE_STOCK);
        if ($cache !== null && $action === RepertoryItemSkuCache::ACTION_READ_CACHE) {
            return $cache;
        }

        $ship = $this->getShip($repertoryItemSkuId);
        if (!$ship) {
            $stock = "0";
        } else {
            $stock = (string)$ship->stock();
        }

        $repertoryItemSku->setCache($repertoryItemSkuId, RepertoryItemSkuCache::TYPE_STOCK, $stock);
        return $stock;
    }

    public function inspection(int $repertoryItemSkuId, array $map = []): bool
    {
        $ship = $this->getShip($repertoryItemSkuId);
        if (!$ship) {
            $state = false;
        } else {
            $state = $ship->inspection($map);
        }
        return $state;
    }

    public function hasEnoughStock(int $repertoryItemSkuId, int $quantity = 1, int $action = RepertoryItemSkuCache::ACTION_READ_CACHE): bool
    {
        $repertoryItemSku = $this->getRepertoryItemSkuService();
        $cache = $repertoryItemSku->getCache($repertoryItemSkuId, RepertoryItemSkuCache::TYPE_HAS_ENOUGH_STOCK);
        if ($cache !== null && $action === RepertoryItemSkuCache::ACTION_READ_CACHE) {
            return (bool)$cache;
        }
        $ship = $this->getShip($repertoryItemSkuId);
        if (!$ship) {
            $state = false;
        } else {
            $state = $ship->hasEnoughStock($quantity);
        }
        $repertoryItemSku->setCache($repertoryItemSkuId, RepertoryItemSkuCache::TYPE_HAS_ENOUGH_STOCK, $state ? "1" : "0");
        return $state;
    }
}