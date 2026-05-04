<?php
declare(strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\HasMany;
use Hyperf\Database\Model\Relations\HasOne;
use Kernel\Annotation\Inject;
use Kernel\Database\Model;
use Kernel\Log\Log;

class RepertoryItemSku extends Model
{

    use \Kernel\Component\Inject;

    protected ?string $table = "repertory_item_sku";

    public bool $timestamps = false;

    protected array $casts = ['id' => 'integer', 'user_id' => 'integer', 'market_control_only_num' => 'integer', 'private_display' => 'integer', 'market_control_max_num' => 'integer', 'market_control_min_num' => 'integer', 'repertory_item_id' => 'integer', 'market_control_status' => 'integer', 'sort' => 'integer', 'version' => 'json'];

    #[Inject]
    protected \App\Service\User\Item $item;

    public function saved(): void
    {
        try {
            $this->repertory_item_id && $this->item->syncRepertoryItems($this->repertory_item_id);
        } catch (\Throwable $e) {
            Log::inst()->error("商品同步失败：{$e->getMessage()}");
        }
    }

    public function deleted(): void
    {
        try {
            $this->repertory_item_id && $this->item->syncRepertoryItems($this->repertory_item_id);
        } catch (\Throwable $e) {
            Log::inst()->error("商品同步失败：{$e->getMessage()}");
        }
    }

    public function repertoryItem(): HasOne
    {
        return $this->hasOne(RepertoryItem::class, "id", "repertory_item_id");
    }

    public function wholesale(): HasMany
    {
        return $this->hasMany(RepertoryItemSkuWholesale::class, "sku_id", "id");
    }

}