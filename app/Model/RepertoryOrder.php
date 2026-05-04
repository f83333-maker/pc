<?php
declare(strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\HasMany;
use Hyperf\Database\Model\Relations\HasOne;
use Kernel\Component\Inject;
use Kernel\Database\Model;
use Kernel\Plugin\Const\Point;
use Kernel\Plugin\Plugin;
use Kernel\Plugin\Usr;

class RepertoryOrder extends Model
{

    use Inject;

    #[\Kernel\Annotation\Inject]
    private \App\Service\User\Order $order;

    protected ?string $table = "repertory_order";

    public bool $timestamps = false;

    protected array $casts = ['id' => 'integer', 'user_id' => 'integer', 'customer_id' => 'integer', 'repertory_item_id' => 'integer', 'repertory_item_sku_id' => 'integer', 'quantity' => 'integer', 'status' => 'integer'];

    public function saved(): void
    {
        $this->id && $this->order->syncDeliver($this->item_trade_no, $this->contents, $this->status == 1 ? 1 : 0);
        Plugin::inst()->unsafeHook(Usr::inst()->userToEnv($this->user_id), Point::MODEL_REPERTORY_ORDER_SAVE, \Kernel\Plugin\Const\Plugin::HOOK_TYPE_PAGE, $this);
    }

    public function supplier(): HasOne
    {
        return $this->hasOne(User::class, "id", "user_id")->select(["id", "username", "avatar"]);
    }

    public function customer(): HasOne
    {
        return $this->hasOne(User::class, "id", "customer_id")->select(["id", "username", "avatar"]);
    }

    public function item(): HasOne
    {
        return $this->hasOne(RepertoryItem::class, "id", "repertory_item_id")->select(["id", "name", "picture_thumb_url", "plugin"]);
    }

    public function sku(): HasOne
    {
        return $this->hasOne(RepertoryItemSku::class, "id", "repertory_item_sku_id")->select(["id", "name", "picture_thumb_url"]);
    }

    public function commission(): HasMany
    {
        return $this->hasMany(RepertoryOrderCommission::class, "order_id", "id")->with(["user", "parent"]);
    }

}