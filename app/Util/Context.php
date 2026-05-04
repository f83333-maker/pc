<?php
declare(strict_types=1);

namespace App\Util;

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
}