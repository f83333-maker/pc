<?php
declare(strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\HasOne;
use Kernel\Database\Model;

class CartItem extends Model
{
    protected ?string $table = 'cart_item';
    public bool $timestamps = false;
    protected array $casts = ['id' => 'integer', 'cart_id' => 'integer', 'quantity' => 'integer', 'sku_id' => 'integer', 'option' => 'json'];

    
    public function sku(): HasOne
    {
        return $this->hasOne(ItemSku::class, "id", "sku_id");
    }
}