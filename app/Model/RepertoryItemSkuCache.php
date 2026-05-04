<?php
declare(strict_types=1);

namespace App\Model;

use Kernel\Database\Model;

class RepertoryItemSkuCache extends Model
{
    protected ?string $table = 'repertory_item_sku_cache';
    public bool $timestamps = false;
    protected array $casts = ['id' => 'integer', 'sku_id' => 'integer', 'type' => 'integer'];
}