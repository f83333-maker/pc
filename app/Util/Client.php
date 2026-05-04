<?php
declare(strict_types=1);

namespace App\Util;

use JetBrains\PhpStorm\NoReturn;
use Kernel\Util\View;

class Client
{
    
    private static ?int $mode = null;

    public static function setClientMode(int $mode): void
    {
        file_put_contents(BASE_PATH . "/runtime/mode", (string)$mode);
    }

    public static function haveMode(): bool
    {
        return file_exists(BASE_PATH . "/runtime/mode");
    }

    public static function getClientMode(): int
    {
        if (self::$mode !== null) {
            return self::$mode;
        }

        $path = BASE_PATH . "/runtime/mode";
        if (!file_exists($path)) {
            return 0;
        }
        $mode = (int)file_get_contents($path);
        if ($mode < 0 || $mode > 8) {
            return 0;
        }

        self::$mode = $mode;
        return $mode;
    }

    public static function getIp(int $type): ?string
    {

        $headers = [
            'REMOTE_ADDR',
            'HTTP_X_REAL_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'HTTP_CF_CONNECTING_IP'
        ];

        if (!isset($headers[$type])) {
            return null;
        }

        $header = $headers[$type];
        if (isset($_SERVER[$header])) {
            
            $ips = explode(',', $_SERVER[$header]);
            if (count($ips) > 0) {
                return trim($ips[0]);
            }
        }

        return null;
    }

    public static function getAddress(): string
    {
        return (string)self::getIp(self::getClientMode());
    }

    public static function getUserAgent(): string
    {
        return (string)$_SERVER['HTTP_USER_AGENT'];
    }

    public static function getUrl(): string
    {
        if (strtolower((string)$_SERVER["HTTPS"]) == "on") {
            $_SERVER['REQUEST_SCHEME'] = "https";
        } elseif (!isset($_SERVER['REQUEST_SCHEME'])) {
            $_SERVER['REQUEST_SCHEME'] = "http";
        }
        return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
    }

    public static function getDomain(): string
    {
        $host = explode(":", (string)$_SERVER['HTTP_HOST']);
        return (string)$host[0];
    }

    public static function redirect(string $url, string $message, int $time = 2): void
    {
        if ($time == 0) {
            header('location:' . $url);
        } else {
            header("refresh:{$time},url={$url}");
            echo View::render("404.html", ["msg" => $message]);
        }
        exit;
    }

    public static function isMobile(): bool
    {
        if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        if (isset($_SERVER['HTTP_VIA'])) {
            return (bool)stristr($_SERVER['HTTP_VIA'], "wap");
        }
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array('nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile', 'MicroMessenger');
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        if (isset ($_SERVER['HTTP_ACCEPT'])) {
            if ((str_contains($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml')) && (!str_contains($_SERVER['HTTP_ACCEPT'], 'text/html') || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }
        return false;
    }

    public static function isWeChat(): bool
    {
        return preg_match('/MicroMessenger/i', $_SERVER['HTTP_USER_AGENT']) === 1;
    }

    public static function getDeviceTypeByUa(?string $userAgent = null): int
    {
        $ua = strtolower($userAgent ?? $_SERVER['HTTP_USER_AGENT'] ?? '');
        return match (true) {
            str_contains($ua, 'ipad') => 3,
            str_contains($ua, 'macintosh') && str_contains($ua, 'mobile') => 3,
            str_contains($ua, 'iphone'),
            str_contains($ua, 'ipod') => 2,
            str_contains($ua, 'android') => 1,
            default => 0,
        };
    }
}