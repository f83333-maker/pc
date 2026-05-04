<?php
declare(strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\HasOne;
use Kernel\Database\Model;

class UserLifetime extends Model
{
    protected ?string $table = 'user_lifetime';
    public bool $timestamps = false;
    protected array $casts = ['id' => 'integer', 'user_id' => 'integer', 'total_consumption_amount' => 'float', 'total_recharge_amount' => 'float', 'total_referral_count' => 'integer', 'favorite_item_id' => 'integer', 'favorite_item_count' => 'integer', 'total_login_count' => 'integer', 'total_profit_amount' => 'float', 'total_withdraw_amount' => 'float', 'total_withdraw_count' => 'integer', 'share_item_id' => 'integer', 'share_item_count' => 'integer', 'login_status' => 'integer'];

    public function favoriteItem(): HasOne
    {
        return $this->hasOne(Item::class, 'id', 'favorite_item_id')->select(['id', 'name', 'picture_url', 'picture_thumb_url']);
    }

    public function shareItem(): HasOne
    {
        return $this->hasOne(Item::class, 'id', 'share_item_id')->select(['id', 'name', 'picture_url', 'picture_thumb_url']);
    }

}