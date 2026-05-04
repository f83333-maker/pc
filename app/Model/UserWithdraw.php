<?php
declare(strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\HasOne;
use Kernel\Database\Model;

class UserWithdraw extends Model
{
    protected ?string $table = 'user_withdraw';
    public bool $timestamps = false;
    protected array $casts = ['id' => 'integer', 'user_id' => 'integer', 'card_id' => 'integer', 'amount' => 'float', 'status' => 'integer'];

    
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function card(): HasOne
    {
        return $this->hasOne(UserBankCard::class, 'id', 'card_id')->with(['bank']);
    }
}