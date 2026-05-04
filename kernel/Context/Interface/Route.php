<?php
declare (strict_types=1);

namespace Kernel\Context\Interface;

interface Route
{

    public function route(): string;

    
    public function class(): string;

    
    public function method(): string;

    
    public function action(string $default): ?string;

    
    public function setRoute(string $route): void;

    public function setClass(string $class): void;

    public function setMethod(string $method): void;

    public function setAction(string $action): void;
}