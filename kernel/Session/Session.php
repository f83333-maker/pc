<?php
declare (strict_types=1);

namespace Kernel\Session;

interface Session
{
    
    const NAME = "acg_session";

    public function get(?string $key = null): mixed;

    public function set(string $key, mixed $value): void;

    public function has(string $key): bool;

    public function remove(string $key): void;

    public function clear(): void;

    public function id(): string;

    public function gc(): bool;
}