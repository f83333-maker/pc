<?php
declare (strict_types=1);

namespace App\Service\User;

use App\Entity\Shop\Pay;
use App\Model\User;
use Kernel\Annotation\Bind;
use Kernel\Context\Interface\Response;

#[Bind(class: \App\Service\User\Bind\PayOrder::class)]
interface PayOrder
{

    public function getPay(int $payId): \App\Model\Pay;

    public function pay(string $tradeNo, int $method, bool $balance, string $tradeIp, string $httpUrl, ?User $customer = null): Pay;

    public function async(string $tradeNo, string $clientIp): Response;

    public function getSyncUrl(string $tradeNo): string;

    public function getPayOrder(string $tradeNo): \App\Entity\Pay\Order;

    public function findPayOrder(int $orderId): \App\Model\PayOrder;
}