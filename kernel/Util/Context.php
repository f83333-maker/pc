<?php
declare(strict_types=1);

namespace Kernel\Util;

class Context
{

    private static array $context = [];

    
    public static function set(string $name, $value): void
    {
        self::$context[$name] = $value;
    }

    public static function get(string $name)
    {

        return self::$context[$name] ?? null;
    }

    public static function has(string $name): bool
    {
        return isset(self::$context[$name]);
    }

    public static function del(string $name): void
    {
        unset(self::$context[$name]);
    }
}