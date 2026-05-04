<?php
declare (strict_types=1);

namespace App\Service\User\Bind;

use App\Entity\Pay\MasterPay;
use App\Entity\Shop\Order;
use App\Model\PayGroup;
use App\Model\PayUser;
use App\Model\User;
use App\Model\UserGroup;
use Kernel\Exception\ServiceException;

class Pay implements \App\Service\User\Pay
{

    public function getList(int $equipment, string $business, ?User $user = null, string $amount = "0", array $options = []): array
    {
        if (!in_array($business, \App\Service\User\Pay::BUSINESS)) {
            throw new ServiceException("业务不存在");
        }

        $pay = \App\Model\Pay::query()->where("status", 1)->whereIn("equipment", [0, $equipment]);

        $openUser = false;

        if (in_array($business, ["product", "level"])) {
            $openUser = true;
        }

        if ($user && $openUser) {
            $pay = $pay->where("user_id", $user->id);
            if ($user->balance < $amount) {
                $pay = $pay->where("pid", ">", 0);
            }
        } else {
            $pay = $pay->whereNull("user_id");
        }

        $methods = $pay->orderBy("sort", "asc")->get();

        $items = [];

        $group = $user?->group;

        foreach ($methods as $method) {
            if ($method->pid > 0 && $user && !$this->getMasterPay($method->pid, $user, $group)) {
                continue;
            }
            $scope = is_array($method->scope) ? $method->scope : (array)json_decode((string)$method->scope, true);
            if (!in_array($business, $scope)) {
                continue;
            }
            $items[] = (new \App\Entity\Pay\Pay($method))->toArray();
        }
        return $items;
    }

    public function findPay(?int $id): ?\App\Model\Pay
    {
        if ($id <= 0) {
            return null;
        }

        $pay = \App\Model\Pay::query()->find($id);
        if (!$pay) {
            return null;
        }
        return $pay;
    }

    public function isCustom(?int $id): bool
    {
        $payOwner = $this->findPayOwner($id);
        return $payOwner === \App\Service\User\Pay::OWNER_MERCHANT;
    }

    public function isOfficial(?int $id): bool
    {
        $payOwner = $this->findPayOwner($id);
        return $payOwner === \App\Service\User\Pay::OWNER_OFFICIAL;
    }

    public function findPayOwner(?int $id): ?int
    {
        return $this->resolvePayOwner($this->findPay($id));
    }

    public function hydratePayOrderMerchantFlag(array &$list): void
    {
        if ($list === []) {
            return;
        }

        $payIds = [];
        foreach ($list as $row) {
            if (isset($row['pay_id']) && is_numeric($row['pay_id'])) {
                $payIds[] = (int)$row['pay_id'];
            }
        }
        $payIds = array_values(array_unique($payIds));

        $pays = [];
        if ($payIds !== []) {
            foreach (\App\Model\Pay::query()->whereIn('id', $payIds)->get() as $p) {
                $pays[(int)$p->id] = $p;
            }
        }

        foreach ($list as $index => $row) {
            $pid = isset($row['pay_id']) && is_numeric($row['pay_id']) ? (int)$row['pay_id'] : 0;
            $payOwner = $this->resolvePayOwner($pays[$pid] ?? null);
            $trade = (float)($row['trade_amount'] ?? 0);
            $balance = (float)($row['balance_amount'] ?? 0);
            $list[$index]['is_custom_pay'] = $payOwner === \App\Service\User\Pay::OWNER_MERCHANT && $trade > 0 && $balance == 0.0;
        }
    }

    private function resolvePayOwner(?\App\Model\Pay $pay): ?int
    {
        if (!$pay) {
            return null;
        }

        if ($pay->user_id === null) {
            return \App\Service\User\Pay::OWNER_OFFICIAL;
        }

        if ($pay->user_id > 0) {
            return $pay->pid > 0 ? \App\Service\User\Pay::OWNER_OFFICIAL : \App\Service\User\Pay::OWNER_MERCHANT;
        }
        return null;
    }

    public function getMasterPayList(User $user): array
    {
        $list = \App\Model\Pay::query()->where("status", 1)->whereNull("user_id")->orderBy("sort", "asc")->get()->toArray();
        $pays = [];
        $group = $user?->group;

        foreach ($list as $item) {
            $masterPay = $this->getMasterPay($item['id'], $user, $group);
            if ($masterPay) {
                $pays[] = $masterPay;
            }
        }
        return $pays;
    }

    public function getMasterPay(int $id, User $user, ?UserGroup $group): ?MasterPay
    {

        $pay = \App\Model\Pay::query()->find($id);
        if (!$pay) {
            return null;
        }

        if ($pay->status != 1) {
            return null;
        }

        $scope = is_array($pay->scope) ? $pay->scope : (array)json_decode((string)$pay->scope, true);

        if (empty($scope)) {
            return null;
        }

        if ($group) {

            $payGroup = PayGroup::query()->where("group_id", $group->id)->where("pay_id", $id)->first();
            if ($payGroup && $payGroup->status == 1) {
                return new MasterPay($pay->id, $pay->name, $pay->icon, $payGroup->fee, $scope);
            }
        }

        $payUser = PayUser::query()->where("user_id", $user->id)->where("pay_id", $id)->first();

        if ($payUser && $payUser->status == 1) {
            return new MasterPay($pay->id, $pay->name, $pay->icon, $payUser->fee, $scope);
        }

        if ($pay->substation_status != 1) {
            return null;
        }

        return new MasterPay($pay->id, $pay->name, $pay->icon, $pay->substation_fee, $scope);
    }

}