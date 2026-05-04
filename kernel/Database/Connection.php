<?php
declare (strict_types=1);

namespace Kernel\Database;

use Hyperf\Database\ConnectionInterface;
use Kernel\Component\Singleton;
use Kernel\Container\Di;
use Kernel\Context\App;
use Kernel\Pool\ConnectionPool;
use Swoole\Coroutine;

class Connection
{
    use Singleton;

    private array $connections = [];

    
    public function get(): ConnectionInterface
    {
        if (!App::$cli) {
            if (isset($this->connections[0])) {
                return $this->connections[0];
            }
            return $this->connections[0] = (new \Kernel\Database\MysqlConnection())->createObject();
        }

        $cid = Coroutine::getCid();

        if (isset($this->connections[$cid])) {
            return $this->connections[$cid];
        }

        $di = Di::instance()->get(ConnectionPool::class);
        $this->connections[$cid] = $di->get();

        
        \Swoole\Coroutine\defer(function () use ($cid, $di) {
            $di->put($this->connections[$cid]);
            unset($this->connections[$cid]);
        });
        return $this->connections[$cid];
    }

    public function release(): void
    {
        if (!App::$cli) {
            return;
        }

        $cid = Coroutine::getCid();
        $this->connections[$cid] = null;
    }

    public function set(ConnectionProxy $connection): void
    {
        if (!App::$cli) {
            return;
        }

        $cid = Coroutine::getCid();
        $this->connections[$cid] = $connection;
    }

    
    public function usage(): int
    {
        return count($this->connections);
    }
}