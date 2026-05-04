<?php
declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    
    protected $table = "order";

    public $timestamps = false;

    protected $casts = ['amount' => 'float', 'cost' => 'float', 'rebate' => 'float', 'divide_amount' => 'float', 'rent' => 'float', 'premium' => 'float', 'user_id' => 'integer', 'substation_user_id' => 'integer', 'from' => 'integer', 'commodity_id' => 'integer', 'card_id' => 'integer', 'card_num' => 'integer', 'create_device' => 'integer', 'delivery_status' => 'integer', 'id' => 'integer', 'owner' => 'integer', 'pay_id' => 'integer', 'status' => 'integer', 'sku' => 'json'];

    public function owner(): ?HasOne
    {
        return $this->hasOne(User::class, "id", "owner");
    }

    public function user(): ?HasOne
    {
        return $this->hasOne(User::class, "id", "user_id");
    }

    public function commodity(): ?HasOne
    {
        return $this->hasOne(Commodity::class, "id", "commodity_id");
    }

    public function pay(): ?HasOne
    {
        return $this->hasOne(Pay::class, "id", "pay_id");
    }

    public function card(): ?HasOne
    {
        return $this->hasOne(Card::class, "id", "card_id");
    }

    public function promote(): ?HasOne
    {
        return $this->hasOne(User::class, "id", "from");
    }

    public function substationUser(): HasOne
    {
        return $this->hasOne(User::class, "id", "substation_user_id");
    }

    public function coupon(): ?HasOne
    {
        return $this->hasOne(Coupon::class, "id", "coupon_id");
    }
}