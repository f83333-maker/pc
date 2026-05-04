<?php
declare (strict_types=1);

namespace App\Service\User;

use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\User\Bind\Lifetime::class)]
interface Lifetime
{

    public function create(int $userId, string $ip, string $ua): void;

    
    public function update(int $userId, string $column, int|float|string $value): void;

    
    public function get(int $userId, ?string $column = null): mixed;

    
    public function increment(int $userId, string $column, string $amount = "1"): void;
}