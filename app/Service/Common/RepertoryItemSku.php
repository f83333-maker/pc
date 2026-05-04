<?php
declare(strict_types=1);

namespace App\Service\Common;

use App\Const\MarketControl;
use App\Entity\Repertory\Sku;
use App\Model\User;
use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Common\Bind\RepertoryItemSku::class)]
interface RepertoryItemSku
{

    public function getSKUEntity(int|\App\Model\RepertoryItemSku $skuModel, ?int $userId): ?Sku;

    public function isDisplay(int|\App\Model\RepertoryItemSku $skuModel, int|User $userModel): bool;

    public function marketControlCheck(string $price, int $repertoryItemSkuId, int $userId, int $type = MarketControl::TYPE_VISITOR): void;

    public function getWholesale(?User $user, int $skuId): array;

    public function setCache(int $repertoryItemSkuId, int $type, string $value): void;

    public function getCache(int $repertoryItemSkuId, int $type): ?string;

    public function existCache(int $repertoryItemSkuId): bool;

    public function delCache(int $repertoryItemSkuId, bool $force = false): void;

    public function syncCache(int $repertoryItemSkuId): void;

    public function syncCacheForItem(int $repertoryItemId): void;

    public function checkSyncCacheForItem(int $repertoryItemId): void;

    public function delCacheForItem(int $repertoryItemId): void;
}