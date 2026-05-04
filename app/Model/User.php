<?php
declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Model
{
    
    protected $table = 'user';

    public $timestamps = false;

    protected $casts = ['id' => 'integer', 'settlement' => 'integer', 'business_level' => 'integer', 'balance' => 'float', 'coin' => 'float', 'total_coin' => 'float', 'integral' => 'integer', 'pid' => 'integer', 'recharge' => 'float', 'status' => 'integer'];

    protected $appends = ['group'];

    public function getGroupAttribute(): ?UserGroup
    {
        return UserGroup::get((float)$this->attributes['recharge']);
    }

    public function parent(): ?HasOne
    {
        return $this->hasOne(User::class, "id", "pid");
    }

    public function businessLevel(): ?HasOne
    {
        return $this->hasOne(BusinessLevel::class, "id", "business_level");
    }

    public function business(): ?HasOne
    {
        return $this->hasOne(Business::class, "user_id", "id");
    }
}