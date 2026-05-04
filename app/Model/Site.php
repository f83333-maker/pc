<?php
declare(strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\HasOne;
use Kernel\Container\Memory;
use Kernel\Database\Model;
use Kernel\Exception\NotFoundException;
use Kernel\Util\Url;

class Site extends Model
{

    protected ?string $table = "site";

    public bool $timestamps = false;

    protected array $casts = ['id' => 'integer', 'user_id' => 'integer', 'type' => 'integer', 'status' => 'integer'];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, "id", "user_id");
    }

    public static function getUser(string $host): ?User
    {
        $key = "site_host_find_sql_" . $host;
        if (Memory::instance()->has($key)) {
            return Memory::instance()->get($key);
        }

        $site = Site::query()->with(["user"])->where("host", $host)->orWhere("host", Url::getWildcard($host))->first();

        if (!$site || !$site->user) {
            return null;
        }

        if ($site->status != 1) {
            throw new NotFoundException("此站点已被关闭");
        }

        Memory::instance()->set($key, $site->user);
        return $site->user;
    }
}