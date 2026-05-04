<?php
declare (strict_types=1);

namespace App\Service\Common;

use App\Entity\Repertory\Deliver;
use App\Entity\Repertory\Trade;
use App\Model\RepertoryItemSku;
use App\Model\User;
use Kernel\Annotation\Bind;
use Kernel\Plugin\Handle\Ship;

#[Bind(class: \App\Service\Common\Bind\RepertoryOrder::class)]
interface RepertoryOrder
{

    public function trade(Trade $trade, string $tradeIp, bool $direct = false): Deliver;

    public function getAmount(?User $customer, RepertoryItemSku $repertoryItemSku, int $quantity = 1): string;

    public function getOrderShip(int|\App\Model\RepertoryOrder $order): ?Ship;

    public function hydrateOrderListRenderFlags(array &$list): void;
}