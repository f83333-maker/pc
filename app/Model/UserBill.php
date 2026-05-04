<?php
declare(strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\HasOne;
use Kernel\Database\Model;

class UserBill extends Model
{
    
    protected ?string $table = "user_bill";

    public bool $timestamps = false;

    protected array $casts = ['id' => 'integer', 'status' => 'integer', 'action' => 'integer', 'user_id' => 'integer', 'type' => 'integer', 'is_withdraw' => 'integer'];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id')->select(['id', 'username', 'avatar']);
    }
}