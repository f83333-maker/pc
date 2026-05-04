<?php
declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderOption extends Model
{
    
    protected $table = "order_option";

    public $timestamps = false;

    protected $casts = ['id' => 'integer', 'order_id' => 'integer'];

    public static function create(int $orderId, array $option): void
    {
        $orderOption = new self();
        $orderOption->order_id = $orderId;
        $orderOption->option = json_encode($option);
        $orderOption->save();
    }

    public static function get(int $orderId): ?array
    {
        $orderOption = self::query()->where("order_id", $orderId)->first();
        if (!$orderOption) {
            return null;
        }
        return (array)json_decode($orderOption->option, true);
    }
}