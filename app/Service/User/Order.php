<?php
declare (strict_types=1);

namespace App\Service\User;

use App\Entity\Shop\CreateOrder;
use App\Entity\Shop\OrderItem;
use App\Entity\Shop\Trade;
use App\Model\ItemSku;
use App\Model\User;
use Kernel\Annotation\Bind;
use Kernel\Plugin\Handle\Ship;

#[Bind(class: \App\Service\User\Bind\Order::class)]
interface Order
{

    public function create(CreateOrder $createOrder, ?callable $callable = null): mixed;

    
    public function trade(array $items, string $clientId, string $createIp, string $createUa, ?User $customer = null, ?User $user = null, ?User $invite = null): Trade;

    
    public function recharge(string $amount, string $clientId, string $createIp, string $createUa, ?User $customer = null): Trade;

    public function cancel(string $tradeNo): bool;

    public function getAmount(?User $customer, ItemSku $itemSku, int $quantity = 1): string;

    
    public function getDividendAmount(?User $invite, ItemSku $itemSku, int $quantity = 1): string;

    
    public function deliver(\App\Model\Order $order, string $clientIp): void;

    
    public function syncDeliver(string $tradeNo, ?string $treasure, int $status): void;

    
    public function itemRestock(int $orderItemId): void;

    public function getItemSold(int $repertoryItemId): int;

    
    public function getCheckoutOrder(string $tradeNo): \App\Entity\Shop\Order;

    public function getOrder(?User $customer, string $clientId, ?string $tradeNo): ?\App\Entity\Shop\Order;

    public function getOrderItem(int|\App\Model\OrderItem $idOrModel): ?OrderItem;

    
    public function autoReceipt(?int $userId = null): void;

    
    public function receipt(int $orderItemId): void;

    
    public function getOrderItemShip(int|\App\Model\OrderItem $idOrModel): ?\Kernel\Plugin\Handle\Ship;

    public function hydrateOrderItemRenderFlags(array &$list): void;

    
    public function dividend(\App\Model\Order $order, \App\Model\OrderItem $orderItem, int $balanceStatus, int $balanceFreeze): void;

    
    public function limiter(string $ip, int $type, int $time, int $quantity, string $message): void;

    
    public function clearUnpaidOrder(int $userId, int $type): void;

}