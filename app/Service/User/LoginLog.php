<?php
declare (strict_types=1);

namespace App\Service\User;

use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\User\Bind\LoginLog::class)]
interface LoginLog
{

    
    public function create(int $userId, string $ip, string $ua): void;

    
    public function isSame(int $userId, int $targetId): bool;
}