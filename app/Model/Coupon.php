<?php
declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    
    protected $table = "coupon";

    public $timestamps = false;

    protected $casts = ['commodity_id' => 'integer', 'id' => 'integer', 'category_id' => 'integer', 'mode' => 'integer', 'money' => 'float', 'owner' => 'integer', 'status' => 'integer', 'life' => 'integer', 'use_life' => 'integer', 'sku' => 'json'];

    public function owner(): ?\Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, "id", "owner");
    }

    public function commodity(): ?\Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Commodity::class, "id", "commodity_id");
    }

    public function category(): ?\Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Category::class, "id", "category_id");
    }
}