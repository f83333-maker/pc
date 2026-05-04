<?php
declare (strict_types=1);

namespace App\Service\User;

use App\Entity\Shop\Trade;
use App\Model\User;
use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\User\Bind\OpenMerchant::class)]
interface OpenMerchant
{

    public function trade(User $user, int $groupId, string $clientId, string $userAgent, string $clientIp): Trade;

    
    public function become(int $userId, int $groupId, bool $isDividend  = false, ?string $tradeNo = null): bool;
}