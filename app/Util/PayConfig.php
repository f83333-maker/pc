<?php
declare(strict_types=1);

namespace App\Util;

class PayConfig
{

    public static function isValid(string $handle): bool
    {
        return is_file(BASE_PATH . '/app/Pay/' . $handle . '/Impl/Pay.php');
    }

    public static function config(string $handle): ?array
    {
        return require(BASE_PATH . '/app/Pay/' . $handle . '/Config/Config.php');
    }

    public static function info(string $handle): ?array
    {
        $path = BASE_PATH . '/app/Pay/' . $handle . '/Config/Info.php';

        if (!file_exists($path)) {
            return null;
        }

        return require($path);
    }

    
    public static function log(string $handle, string $type, string $message): void
    {
        $path = BASE_PATH . "/app/Pay/{$handle}/runtime.log";
        file_put_contents($path, "[{$type}][" . Date::current() . "]:" . $message . PHP_EOL, FILE_APPEND);
    }
}