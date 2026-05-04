<?php
declare(strict_types=1);

namespace App\Model;

use Kernel\Database\Model;

class UserLoginLog extends Model
{
    protected ?string $table = 'user_login_log';
    public bool $timestamps = false;
    protected array $casts = ['id' => 'integer', 'user_id' => 'integer', 'is_dangerous' => 'integer'];
}