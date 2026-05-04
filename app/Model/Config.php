<?php
declare (strict_types=1);

namespace App\Model;

use App\Util\Context;
use Illuminate\Database\Eloquent\Model;
use Kernel\Exception\RuntimeException;
use Kernel\Util\Binary;
use Kernel\Util\File;

class Config extends Model
{
    
    protected $table = 'config';

    public $timestamps = false;

    protected $casts = ['id' => 'integer'];

    private const CACHE_FILE = BASE_PATH . "/runtime/config";

    public static function getSessionExpire(): int
    {
        $expire = self::get("session_expire") ?: (86400 * 30);
        if ($expire < 120) {
            return 86400 * 30;
        }
        return (int)$expire;
    }

    
    public static function get(string $key): string
    {
        $cacheKey = "_DB_CONFIG_" . $key;
        $cache = Context::get($cacheKey);

        if ($cache) {
            return (string)$cache;
        }

        $configs = File::read(self::CACHE_FILE, function (string $contents) {
            return Binary::inst()->unpack($contents);
        }) ?: [];

        if (isset($configs[$key])) {
            Context::set($cacheKey, $configs[$key]);
            return (string)$configs[$key];
        }
        $cfg = Config::query()->where("key", $key)->first();
        if (!$cfg) {
            return "";
        }

        File::writeForLock(self::CACHE_FILE, function (string $contents) use ($cfg, $key) {
            $configs = Binary::inst()->unpack($contents) ?: [];
            $configs[$key] = $cfg->value;
            return Binary::inst()->pack($configs);
        });
        
        Context::set($cacheKey, $cfg->value);

        return (string)$cfg->value;
    }

    public static function list(): array
    {
        $cfg = Config::query()->get();
        $list = [];
        foreach ($cfg as $item) {
            $list[$item->key] = $item->value;
        }
        return $list;
    }

    
    public static function put(string $key, string|int $value): void
    {
        $cfg = Config::query()->where("key", $key)->first();
        if (!$cfg) {
            $cfg = new Config();
            $cfg->key = $key;
        }
        $cfg->value = $value;
        $cfg->save();

        File::writeForLock(self::CACHE_FILE, function (string $contents) use ($cfg, $key) {
            $configs = Binary::inst()->unpack($contents);
            $configs[$key] = $cfg->value;
            return Binary::inst()->pack($configs);
        });
    }

}