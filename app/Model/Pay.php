<?php
declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Pay extends Model
{

    protected $table = "pay";

    public $timestamps = false;

    protected $casts = ['id' => 'integer', 'commodity' => 'integer', 'recharge' => 'integer', 'sort' => 'integer', 'equipment' => 'integer', 'cost_type' => 'integer', 'cost' => 'float'];
}