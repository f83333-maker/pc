<?php
declare(strict_types=1);

namespace App\Model;

use Kernel\Database\Model;

class PluginConfig extends Model
{
    protected ?string $table = 'plugin_config';
    public bool $timestamps = false;
    protected array $casts = ['id' => 'integer', 'user_id' => 'integer', 'config' => 'json'];
}