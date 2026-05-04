<?php
declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BusinessLevel extends Model
{
    
    protected $table = "business_level";

    public $timestamps = false;

    protected $casts = ['id' => 'integer', 'cost' => 'float', 'accrual' => 'float', 'substation' => 'integer', 'top_domain' => 'integer', 'supplier' => 'integer', 'price' => 'float'];
}