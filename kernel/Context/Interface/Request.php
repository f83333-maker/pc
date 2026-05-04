<?php
declare (strict_types=1);

namespace Kernel\Context\Interface;

use Kernel\Waf\Filter;

interface Request
{
    
    public function method(): string;

    public function all(int $flags = Filter::STRING_UNSIGNED): mixed;

    
    public function post(?string $key = null, int $flags = Filter::STRING_UNSIGNED): mixed;

    public function unsafePost(?string $key = null): mixed;

    
    public function xml(?string $key = null, int $flags = Filter::STRING_UNSIGNED): mixed;

    
    public function get(?string $key = null, int $flags = Filter::STRING_UNSIGNED): mixed;

    public function unsafeGet(?string $key = null): mixed;

    
    public function header(?string $key = null): mixed;

    public function cookie(?string $key = null): mixed;

    public function json(?string $key = null, int $flags = Filter::STRING_UNSIGNED): mixed;

    public function unsafeJson(?string $key = null): mixed;

    public function file(?string $key = null): mixed;

    public function uri(): string;

    public function uriSuffix(): string;

    
    public function setProperty(string $property, mixed $value): void;

    public function url(): string;

    
    public function domain(): string;

    public function raw(): string;
}