<?php
declare(strict_types=1);

namespace App\Model;

use Kernel\Database\Model;

class ItemSkuWholesale extends Model
{

    protected ?string $table = "item_sku_wholesale";

    public bool $timestamps = false;

    protected array $casts = ['id' => 'integer', 'repertory_item_sku_wholesale_id' => 'integer', 'user_id' => 'integer', 'sku_id' => 'integer', 'quantity' => 'integer'];
}