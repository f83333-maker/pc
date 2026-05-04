<?php
declare(strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\HasMany;
use Kernel\Database\Model;

class RepertoryCategory extends Model
{

    protected ?string $table = "repertory_category";

    public bool $timestamps = false;

    protected array $casts = ['id' => 'integer', 'sort' => 'integer', 'status' => 'integer'];

    public function repertoryItem(): HasMany
    {
        return $this->hasMany(RepertoryItem::class, 'repertory_category_id', 'id');
    }
}