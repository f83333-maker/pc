<?php
declare (strict_types=1);

namespace App\Service\User;

use App\Entity\Shop\CartItem;
use App\Model\User;
use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\User\Bind\Cart::class)]
interface Cart
{

    public function getItems(?User $customer, string $clientId): array;

    public function getClientId(?User $customer, string $clientId): string;

    public function getAmount(?User $customer, string $clientId): string;

    public function getItem(?User $customer, string $clientId, int $itemId): CartItem;

    public function add(?User $customer, string $clientId, int $quantity, int $skuId, array $option): bool;

    
    public function changeQuantity(?User $customer, string $clientId, int $itemId, int $quantity): void;

    public function updateOption(?User $customer, string $clientId, int $itemId, array $option): void;

    
    public function del(?User $customer, string $clientId, int $itemId): bool;

    
    public function clear(?User $customer, string $clientId): void;

    
    public function bindUser(User $customer, string $clientId): void;
}