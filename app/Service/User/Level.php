<?php
declare (strict_types=1);

namespace App\Service\User;

use App\Entity\Shop\Trade;
use App\Model\User;
use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\User\Bind\Level::class)]
interface Level
{

    public function getDefaultId(?User $merchant): int;

    public function getList(User $user): array;

    public function trade(User $user, int $levelId, string $clientId, string $userAgent, string $clientIp): Trade;

    public function upgrade(int $userId, int $levelId): bool;
}