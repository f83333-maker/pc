<?php
declare (strict_types=1);

namespace Kernel\Context;

class Route implements \Kernel\Context\Interface\Route
{
    private string $route;
    private string $class;
    private string $method;
    private string $action;

    public function route(): string
    {
        return $this->route;
    }

    public function class(): string
    {
        return $this->class;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function action(string $default = "*"): string
    {
        if ($this->action == "*") {
            return $default;
        }

        return $this->action;
    }

    public function setRoute(string $route): void
    {
        $this->route = $route;
    }

    public function setClass(string $class): void
    {
        $this->class = $class;
    }

    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    public function setAction(string $action): void
    {
        $this->action = $action;
    }
}