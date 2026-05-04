<?php
declare(strict_types=1);

namespace App\Model;

use Kernel\Database\Model;

class ItemSkuLevel extends Model
{
    
    protected ?string $table = "item_sku_level";

    public bool $timestamps = false;

    protected array $casts = ['id' => 'integer' , 'user_id' => 'integer' , 'level_id' => 'integer' , 'sku_id' => 'integer' ,   'status' => 'integer'];
}