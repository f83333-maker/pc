<?php
declare(strict_types=1);

namespace App\Util;

class Opcache
{

    public static array $invalidate = [];

    
    public static function reset(): void
    {
        if (\Kernel\Util\Context::get(\Kernel\Consts\Base::OPCACHE)) {
            opcache_reset();
        }
    }

    public static function invalidate(string ...$file): void
    {
        if (\Kernel\Util\Context::get(\Kernel\Consts\Base::OPCACHE)) {
            foreach ($file as $f) {
                opcache_invalidate($f, true);
            }
        }
    }
}