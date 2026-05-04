<?php
declare(strict_types=1);

namespace App\Model;

use Kernel\Database\Model;

class RepertoryItemSkuGroup extends Model
{

    protected ?string $table = "repertory_item_sku_group";

    public bool $timestamps = false;

    protected array $casts = ['id' => 'integer', 'market_control_only_num' => 'integer', 'market_control_max_num' => 'integer', 'market_control_min_num' => 'integer' , 'group_id' => 'integer', 'user_id' => 'integer', 'sku_id' => 'integer', 'market_control_status' => 'integer', 'status' => 'integer'];
}