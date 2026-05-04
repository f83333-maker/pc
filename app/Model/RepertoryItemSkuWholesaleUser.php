<?php
declare(strict_types=1);

namespace App\Model;

use Kernel\Database\Model;

class RepertoryItemSkuWholesaleUser extends Model
{
    
    protected ?string $table = "repertory_item_sku_wholesale_user";

    public bool $timestamps = false;

    protected array $casts = ['id' => 'integer', 'wholesale_id' => 'integer', 'user_id' => 'integer', 'customer_id' => 'integer', 'status' => 'integer'];
}  