<?php
declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Card extends Model
{
    
    protected $table = "card";

    public $timestamps = false;

    protected $casts = ['commodity_id' => 'integer', 'id' => 'integer', 'order_id' => 'integer', 'owner' => 'integer', 'status' => 'integer', 'sku' => 'json'];

    public function owner(): ?HasOne
    {
        return $this->hasOne(User::class, "id", "owner");
    }

    public function commodity(): ?HasOne
    {
        return $this->hasOne(Commodity::class, "id", "commodity_id");
    }

    public function order(): ?HasOne
    {
        return $this->hasOne(Order::class, "id", "order_id");
    }
}