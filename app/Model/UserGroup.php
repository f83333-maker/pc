<?php
declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    
    private static mixed $userGroups = null;

    protected $table = "user_group";

    public $timestamps = false;

    protected $casts = ['id' => 'integer', 'recharge' => 'float', 'discount_config' => 'json'];

    public static function get(float $recharge, bool $next = false): ?UserGroup
    {
        if (!self::$userGroups) {
            self::$userGroups = UserGroup::query()->orderBy("recharge", "desc")->get();
        }
        foreach (self::$userGroups as $index => $group) {
            if ($recharge >= $group->recharge) {
                if ($next) {
                    return self::$userGroups[$index - 1];
                }
                return $group;
            }
        }
        return null;
    }

    public static function getRechargeScope(int $groupId): ?array
    {
        if (!self::$userGroups) {
            self::$userGroups = UserGroup::query()->orderBy("recharge", "desc")->get();
        }
        foreach (self::$userGroups as $index => $group) {
            if ($groupId === $group->id) {
                $next = self::$userGroups[$index - 1];
                return ["min" => $group->recharge, "max" => $next ? $next->recharge : 0];
            }
        }
        return null;
    }

}