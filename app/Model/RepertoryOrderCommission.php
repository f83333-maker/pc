<?php
declare(strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\HasOne;
use Kernel\Database\Model;

class RepertoryOrderCommission extends Model
{

    protected ?string $table = "repertory_order_commission";

    public bool $timestamps = false;

    protected array $casts = ['id' => 'integer', 'order_id' => 'integer', 'user_id' => 'integer', 'pid' => 'integer'];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, "id", "user_id")->select(["id", "username", "avatar"]);
    }

    public function parent(): HasOne
    {
        return $this->hasOne(User::class, "id", "pid")->select(["id", "username", "avatar"]);
    }
}