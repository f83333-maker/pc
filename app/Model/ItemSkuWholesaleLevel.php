<?php
declare(strict_types=1);

namespace App\Model;

use Kernel\Database\Model;

class ItemSkuWholesaleLevel extends Model
{

    protected ?string $table = "item_sku_wholesale_level";

    public bool $timestamps = false;

    protected array $casts = ['id' => 'integer' , 'user_id' => 'integer' , 'wholesale_id' => 'integer' , 'level_id' => 'integer' ,  'status' => 'integer'];
}