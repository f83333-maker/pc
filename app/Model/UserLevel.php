<?php
declare(strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\HasMany;
use Hyperf\Database\Model\Relations\HasOne;
use Kernel\Database\Model;

class UserLevel extends Model
{

    protected ?string $table = "user_level";

    public bool $timestamps = false;

    protected array $casts = ['id' => 'integer', 'user_id' => 'integer', 'is_upgradable' => 'integer', 'sort' => 'integer'];

    public function itemSkuLevel(): hasOne
    {
        return $this->hasOne(ItemSkuLevel::class, "level_id", "id");
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, "id", "user_id")->select(["id", "username", "avatar", "group_id", "level_id"]);
    }

    public function member(): HasMany
    {
        return $this->hasMany(User::class, "level_id", "id")->select(["id", "username", "avatar", "group_id", "level_id"]);
    }

    public function itemSkuWholesaleLevel(): HasOne
    {
        return $this->hasOne(ItemSkuWholesaleLevel::class, "level_id", "id");
    }

}