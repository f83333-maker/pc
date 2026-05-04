<?php
declare (strict_types=1);

namespace App\Service\User;

use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\User\Bind\BankCard::class)]
interface BankCard
{

    public function add(int $userId, int $bankId, string $cardNo, ?string $cardImage = null): void;

    public function abnormality(int $cardId, int $status = 0): void;

    public function del(int $cardId): void;

    public function list(int $userId): array;
}