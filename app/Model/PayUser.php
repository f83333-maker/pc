<?php
declare(strict_types=1);

namespace App\Model;

use Kernel\Database\Model;

class PayUser extends Model
{
    protected ?string $table = 'pay_user';
    public bool $timestamps = false;
    protected array $casts = ['id' => 'integer', 'user_id' => 'integer', 'pay_id' => 'integer', 'fee' => 'float', 'status' => 'integer'];
}