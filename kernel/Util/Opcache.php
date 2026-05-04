<?php
declare(strict_types=1);

namespace Kernel\Util;

use Kernel\Context\App;

class Opcache
{

    public static array $invalidate = [];

    public static function reset(): void
    {
        if (App::$opcache) {
            opcache_reset();
        }
    }

    public static function invalidate(string ...$file): void
    {
        if (App::$opcache) {
            foreach ($file as $f) {
                opcache_invalidate($f, true);
            }
        }
    }
}