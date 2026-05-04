<?php
declare (strict_types=1);

namespace App\Service\User;

use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\User\Bind\Withdraw::class)]
interface Withdraw
{

    public function apply(int $userId, int $cardId, string $amount): void;

    public function processed(int $withdrawId, bool $lockCard, int $status, string $message): void;
}