<?php
declare(strict_types=1);

namespace Kernel\Util;

use Kernel\Context\App;
use Kernel\Exception\RuntimeException;

class Process
{

    public static function __callStatic(string $name, array $arguments)
    {
        if (!App::$cli) {
            throw new RuntimeException("Process 类仅限CLI模式下调用");
        }
    }

    public static function setName(string $name): void
    {
        swoole_set_process_name($name);
    }

    public static function cpuNum(): int
    {
        return swoole_cpu_num();
    }

}