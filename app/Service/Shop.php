<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Commodity;
use App\Model\User;
use App\Model\UserGroup;
use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Bind\Shop::class)]
interface Shop
{

    public function getCategory(?UserGroup $group): array;

    public function getItem(int|string $commodityId, ?User $user = null, ?UserGroup $group = null): array;

    public function getSharedStock(int|Commodity $commodity, ?string $race = null, ?array $sku = []): string|null;

    public function updateSharedStock(int|Commodity $commodity, ?string $race = null, ?array $sku = []): void;

    public function getSharedStockHash(int $id, ?string $race = null, ?array $sku = []): string;

    public function getItemStock(int|Commodity|string $commodity, ?string $race = null, ?array $sku = []): string;

    public function getHideStock(int|string|null $stock): string;

    public function getStockState(int|string|null $stock): int;

    public function getDraft(int|Commodity|string $commodity, int $cardId): array;

    public function substationPriceIncrease(Commodity &$commodity): void;

    public function getSubstationPrice(Commodity|int $commodity, int|string|float $amount): string;
}