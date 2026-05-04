<?php
declare(strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\HasOne;
use Kernel\Database\Model;

class OrderItem extends Model
{
    
    protected ?string $table = "order_item";

    public bool $timestamps = false;

    protected array $casts = ['id' => 'integer', 'user_id' => 'integer', 'order_id' => 'integer', 'item_id' => 'integer', 'sku_id' => 'integer', 'quantity' => 'integer', 'status' => 'integer', 'refund_mode' => 'integer', 'widget' => 'json'];

    
    public function sku(): ?HasOne
    {
        return $this->hasOne(ItemSku::class, "id", "sku_id");
    }

    public function item(): ?HasOne
    {
        return $this->hasOne(Item::class, "id", "item_id");
    }

    public function order(): ?HasOne
    {
        return $this->hasOne(Order::class, "id", "order_id");
    }
}