<?php
declare(strict_types=1);

namespace App\Model;

use Kernel\Database\Model;

class PayGroup extends Model
{
    protected ?string $table = 'pay_group';
    public bool $timestamps = false;
    protected array $casts = ['id' => 'integer', 'group_id' => 'integer', 'pay_id' => 'integer', 'fee' => 'float', 'status' => 'integer'];
}