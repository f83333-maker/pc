<?php
declare(strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\HasOne;
use Kernel\Database\Model;

class OrderReportMessage extends Model
{
    protected ?string $table = 'order_report_message';
    public bool $timestamps = false;
    protected array $casts = ['id' => 'integer', 'order_report_id' => 'integer', 'role' => 'integer'];

    public function orderReport(): HasOne
    {
        return $this->hasOne(OrderReport::class, "id", "order_report_id");
    }
}