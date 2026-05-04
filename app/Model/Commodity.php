<?php
declare(strict_types=1);

namespace App\Model;

use App\Util\Ini;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Kernel\Exception\JSONException;

class Commodity extends Model
{

    protected $table = 'commodity';

    public $timestamps = false;

    protected $casts = [
        'id' => 'integer',
        'factory_price' => 'float',
        'price' => 'float',
        'user_price' => 'float',
        'shared_premium' => 'float',
        'status' => 'integer',
        'hide' => 'integer',
        'stock' => 'integer',
        'owner' => 'integer',
        'integral' => 'integer',
        'delivery_way' => 'integer',
        'delivery_auto_mode' => 'integer',
        'contact_type' => 'integer',
        'sort' => 'integer',
        'coupon' => 'integer',
        'shared_id' => 'integer',
        'seckill_status' => 'integer',
        'password_status' => 'integer',
        'category_id' => 'integer',
        'api_status' => 'integer',
        'draft_status' => 'integer',
        'draft_premium' => 'float',
        'inventory_hidden' => 'integer',
        'send_email' => 'integer',
        'only_user' => 'integer',
        'purchase_count' => 'integer',
        'minimum' => 'integer',
        'maximum' => 'integer',
        'shared_amount_sync' => 'integer',
        'shared_config_sync' => 'integer',
        'shared_sync' => 'integer',
        'shared_stock' => 'json'
    ];

    public function owner(): ?HasOne
    {
        return $this->hasOne(User::class, "id", "owner");
    }

    public function shared(): ?HasOne
    {
        return $this->hasOne(Shared::class, "id", "shared_id");
    }

    public function category(): ?HasOne
    {
        return $this->hasOne(Category::class, "id", "category_id");
    }

    public function card(): ?HasMany
    {
        return $this->hasMany(Card::class, 'commodity_id', 'id');
    }

    public function order(): ?HasMany
    {
        return $this->hasMany(Order::class, 'commodity_id', 'id');
    }

    public static function parseGroupConfig(?string $config, ?UserGroup $group): ?array
    {
        if (!$group) {
            return null;
        }

        $levelPrice = (array)json_decode((string)$config, true);

        if (!array_key_exists($group->id, $levelPrice)) {
            return null;
        }

        $var = $levelPrice[$group->id];

        $parse = [];
        $parse['amount'] = (float)$var['amount'];
        $parse['config'] = Ini::toArray((string)$var['config']);
        $parse['show'] = (int)$var['show'];
        return $parse;
    }

    public static function premiumConfig(string $config, int $type, float $premium): string
    {
        $configs = Ini::toArray($config);

        if (array_key_exists("category", $configs)) {
            foreach ($configs['category'] as $ck => $cv) {

                $price = $type == 0 ? (float)$cv + $premium : (float)$cv + ($premium * (float)$cv);
                $price = (int)($price * 100) / 100;
                $configs['category'][$ck] = $price;
            }
        }
        return Ini::toConfig($configs);
    }
}