<?php
declare(strict_types=1);

namespace Kernel\Database;

use Hyperf\Database\ConnectionInterface;
use Hyperf\Database\Model\Builder;

class Schema
{
    public static function __callStatic($name, $arguments)
    {
        $connection = Connection::instance()->get();
        return $connection->getSchemaBuilder()->{$name}(...$arguments);
    }

    public function __call($name, $arguments)
    {
        return self::__callStatic($name, $arguments);
    }

    public function connection(): ConnectionInterface
    {
        return Connection::instance()->get();
    }
}
