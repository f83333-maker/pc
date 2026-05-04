<?php
declare(strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\HasOne;
use Kernel\Database\Model;

class UserIdentity extends Model
{
    protected ?string $table = 'user_identity';
    public bool $timestamps = false;
    protected array $casts = ['id' => 'integer', 'user_id' => 'integer', 'type' => 'integer', 'status' => 'integer'];

    
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}