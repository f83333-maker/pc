<?php
declare(strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\HasMany;
use Hyperf\Database\Model\Relations\HasOne;
use Kernel\Database\Model;
use Kernel\Util\Date;

class Item extends Model
{

    protected ?string $table = "item";

    public bool $timestamps = false;

    protected array $casts = ['id' => 'integer', 'user_id' => 'integer', 'markup_template_id' => 'integer', 'markup_mode' => 'integer', 'repertory_item_id' => 'integer', 'category_id' => 'integer', 'status' => 'integer', 'sort' => 'integer', 'recommend' => 'integer', 'markup' => 'json'];

    public function category(): HasOne
    {
        return $this->hasOne(Category::class, "id", "category_id")->select();
    }

    public function sku(): HasMany
    {
        return $this->hasMany(ItemSku::class, "item_id", "id");
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, "id", "user_id")->select(["id", "username", "avatar"]);
    }

    public function repertoryItem(): HasOne
    {
        return $this->hasOne(RepertoryItem::class, "id", "repertory_item_id");
    }

    public function order(): HasMany
    {
        return $this->hasMany(OrderItem::class, "item_id", "id");
    }

    public function todayOrder(): HasMany
    {
        return $this->order()->where("status", "!=", 0)->whereBetween("create_time", [Date::calcDay(), Date::calcDay(1)]);
    }

    public function yesterdayOrder(): HasMany
    {
        return $this->order()->where("status", "!=", 0)->whereBetween("create_time", [Date::calcDay(-1), Date::calcDay()]);
    }

    public function weekdayOrder(): HasMany
    {
        return $this->order()->where("status", "!=", 0)->whereBetween("create_time", [Date::getDateByWeekday(1) . " 00:00:00", Date::getDateByWeekday(7) . " 23:59:59"]);
    }

    public function monthOrder(): HasMany
    {
        return $this->order()->where("status", "!=", 0)->whereBetween("create_time", [Date::getFirstDayOfMonth() . " 00:00:00", Date::getLastDayOfMonth() . " 23:59:59"]);
    }

    public function lastMonthOrder(): HasMany
    {
        return $this->order()->where("status", "!=", 0)->whereBetween("create_time", [Date::getFirstDayOfLastMonth() . " 00:00:00", Date::getLastDayOfLastMonth() . " 23:59:59"]);
    }
}