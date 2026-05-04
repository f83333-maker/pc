<?php
declare(strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Events\Deleting;
use Hyperf\Database\Model\Relations\BelongsToMany;
use Kernel\Database\Model;

class Role extends Model
{

    protected ?string $table = "role";

    public bool $timestamps = false;

    protected array $casts = ['id' => 'integer', 'status' => 'integer'];

    public function permission(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, "role_permission", "role_id", "permission_id");
    }
}