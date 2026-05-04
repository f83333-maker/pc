<?php
declare(strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\HasOne;
use Kernel\Database\Model;

class ItemSku extends Model
{
    
    protected ?string $table = "item_sku";

    public bool $timestamps = false;

    protected array $casts = ['id' => 'integer', 'repertory_item_sku_id' => 'integer', 'user_id' => 'integer', 'item_id' => 'integer', 'sort' => 'integer', 'private_display' => 'integer'];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, "id", "user_id");
    }

    public function repertoryItemSku(): HasOne
    {
        return $this->hasOne(RepertoryItemSku::class, "id", "repertory_item_sku_id");
    }

    public function item(): HasOne
    {
        return $this->hasOne(Item::class, "id", "item_id");
    }
}