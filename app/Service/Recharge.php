<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\UserRecharge;
use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Bind\Recharge::class)]
interface Recharge
{

    public function trade(\App\Model\User $user): array;

    public function callback(string $handle, array $map): string;

    
    public function orderSuccess(UserRecharge $recharge): void;

    public function calcAmount(float $amount): float;
}