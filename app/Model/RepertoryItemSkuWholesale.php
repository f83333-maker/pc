<?php
declare(strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\HasOne;
use Kernel\Annotation\Inject;
use Kernel\Database\Model;

class RepertoryItemSkuWholesale extends Model
{
    use \Kernel\Component\Inject;

    protected ?string $table = "repertory_item_sku_wholesale";

    public bool $timestamps = false;

    protected array $casts = ['id' => 'integer', 'sku_id' => 'integer', 'user_id' => 'integer', 'quantity' => 'integer'];

    #[Inject]
    protected \App\Service\User\Item $item;

    public function sku(): HasOne
    {
        return $this->hasOne(RepertoryItemSku::class, "id", "sku_id");
    }

    public function saved(): void
    {
        $this->sku && $this->item->syncRepertoryItems($this->sku->repertory_item_id);
    }

}