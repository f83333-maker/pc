<?php
declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CommodityGroup extends Model
{
    
    protected $table = "commodity_group";

    public $timestamps = false;

    protected $casts = ['id' => 'integer', 'commodity_list' => 'json'];
}