<?php
declare (strict_types=1);

namespace App\Service\Common;

use App\Entity\Repertory\CreateItem;
use App\Entity\Repertory\CreateSku;
use App\Entity\Repertory\Markup;
use App\Model\RepertoryItemSku;
use Hyperf\Database\Model\Collection;
use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Common\Bind\RepertoryItem::class)]
interface RepertoryItem
{

    public function import(?int $userId, int $markupTemplateId, int $categoryId, int $configId, int $refundMode, int $autoReceiptTime, array $item, bool $imageDownloadLocal, bool $checkRepeat = false): void;

    public function create(CreateItem $createItem): \App\Entity\Repertory\RepertoryItem;

    public function createSku(?int $userId, int $itemId, CreateSku $sku, Markup $markup): \App\Model\RepertoryItemSku;

    public function getMarkup(int|\App\Model\RepertoryItem $item): Markup;

    public function syncRemoteItem(\App\Model\RepertoryItem $repertoryItem): void;

    public function forceSyncRemoteItemPrice(\App\Model\RepertoryItem|int $repertoryItem): void;

    public function checkForceSyncRemoteItemPrice(array $originMarkup, array $newMarkup): bool;

    public function getSyncRemoteItems(bool $isOnlyId = true, ?int $userId = null, int $second = 120): array|Collection;
}