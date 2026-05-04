<?php
declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserCommodity extends Model
{
    
    protected $table = "user_commodity";

    public $timestamps = false;

    protected $casts = ['id' => 'integer', 'user_id' => 'integer', 'commodity_id' => 'integer', 'status' => 'integer', 'premium' => 'integer'];

    public static function getCustom(?int $userId, int $commodityId): ?UserCommodity
    {
        if ($userId == 0 || !$userId) {
            return null;
        }

        return UserCommodity::query()->where("user_id", $userId)->where("commodity_id", $commodityId)->first();
    }
}