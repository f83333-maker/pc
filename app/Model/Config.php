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
        });
        if (!is_array($configs)) {
            $configs = [];
        }

        if (isset($configs[$key])) {
            Context::set($cacheKey, $configs[$key]);
            return (string)$configs[$key];
        }
        $cfg = Config::query()->where("key", $key)->whereNull("user_id")->first();
        if (!$cfg) {
            return "";
        }

        File::writeForLock(self::CACHE_FILE, function (string $contents) use ($cfg, $key) {
            $configs = Binary::inst()->unpack($contents);
            if (!is_array($configs)) {
                $configs = [];
            }
            $configs[$key] = $cfg->value;
            return Binary::inst()->pack($configs);
        });

        Context::set($cacheKey, $cfg->value);

        return (string)$cfg->value;
    }

    public static function list(): array
    {
        $cfg = Config::query()->whereNull("user_id")->get();
        $list = [];
        foreach ($cfg as $item) {
            $list[$item->key] = $item->value;
        }
        return $list;
    }

    public static function put(string $key, string|int $value): void
    {
        // Scope system-wide settings to user_id IS NULL so we never accidentally
        // update a user's per-account config row that happens to share the same key.
        $cfg = Config::query()->where("key", $key)->whereNull("user_id")->first();
        if (!$cfg) {
            $cfg = new Config();
            $cfg->key = $key;
            $cfg->user_id = null;
        }
        $cfg->value = $value;
        $saved = $cfg->save();

        if ($saved === false) {
            throw new RuntimeException("Eloquent save() returned false for config key={$key}");
        }

        // Verify the write actually persisted; surface a clear error if not.
        $verify = Config::query()->where("key", $key)->whereNull("user_id")->first();
        if (!$verify || (string)$verify->value !== (string)$value) {
            $actual = $verify ? (string)$verify->value : "(row missing)";
            throw new RuntimeException(
                "Config persistence verification failed for key={$key}: " .
                "expected length=" . strlen((string)$value) . ", actual=" . substr($actual, 0, 80)
            );
        }

        // Invalidate per-request cache for this key so subsequent reads in the same request see the new value.
        Context::set("_DB_CONFIG_" . $key, null);

        File::writeForLock(self::CACHE_FILE, function (string $contents) use ($cfg, $key) {
            $configs = Binary::inst()->unpack($contents);
            // Tolerate a corrupt/empty cache file: previously an unpack() returning false
            // got auto-promoted to a single-key array, wiping every other cached key.
            if (!is_array($configs)) {
                $configs = [];
            }
            $configs[$key] = $cfg->value;
            return Binary::inst()->pack($configs);
        });
    }

}
