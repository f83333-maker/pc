<?php
declare (strict_types=1);

namespace Kernel\Component;

trait Singleton
{
    
    private static mixed $instance;

    public static function instance(...$args): static
    {
        if (!isset(static::$instance)) {
            static::$instance = new static(...$args);
        }
        return static::$instance;
    }

    public static function inst(...$args): static
    {
        return self::instance(...$args);
    }
}