<?php
declare(strict_types=1);

namespace App\Model;

use App\Util\Client;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    
    protected $table = "business";

    public $timestamps = false;

    protected $casts = ['id' => 'integer', 'user_id' => 'integer', 'master_display' => 'integer'];

    public static function get(): ?Business
    {
        $domain = Client::getDomain();
        return self::query()->where("subdomain", $domain)->first() ?? self::query()->where("topdomain", $domain)->first();
    }

    public static function state(): bool
    {
        $domain = Client::getDomain();
        return self::query()->where("subdomain", $domain)->exists() || self::query()->where("topdomain", $domain)->exists();
    }

    public function user(): ?\Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, "id", "user_id");
    }
}