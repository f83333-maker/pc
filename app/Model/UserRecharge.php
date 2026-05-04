<?php
declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserRecharge extends Model
{

    protected $table = "user_recharge";

    public $timestamps = false;

    protected $casts = ['amount' => 'float', 'id' => 'integer', 'pay_id' => 'integer', 'status' => 'integer', 'user_id' => 'integer'];

    public function user(): ?HasOne
    {
        return $this->hasOne(User::class, "id", "user_id");
    }

    public function pay(): ?HasOne
    {
        return $this->hasOne(Pay::class, "id", "pay_id");
    }
}