<?php
declare(strict_types=1);

namespace Kernel\Database;

use Generator;
use Hyperf\Database\ConnectionInterface;
use Hyperf\Database\Query\Builder;
use Hyperf\Database\Query\Expression;
use Kernel\Component\Singleton;

class Db
{
    use Singleton;

    public function __call($name, $arguments)
    {
        if ($name === 'connection') {
            return $this->__connection(...$arguments);
        }
        return $this->__connection()->{$name}(...$arguments);
    }

    public static function __callStatic($name, $arguments)
    {
        $db = Db::instance();
        if ($name === 'connection') {
            return $db->__connection(...$arguments);
        }
        return $db->__connection()->{$name}(...$arguments);
    }

    private function __connection(): ConnectionInterface
    {
        return Connection::instance()->get();
    }

    public static function transaction(callable $callback, string $level = \Kernel\Database\Const\Db::ISOLATION_REPEATABLE_READ, int $attempts = 1): mixed
    {
        $attempt = 0;
        while ($attempt < $attempts) {
            try {
                self::statement("SET SESSION TRANSACTION ISOLATION LEVEL {$level}");
                self::beginTransaction();
                $result = $callback();
                self::commit();
                return $result; 
            } catch (\Throwable $e) {
                self::rollBack(); 
                if (++$attempt < $attempts) {
                    continue; 
                } else {
                    throw $e; 
                }
            }
        }

        throw new \Exception("mysql transaction encountered an unknown error", 10881);
    }

}
