<?php
declare (strict_types=1);

namespace App\Service\User;

use App\Entity\Repertory\RepertoryItem;
use App\Model\User;
use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\User\Bind\Supply::class)]
interface Supply
{

    public function getItem(?User $customer, int $itemId): RepertoryItem;
}