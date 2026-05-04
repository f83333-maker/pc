<?php
declare (strict_types=1);

namespace App\Service\User;

use App\Entity\Pay\MasterPay;
use App\Entity\Shop\Order;
use App\Model\User;
use App\Model\UserGroup;
use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\User\Bind\Pay::class)]
interface Pay
{
    public const OWNER_OFFICIAL = 0; 
    public const OWNER_MERCHANT = 1; 

    public const BUSINESS = ["product", "recharge", "plugin", "level", "group"];

    public function getList(int $equipment, string $business, ?User $user = null, string $amount = "0", array $options = []): array;

    public function findPay(?int $id): ?\App\Model\Pay;

    public function findPayOwner(?int $id): ?int;

    public function hydratePayOrderMerchantFlag(array &$list): void;

    public function isCustom(?int $id): bool;

    public function isOfficial(?int $id): bool;

    public function getMasterPayList(User $user): array;

    public function getMasterPay(int $id, User $user, ?UserGroup $group): ?MasterPay;
}