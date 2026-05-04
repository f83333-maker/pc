<?php
declare (strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $table = 'category';

    public $timestamps = false;

    protected $casts = ['id' => 'integer', 'status' => 'integer', 'sort' => 'integer', 'owner' => 'integer'];

    public function owner(): ?\Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, "id", "owner");
    }

    public function children(): ?\Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Commodity::class, "category_id", "id");
    }

    public function getLevelConfig(?UserGroup $group): ?array
    {
        if (!$group) {
            return null;
        }
        $decode = (array)json_decode((string)$this->attributes['user_level_config'], true);
        if (!array_key_exists($group->id, $decode)) {
            return null;
        }
        return (array)$decode[$group->id];
    }

}