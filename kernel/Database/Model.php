<?php
declare (strict_types=1);

namespace Kernel\Database;

use Hyperf\Database\ConnectionInterface;
use Hyperf\Database\Model\Model as Base;
use Kernel\Component\Singleton;

abstract class Model extends Base
{

    public function dispatcher(string $event, ...$args): void
    {
        if (method_exists($this, $event)) {
            call_user_func_array([$this, $event], $args);
        }
    }

    public function getConnection(): ConnectionInterface
    {
        return Connection::instance()->get();
    }

    public function save(array $options = []): bool
    {
        $this->dispatcher("saving");
        $bool = parent::save($options);
        $this->dispatcher("saved");
        if ($this->wasRecentlyCreated) {
            $this->dispatcher("created");
        }
        return $bool;
    }

    public function delete(): ?bool
    {
        $this->dispatcher("deleting", (int)$this->id);
        $result = parent::delete();
        $this->dispatcher("deleted", (int)$this->id);
        return $result;
    }
}