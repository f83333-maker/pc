<?php
declare (strict_types=1);

namespace App\Service\User;

use Kernel\Annotation\Bind;
use App\Const\Balance as Bce;

#[Bind(class: \App\Service\User\Bind\Balance::class)]
interface Balance
{

    public function add(int $userId, string|float|int $amount, int $type, bool $isWithdraw, int $status = Bce::STATUS_DIRECT, int $freeze = 0, ?string $tradeNo = null, ?string $remark = null): int;

    public function unfreeze(int $id): void;

    public function rollback(int $id): void;

    public function refund(int $id, bool $deductionWithdraw = false): bool;

    public function deduct(int $userId, string|float|int $amount, int $type, ?string $tradeNo = null, ?string $remark = null, bool $deductionWithdraw = false): void;

    public function transfer(int $payer, int $payee, string $amount): void;
}