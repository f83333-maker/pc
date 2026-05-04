<?php
declare (strict_types=1);

namespace App\Service\User;

use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\User\Bind\Log::class)]
interface Log
{

    public function create(int $userId, string $content): void;
}