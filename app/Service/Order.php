<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Commodity;
use App\Model\User;
use App\Model\UserGroup;
use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Bind\Order::class)]
interface Order
{

    public function calcAmount(int $owner, int $num, Commodity $commodity, ?UserGroup $group, ?string $race = null, bool $disableSubstation = false): float;

    public function valuation(Commodity|int $commodity, int $num = 1, ?string $race = null, ?array $sku = [], ?int $cardId = null, ?string $coupon = null, ?UserGroup $group = null): string;

    public function getCost(Commodity|int $commodity, int $num = 1, ?string $race = null, ?array $sku = [], ?int $cardId = null): string;

    public function getValuationPrice(int $commodityId, string|float|int $price, ?UserGroup $group = null): string;

    public function parseConfig(Commodity &$commodity, ?UserGroup $group): void;

    public function userDefinedPrice(Commodity $commodity, ?UserGroup $group): ?array;

    
    public function trade(?User $user, ?UserGroup $userGroup, array $map): array;

    
    public function getTradeAmount(?User $user, ?UserGroup $userGroup, int $cardId, int $num, string $coupon, int|Commodity|null $commodityId, ?string $race = null, ?array $sku = [], bool $disableShared = false): array;

    
    public function callback(string $handle, array $map): string;

    public function getCallbackTradeNo(string $handle, array $map): ?string;

    public function orderSuccess(\App\Model\Order $order): string;

    public function callbackInitialize(string $handle, array $map): array;

    public function giftOrder(Commodity $commodity, string $race = "", int $num = 1, string $contact = "", string $password = "", ?int $cardId = null, int $userId = 0, string $widget = "[]"): array;
}