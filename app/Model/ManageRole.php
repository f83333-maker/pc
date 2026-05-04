<?php
declare(strict_types=1);

namespace App\Model;

use Kernel\Database\Model;

class ManageRole extends Model
{
    
    protected ?string $table = "manage_role";

    public bool $timestamps = false;

    protected array $casts = ['id' => 'integer' , 'manage_id' => 'integer' , 'role_id' => 'integer'];
}