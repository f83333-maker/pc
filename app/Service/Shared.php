<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Commodity;
use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Bind\Shared::class)]
interface Shared
{

    public function connect(string $domain, string $appId, string $appKey, int $type = 0): ?array;

    public function items(\App\Model\Shared $shared): ?array;

    public function item(\App\Model\Shared $shared, string $code): array;

    public function inventoryState(\App\Model\Shared $shared, Commodity $commodity, int $cardId, int $num, string $race): bool;

    public function inventory(\App\Model\Shared $shared, Commodity $commodity, string $race = ""): array;

    public function trade(\App\Model\Shared $shared, Commodity $commodity, string $contact, int $num, int $cardId, int $device, string $password, string $race, ?array $sku, ?string $widget, string $requestNo): string;

    public function draftCard(\App\Model\Shared $shared, string $code, array $map = []): array;

    public function getDraft(\App\Model\Shared $shared, string $code, int $cardId): array;

    public function getItemStock(Commodity $commodity, \App\Model\Shared $shared, string $code, ?string $race = null, ?array $sku = []): string;

    public function getValuation(Commodity $commodity, \App\Model\Shared $shared, string $code, int $num, ?string $race = null, ?array $sku = [], ?int $cardId = 0): string|float|int;

    public function AdjustmentPrice(string $config, string $price, string $userPrice, int $type, float $premium): array;

    public function AdjustmentAmount(int $type, float $premium, string|int|float $amount): string;

    public function syncRemoteItem(Commodity|int $commodity): bool;
}