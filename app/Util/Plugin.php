<?php
declare(strict_types=1);

namespace App\Util;

use Kernel\Exception\JSONException;

class Plugin
{

    public static function setCache(string $pluginName, string $db, string $key, mixed $value, int $expire = 0, bool $cli = false): void
    {
        $path = BASE_PATH . '/app/Plugin/' . $pluginName . '/Db/';
        $db = $path . $db . ".php";
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $data = [];
        if (file_exists($db)) {
            $data = (array)File::codeLoad($db, $cli);
        }

        $data[$key] = serialize([
            'data' => $value,
            'expire' => $expire == 0 ? 0 : time() + $expire
        ]);
        setConfig($data, $db);
    }

    public static function getCache(string $pluginName, string $db, string $key, bool $cli = false): mixed
    {
        $path = BASE_PATH . '/app/Plugin/' . $pluginName . '/Db/' . $db . ".php";

        if (!file_exists($path)) {
            return null;
        }

        $data = (array)File::codeLoad($path, $cli);

        if (!isset($data[$key])) {
            return null;
        }

        $unserialize = unserialize($data[$key]);
        if ($unserialize['expire'] != 0 && $unserialize['expire'] < time()) {
            self::delCache($pluginName, $db, $key);
            return null;
        }

        return $unserialize['data'];
    }

    public static function getCaches(string $pluginName, string $db, bool $cli = false): array
    {
        $path = BASE_PATH . '/app/Plugin/' . $pluginName . '/Db/' . $db . ".php";
        if (!file_exists($path)) {
            return [];
        }
        $data = (array)File::codeLoad($path, $cli);
        $success = [];
        foreach ($data as $key => $val) {
            $unserialize = unserialize($val);
            if ($unserialize['expire'] != 0 && (int)$unserialize['expire'] < time()) {
                self::delCache($pluginName, $db, $key);
                continue;
            }
            $success[$key] = $unserialize['data'];
        }
        return $success;
    }

    public static function delCache(string $pluginName, string $db, string $key, bool $cli = false): void
    {
        $path = BASE_PATH . '/app/Plugin/' . $pluginName . '/Db/' . $db . ".php";
        if (!file_exists($path)) {
            return;
        }
        $data = (array)File::codeLoad($path, $cli);
        unset($data[$key]);

        if (count($data) == 0) {
            unlink($path);
            return;
        }

        setConfig($data, $path, true);
    }

    public static function clearCache(string $pluginName, string $db)
    {
        $path = BASE_PATH . '/app/Plugin/' . $pluginName . '/Db/' . $db . ".php";
        if (!file_exists($path)) {
            return;
        }
        unlink($path);
    }

    public static function getConfig(string $pluginName, bool $cache = true): array
    {
        $path = BASE_PATH . '/app/Plugin/' . $pluginName . '/Config/Config.php';
        if (!file_exists($path)) {
            return [];
        }

        if (!$cache) {
            Opcache::invalidate($path);
            return (array)require($path);
        }

        return (array)File::codeLoad($path);
    }

    public static function setConfig(string $pluginName, string $key, string $value, bool $cache = true): void
    {
        unlink(BASE_PATH . "/runtime/plugin/plugin.cache");
        $config = self::getConfig($pluginName, false);
        $config[$key] = urldecode((string)$value);
        setConfig($config, BASE_PATH . '/app/Plugin/' . $pluginName . '/Config/Config.php');
    }

    public static function getPluginLog(string $pluginName): string
    {
        $path = BASE_PATH . '/app/Plugin/' . $pluginName . '/runtime.log';
        return (string)file_get_contents($path);
    }

    public static function ClearPluginLog(string $pluginName): bool
    {
        $path = BASE_PATH . '/app/Plugin/' . $pluginName . '/runtime.log';
        return unlink($path);
    }

    public static function log(string $pluginName, string $message): void
    {
        $path = BASE_PATH . '/app/Plugin/' . $pluginName . '/runtime.log';
        file_put_contents($path, "[" . Date::current() . "]:" . $message . PHP_EOL, FILE_APPEND);
    }
}