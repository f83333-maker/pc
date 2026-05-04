<?php
declare(strict_types=1);

namespace App\Model;

use Kernel\Database\Model;

class RolePermission extends Model
{
    
    protected ?string $table = "role_permission";

    public bool $timestamps = false;

    protected array $casts = ['id' => 'integer' , 'role_id' => 'integer' , 'permission_id' => 'integer'];
}