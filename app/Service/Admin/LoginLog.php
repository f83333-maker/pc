<?php
declare (strict_types=1);

namespace App\Service\Admin;

use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Admin\Bind\LoginLog::class)]
interface LoginLog
{

    
    public function create(int $manageId, string $ip, string $ua): void;
}