<?php
declare(strict_types=1);

namespace App\Model;

use Kernel\Database\Model;

class PayOrderOption extends Model
{

    protected ?string $table = "pay_order_option";

    public bool $timestamps = false;

    protected array $casts = ['id' => 'integer', 'pay_order_id' => 'integer', 'option' => 'json']; 
}