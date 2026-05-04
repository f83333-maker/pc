<?php
declare (strict_types=1);

namespace App\Service\Store;

use App\Entity\Store\Authentication;
use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Store\Bind\Store::class)]
interface Store
{

    public function list(array $post, Authentication $authentication): array;

    public function getGroup(int $gift, Authentication $authentication): array;

    public function install(string $key, string $env, Authentication $authentication): void;

    public function uninstall(string $key, string $env): void;

    public function getPluginType(string $key, Authentication $authentication): int;

    public function purchase(int $type, int $itemId, int $subscription, int $subscriptionId, int $payId, bool $balance, string $syncUrl, int $isGift, string $giftUsername, Authentication $authentication, int $device = 0): array;

    public function recharge(string $amount, int $payId, string $syncUrl, Authentication $authentication, int $device = 0): array;

    public function powers(Authentication $authentication): array;

    public function powerRenewal(int $type, int $itemId, int $subscription, Authentication $authentication): bool;

    public function powerBind(int $type, int $itemId, Authentication $authentication): bool;

    public function openPowerAutoRenewal(int $type, int $itemId, Authentication $authentication): bool;

    public function openSubFree(int $itemId, Authentication $authentication): bool;

    public function getSubPowers(array $users, Authentication $authentication): array;

    public function setSubPower(int $userId, string $expireTime, int $status, Authentication $authentication): bool;

    public function powerDetail(int $itemId, bool $isGroup, Authentication $authentication): array;

    public function getPluginVersions(array $plugins, Authentication $authentication): array;

    public function getPluginVersionList(string $key, Authentication $authentication): array;

    public function pluginVersionUpdate(string $key, string $env, Authentication $authentication): void;
}