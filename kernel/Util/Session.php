<?php
declare(strict_types=1);

namespace Kernel\Util;

class Session
{
    
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            if (headers_sent()) {
                return;
            }
            session_start();
        }
    }

    public static function end(): void
    {
        session_write_close();
    }

    
    public static function get(?string $key = null): mixed
    {
        self::start();
        $result = $_SESSION[$key] ?? null;
        self::end();
        return $result;
    }

    public static function set(string $key, mixed $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
        self::end();
    }

    public static function has(string $key): bool
    {
        self::start();
        $result = isset($_SESSION[$key]);
        self::end();
        return $result;
    }

    public static function remove(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
        self::end();
    }

    public static function clear(): void
    {
        self::start();
        $_SESSION = [];
        session_destroy();
    }
}