<?php
declare (strict_types=1);

namespace App\Service\User\Bind;

use App\Model\ItemMarkupTemplate;
use App\Model\ItemSku;
use App\Model\ItemSkuWholesale;
use App\Model\OrderItem;
use App\Model\User;
use App\Model\UserLevel;
use Kernel\Exception\JSONException;
use App\Model\Item;

class Ownership implements \App\Service\User\Ownership
{

    public function throw(bool ...$state): void
    {
        foreach ($state as $sta) {
            if (!$sta) {
                throw new JSONException("权限不足");
            }
        }
    }

    public function itemSku(int $userId, int $skuId): bool
    {
        return ItemSku::query()->where("user_id", $userId)->where("id", $skuId)->exists();
    }

    
    public function level(int $userId, int $levelId): bool
    {
        return UserLevel::query()->where("user_id", $userId)->where("id", $levelId)->exists();
    }

    public function ownMember(int $userId, int $memberId): bool
    {
        return User::query()->where("pid", $userId)->where("id", $memberId)->exists();
    }

    public function item(int $userId, int $itemId): bool
    {
        return Item::query()->where("user_id", $userId)->where("id", $itemId)->exists();
    }

    public function wholesale(int $userId, int $wholesaleId): bool
    {
        return ItemSkuWholesale::query()->where("user_id", $userId)->where("id", $wholesaleId)->exists();
    }

    public function markup(int $userId, int $markupId): bool
    {
        return ItemMarkupTemplate::query()->where("user_id", $userId)->where("id", $markupId)->exists();
    }

    public function orderItem(int $customerId, int $orderItemId): bool
    {
        return OrderItem::query()->leftJoin("order", "order_item.order_id", "=", "order.id")->where("order.customer_id", $customerId)->where("order_item.id", $orderItemId)->exists();
    }
}