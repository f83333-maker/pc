<?php
declare(strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\HasOne;
use Kernel\Database\Model;
use Kernel\Util\Date;

class PayOrder extends Model
{

    protected ?string $table = "pay_order";

    public bool $timestamps = false;

    protected array $casts = ['id' => 'integer', 'pay_id' => 'integer', 'order_id' => 'integer', 'status' => 'integer', 'render_mode' => 'integer', 'balance_status' => 'integer', 'user_id' => 'integer', 'customer_id' => 'integer'];

    public function pay(): ?HasOne
    {
        return $this->hasOne(Pay::class, "id", "pay_id");
    }

    public function option(): ?HasOne
    {
        return $this->hasOne(PayOrderOption::class, "pay_order_id", "id");
    }

    public function customer(): ?HasOne
    {
        return $this->hasOne(User::class, "id", "customer_id");
    }

    public function user(): ?HasOne
    {
        return $this->hasOne(User::class, "id", "user_id");
    }

    public function order(): ?HasOne
    {
        return $this->hasOne(Order::class, "id", "order_id");
    }

    public function setOption(array $option): void
    {
        $payOrderOption = new PayOrderOption();
        $payOrderOption->pay_order_id = $this->attributes['id'];
        $payOrderOption->option = $option;
        $payOrderOption->create_date = Date::current();
        $payOrderOption->save();
    }
}