<?php
declare(strict_types=1);

namespace App\Model;

use Kernel\Database\Model;

class RepertoryItemSkuUser extends Model
{
    
    protected ?string $table = "repertory_item_sku_user";

    public bool $timestamps = false;

    protected array $casts = ['id' => 'integer', 'user_id' => 'integer', 'market_control_only_num' => 'integer', 'market_control_max_num' => 'integer', 'market_control_min_num' => 'integer', 'customer_id' => 'integer', 'sku_id' => 'integer', 'status' => 'integer', 'market_control_status' => 'integer'];
}