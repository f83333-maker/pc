<?php
declare (strict_types=1);

namespace Kernel\Cache;

use Kernel\Component\Singleton;

class Variable
{
    use Singleton;

    private array $caches = [];

    public function set(string $key, mixed $value, int $expire = 60): void
    {
        $this->caches[$key] = [
            "value" => $value,
            "expire" => time() + $expire
        ];
    }

    public function get(string $key): mixed
    {
        if (!isset($this->caches[$key])) {
            return null;
        }
        $cache = $this->caches[$key];
        if (time() > $cache['expire']) {
            unset($this->caches[$key]);
            return null;
        }
        return $cache['value'];
    }

    public function getOrNotCallback(string $key, callable $callable, int $expire = 60): mixed
    {
        $value = $this->get($key);
        if ($value) {
            return $value;
        }
        $result = $callable();
        $this->set($key, $result, $expire);
        return $result;
    }

    public function has(string $key): bool
    {
        if ($this->get($key)) {
            return true;
        }
        return false;
    }
}