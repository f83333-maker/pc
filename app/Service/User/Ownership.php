<?php
declare (strict_types=1);

namespace App\Service\User;

use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\User\Bind\Ownership::class)]
interface Ownership
{
    
    public function itemSku(int $userId, int $skuId): bool;

    public function level(int $userId, int $levelId): bool;

    public function ownMember(int $userId, int $memberId): bool;

    
    public function item(int $userId, int $itemId): bool;

    
    public function wholesale(int $userId, int $wholesaleId): bool;

    public function markup(int $userId, int $markupId): bool;

    
    public function orderItem(int $customerId, int $orderItemId): bool;

    public function throw(bool ...$state): void;
}