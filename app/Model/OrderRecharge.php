<?php
declare(strict_types=1);

namespace App\Model;

use Kernel\Database\Model;

class OrderRecharge extends Model
{
    protected ?string $table = 'order_recharge';
    public bool $timestamps = false;
    protected array $casts = ['id' => 'integer', 'user_id' => 'integer', 'order_id' => 'integer', 'amount' => 'float', 'status' => 'integer'];
}