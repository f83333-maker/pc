<?php
declare (strict_types=1);

namespace App\Service\User;

use App\Model\User;
use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\User\Bind\Auth::class)]
interface Auth
{

    public function sendEmail(string $type, array $map): void;

    public function register(array $map, string $clientId, string $ip, string $ua, ?User $merchant = null, ?User $inviter = null): User;

    public function login(array $map, string $ip, string $ua, string $clientId): string;

    public function setLoginSuccess(User $user): string;

    public function reset(array $map): void;
}