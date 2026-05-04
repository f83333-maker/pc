<?php
declare(strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\HasOne;
use Kernel\Annotation\Inject;
use Kernel\Database\Model;

class ItemMarkupTemplate extends Model
{
    use \Kernel\Component\Inject;

    protected ?string $table = "item_markup_template";

    public bool $timestamps = false;

    protected array $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'drift_model' => 'integer',
        'sync_name' => 'integer',
        'sync_introduce' => 'integer',
        'sync_picture' => 'integer',
        'sync_sku_name' => 'integer',
        'sync_sku_picture' => 'integer',
        'sync_amount' => 'integer'
    ];

    #[Inject]
    private \App\Service\User\Item $item;

    public function saved(): void
    {
        $this->id && $this->item->syncRepertoryItemForMarkupTemplate($this->id);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, "id", "user_id");
    }
}