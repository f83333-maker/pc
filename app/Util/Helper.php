<?php
declare(strict_types=1);

namespace App\Util;

use Kernel\Exception\JSONException;

class Helper
{
    
    const TYPE_GENERAL = 0;

    const TYPE_PAY = 1;

    const TYPE_THEME = 2;

    public static function themeUrl(string $path, bool $debug = false): string
    {
        $mobile = \App\Model\Config::get("user_mobile_theme");
        $pc = \App\Model\Config::get("user_theme");
        $theme = Client::isMobile() ? $mobile : $pc;
        if ($theme == "0") {
            $theme = $pc;
        }
        return "/app/View/User/Theme/" . $theme . "/{$path}?v=" . Theme::getConfig($theme)["info"]["VERSION"] . (!$debug ? "" : "&debug=" . Str::generateRandStr(16));
    }

    public static function isInstall(string $key, int $type): bool|array
    {

        $path = match ($type) {
            self::TYPE_GENERAL => BASE_PATH . "/app/Plugin/{$key}",
            self::TYPE_PAY => BASE_PATH . "/app/Pay/{$key}",
            self::TYPE_THEME => BASE_PATH . "/app/View/User/Theme/{$key}",
        };

        if (!is_dir($path)) {
            return false;
        }

        switch ($type) {
            case self::TYPE_GENERAL:
                if (!file_exists($path . "/Config/Info.php")) {
                    return false;
                }
                $config = require($path . "/Config/Info.php");
                if (!is_array($config)) {
                    return false;
                }
                if (!array_key_exists(\App\Consts\Plugin::VERSION, $config)) {
                    return false;
                }
                return $config;
                break;
            case self::TYPE_PAY:
                if (!file_exists($path . "/Config/Info.php")) {
                    return false;
                }
                $config = require($path . "/Config/Info.php");
                if (!is_array($config)) {
                    return false;
                }
                if (!array_key_exists("version", $config)) {
                    return false;
                }
                return $config;
                break;
            case self::TYPE_THEME:
                if (!file_exists($path . "/Config.php")) {
                    return false;
                }
                $namespace = "App\\View\\User\\Theme\\{$key}\\Config";
                if (!interface_exists($namespace)) {
                    return false;
                }
                return $namespace::INFO;
                break;
        }

        return false;
    }
}