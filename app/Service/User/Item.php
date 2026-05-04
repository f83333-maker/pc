<?php
declare (strict_types=1);

namespace App\Service\User;

use App\Entity\Shop\Markup;
use App\Entity\Shop\QuantityRestriction;
use App\Model\ItemSku;
use App\Model\RepertoryItem;
use App\Model\RepertoryItemSku;
use App\Model\User;
use Hyperf\Collection\Collection;
use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\User\Bind\Item::class)]
interface Item
{
    
    public function list(?User $customer, ?int $categoryId, ?User $merchant, ?string $keywords = null, ?int $page = null, ?int $size = null): array;

    public function getItem(?User $customer, int $itemId, ?User $user): \App\Entity\Shop\Item;

    public function getItemEntity(?User $customer, \App\Model\Item $item, Collection $itemSku, bool $source = false): ?\App\Entity\Shop\Item;

    public function loadRepertoryItem(int $categoryId, int $itemId, int|array $markupId, ?User $user = null, bool $available = false): void;

    public function getPercentageAmount(string $amount, string $percentage, int $keepDecimals): string;

    public function syncRepertoryItem(\App\Model\Item $item, RepertoryItem $repertoryItem): void;

    public function syncRepertoryItems(int $itemId): void;

    
    public function syncRepertoryItemForMarkupTemplate(int $markupTemplateId): void;

    
    public function getMarkup(int|\App\Model\Item $item): Markup;

    
    public function getSku(int $skuId): ItemSku;

    
    public function getWholesale(?User $customer, int $skuId): array;

    public function getQuantityRestriction(?int $userId, ?RepertoryItemSku $itemSku): QuantityRestriction;
}