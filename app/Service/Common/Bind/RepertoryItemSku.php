<?php
declare(strict_types=1);

namespace App\Service\Common\Bind;

use App\Const\MarketControl;
use App\Entity\Repertory\Sku;
use App\Entity\Shop\Wholesale;
use App\Model\RepertoryItemSkuCache;
use App\Model\RepertoryItemSkuGroup;
use App\Model\RepertoryItemSkuUser;
use App\Model\RepertoryItemSkuWholesale;
use App\Model\RepertoryItemSkuWholesaleGroup;
use App\Model\RepertoryItemSkuWholesaleUser;
use App\Model\User;
use App\Model\UserGroup;
use Kernel\Annotation\Inject;
use Kernel\Exception\ServiceException;
use Kernel\Log\Log;
use Kernel\Util\Date;

class RepertoryItemSku implements \App\Service\Common\RepertoryItemSku
{

    #[Inject]
    private \App\Service\Common\RepertoryOrder $order;

    #[Inject]
    private \App\Service\Common\Ship $ship;

    public function getSKUEntity(int|\App\Model\RepertoryItemSku $skuModel, ?int $userId): ?Sku
    {

        $user = $userId > 0 ? User::query()->find($userId) : null;

        if (is_int($skuModel)) {

            $skuModel = \App\Model\RepertoryItemSku::query()->find($skuModel);
            if (!$skuModel) {
                return null;
            }
        }

        $sku = $skuModel;
        $syncList = [
            "market_control_status",
            "market_control_min_price",
            "market_control_max_price",
            "market_control_level_min_price",
            "market_control_level_max_price",
            "market_control_user_min_price",
            "market_control_user_max_price",
            "market_control_min_num",
            "market_control_max_num",
            "market_control_only_num"
        ];

        if ($user) {
            $groupModel = RepertoryItemSkuGroup::query()->where("group_id", $user->group_id)->where("sku_id", $skuModel->id)->first();
            if ($groupModel && $groupModel->status == 1) {
                $sku = $groupModel;
                if ($groupModel->market_control_status == 0) {

                    foreach ($syncList as $key) {
                        $sku->$key = $skuModel->$key;
                    }
                } elseif ($groupModel->market_control_status == 1) {

                    $sku->market_control_status = 1;
                } elseif ($groupModel->market_control_status == 2) {

                    $sku->market_control_status = 0;
                }
            }
            $userModel = RepertoryItemSkuUser::query()->where("customer_id", $user->id)->where("sku_id", $skuModel->id)->first();
            if ($userModel && $userModel->status == 1) {
                $sku = $userModel;

                if ($userModel->market_control_status == 0) {

                    foreach ($syncList as $key) {
                        $sku->$key = $skuModel->$key;
                    }
                } elseif ($userModel->market_control_status == 1) {

                    $sku->market_control_status = 1;
                } elseif ($userModel->market_control_status == 2) {

                    $sku->market_control_status = 0;
                }
            }
        }

        return new Sku($skuModel->id, $skuModel->name, $this->order->getAmount($user, $skuModel), $sku);
    }

    public function isDisplay(int|\App\Model\RepertoryItemSku $skuModel, int|User $userModel): bool
    {
        if (is_int($userModel)) {

            $userModel = User::query()->find($userModel);
            if (!$userModel) {
                return false;
            }
        }

        if (is_int($skuModel)) {

            $skuModel = \App\Model\RepertoryItemSku::query()->find($skuModel);
            if (!$skuModel) {
                return false;
            }
        }

        $result = $skuModel->private_display != 1; 

        $groupModel = RepertoryItemSkuGroup::query()->where("group_id", $userModel->group_id)->where("sku_id", $skuModel->id)->first();
        if ($groupModel?->status == 1) {
            $result = true;
        }

        $userSkuModel = RepertoryItemSkuUser::query()->where("customer_id", $userModel->id)->where("sku_id", $skuModel->id)->first();

        if ($userSkuModel?->status == 1) {
            $result = true;
        }

        return $result;
    }

    public function marketControlCheck(string $price, int $repertoryItemSkuId, int $userId, int $type = MarketControl::TYPE_VISITOR): void
    {
        $SKUEntity = $this->getSKUEntity($repertoryItemSkuId, $userId);
        if ($SKUEntity->marketControl) {
            switch ($type) {
                case MarketControl::TYPE_VISITOR:
                    if ($SKUEntity->marketControlMinPrice > 0 && $SKUEntity->marketControlMinPrice > $price) {
                        throw new ServiceException("零售价不得低于控价范围");
                    }
                    if ($SKUEntity->marketControlMaxPrice > 0 && $SKUEntity->marketControlMaxPrice < $price) {
                        throw new ServiceException("零售价不得高于控价范围");
                    }
                    break;
                case MarketControl::TYPE_LEVEL:
                    if ($SKUEntity->marketControlLevelMinPrice > 0 && $SKUEntity->marketControlLevelMinPrice > $price) {
                        throw new ServiceException("会员等级定价不得低于控价范围");
                    }
                    if ($SKUEntity->marketControlLevelMaxPrice > 0 && $SKUEntity->marketControlLevelMaxPrice < $price) {
                        throw new ServiceException("会员等级定价不得高于控价范围");
                    }
                    break;
                case MarketControl::TYPE_USER:
                    if ($SKUEntity->marketControlUserMinPrice > 0 && $SKUEntity->marketControlUserMinPrice > $price) {
                        throw new ServiceException("会员密价不得低于控价范围");
                    }
                    if ($SKUEntity->marketControlUserMaxPrice > 0 && $SKUEntity->marketControlUserMaxPrice < $price) {
                        throw new ServiceException("会员密价不得高于控价范围");
                    }
                    break;
            }
        }
    }

    public function setCache(int $repertoryItemSkuId, int $type, string $value): void
    {
        try {
            RepertoryItemSkuCache::query()->updateOrInsert(
                ['sku_id' => $repertoryItemSkuId, 'type' => $type], ['value' => $value, "create_time" => Date::current()]
            );
        } catch (\Throwable $e) {
        }
    }

    public function getWholesale(?User $user, int $skuId): array
    {
        $list = RepertoryItemSkuWholesale::query()->where("sku_id", $skuId)->orderBy("quantity", "asc")->get();
        $data = [];

        foreach ($list as $li) {
            $wholesale = new Wholesale($li->id, $li->quantity, $li->stock_price);
            if ($user) {

                $group = $user?->group;

                if ($group) {

                    $levelRule = RepertoryItemSkuWholesaleGroup::where("group_id", $group->id)->where("wholesale_id", $wholesale->id)->first();
                    ($levelRule && $levelRule->status == 1 && $levelRule->stock_price < $wholesale->price) && $wholesale->setPrice($levelRule->stock_price);
                }

                $userRule = RepertoryItemSkuWholesaleUser::where("customer_id", $user->id)->where("wholesale_id", $wholesale->id)->first();
                ($userRule && $userRule->status == 1 && $userRule->stock_price < $wholesale->price) && $wholesale->setPrice($userRule->stock_price);
            }
            $data[] = $wholesale;
        }

        return $data;
    }

    public function getCache(int $repertoryItemSkuId, int $type): ?string
    {

        $cache = RepertoryItemSkuCache::query()->where("sku_id", $repertoryItemSkuId)->where("type", $type)->first();
        return $cache?->value;
    }

    public function existCache(int $repertoryItemSkuId): bool
    {
        $a = RepertoryItemSkuCache::query()->where("sku_id", $repertoryItemSkuId)->where("type", \App\Const\RepertoryItemSkuCache::TYPE_STOCK)->exists();
        $b = RepertoryItemSkuCache::query()->where("sku_id", $repertoryItemSkuId)->where("type", \App\Const\RepertoryItemSkuCache::TYPE_HAS_ENOUGH_STOCK)->exists();
        return $a && $b;
    }

    public function delCache(int $repertoryItemSkuId, bool $force = false): void
    {
        $a = RepertoryItemSkuCache::query()->where("sku_id", $repertoryItemSkuId);
        if (!$force) {
            $a = $a->where("create_time", "<", \date("Y-m-d H:i:s", time() - 30));
        }
        $a->delete();
    }

    public function syncCache(int $repertoryItemSkuId): void
    {
        try {

            $this->ship->stock($repertoryItemSkuId, \App\Const\RepertoryItemSkuCache::ACTION_READ_SOURCE);
            $this->ship->hasEnoughStock($repertoryItemSkuId, 1, \App\Const\RepertoryItemSkuCache::ACTION_READ_SOURCE);
        } catch (\Throwable $e) {
            Log::inst()->error("缓存刷新错误：" . $e->getMessage());
        }
    }

    public function syncCacheForItem(int $repertoryItemId): void
    {
        $skus = \App\Model\RepertoryItemSku::query()->where("repertory_item_id", $repertoryItemId)->get();
        foreach ($skus as $sku) {
            $this->syncCache($sku->id);
        }
    }

    public function checkSyncCacheForItem(int $repertoryItemId): void
    {
        $skus = \App\Model\RepertoryItemSku::query()->where("repertory_item_id", $repertoryItemId)->get();
        foreach ($skus as $sku) {
            if (!$this->existCache($sku->id)) {
                $this->syncCache($sku->id);
            }
        }
    }

    public function delCacheForItem(int $repertoryItemId): void
    {

    }
}