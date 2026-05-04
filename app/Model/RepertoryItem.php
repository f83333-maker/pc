<?php
declare(strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\HasMany;
use Hyperf\Database\Model\Relations\HasOne;
use Kernel\Annotation\Inject;
use Kernel\Database\Model;
use Kernel\Util\Date;

class RepertoryItem extends Model
{

    use \Kernel\Component\Inject;

    protected ?string $table = "repertory_item";

    public bool $timestamps = false;

    protected array $casts = ['id' => 'integer', 'markup_template_id' => 'integer', 'exception_total' => 'integer', 'is_review' => 'integer', 'markup_mode' => 'integer', 'markup' => 'json', 'version' => 'json', 'user_id' => 'integer', 'ship_config_id' => 'integer', 'refund_mode' => 'integer', 'money_freeze_time' => 'integer', 'repertory_category_id' => 'integer', 'status' => 'integer', 'sort' => 'integer', 'item_type' => 'integer', 'privacy' => 'integer'];

    #[Inject]
    protected \App\Service\User\Item $item;

    public function saved(): void
    {
        $this->id && $this->item->syncRepertoryItems($this->id);
    }

    public function sku(): HasMany
    {
        return $this->hasMany(RepertoryItemSku::class, "repertory_item_id", "id");
    }

    public function category(): HasOne
    {
        return $this->hasOne(RepertoryCategory::class, "id", "repertory_category_id");
    }

    public function supplier(): HasOne
    {
        return $this->hasOne(User::class, "id", "user_id")->select(["id", "username", "avatar"]);
    }

    public function order(): HasMany
    {
        return $this->hasMany(RepertoryOrder::class, "repertory_item_id", "id");
    }

    public function userItem(): HasMany
    {
        return $this->HasMany(Item::class, "repertory_item_id", "id");
    }

    public function todayOrder(): HasMany
    {
        return $this->order()->whereBetween("trade_time", [Date::calcDay(), Date::calcDay(1)]);
    }

    public function yesterdayOrder(): HasMany
    {
        return $this->order()->whereBetween("trade_time", [Date::calcDay(-1), Date::calcDay()]);
    }

    public function weekdayOrder(): HasMany
    {
        return $this->order()->whereBetween("trade_time", [Date::getDateByWeekday(1) . " 00:00:00", Date::getDateByWeekday(7) . " 23:59:59"]);
    }

    public function monthOrder(): HasMany
    {
        return $this->order()->whereBetween("trade_time", [Date::getFirstDayOfMonth() . " 00:00:00", Date::getLastDayOfMonth() . " 23:59:59"]);
    }

    public function lastMonthOrder(): HasMany
    {
        return $this->order()->whereBetween("trade_time", [Date::getFirstDayOfLastMonth() . " 00:00:00", Date::getLastDayOfLastMonth() . " 23:59:59"]);
    }
}