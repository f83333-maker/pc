<?php
declare(strict_types=1);

namespace App\Controller\Admin\Api;

use App\Model\Business;
use App\Model\UserRecharge;
use App\Util\Date;
use Kernel\Annotation\Interceptor;
use Kernel\Util\Decimal;

#[Interceptor(\App\Interceptor\ManageSession::class, Interceptor::TYPE_API)]
class Dashboard extends \App\Controller\Base\API\Manage
{

    public function data(int $type): array
    {
        $data = [];

        if ($type == 0) {
            $time = [Date::calcDay(), Date::calcDay(1)];
        } elseif ($type == 1) {
            $time = [Date::calcDay(-1), Date::calcDay()];
        } elseif ($type == 2) {
            $time = [Date::weekDay(1, Date::TYPE_START), Date::weekDay(7, Date::TYPE_END)];
        } elseif ($type == 3) {
            $time = [date("Y-m-01 00:00:00"), Date::calcDay()];
        }

        if ($type == 4) {
            $order = \App\Model\Order::query()->where("status", 1);
            $business = Business::query();
            $cash = \App\Model\Cash::query();
            $user = \App\Model\User::query();
            $recharge = UserRecharge::query();

            $data['user_register_num'] = (clone $user)->count();

        } else {

            $order = \App\Model\Order::query()->whereBetween('create_time', $time)->where("status", 1);
            $business = Business::query()->whereBetween("create_time", $time);
            $cash = \App\Model\Cash::query()->whereBetween("create_time", $time);
            $user = \App\Model\User::query();
            $recharge = UserRecharge::query()->whereBetween("create_time", $time);

            $data['user_register_num'] = (clone $user)->whereBetween("create_time", $time)->count();

        }

        $data['turnover'] = sprintf("%.2f", (clone $order)->sum("amount"));

        $data['order_num'] = (clone $order)->count();

        $data['online_amout'] = sprintf("%.2f", (clone $order)->where("pay_id", "!=", 1)->sum("amount"));

        $data['divide_amount'] = sprintf("%.2f", (clone $order)->sum("divide_amount"));

        $data['rebate'] = sprintf("%.2f", (clone $order)->sum("rebate"));

        $data['cost'] = sprintf("%.2f", (clone $order)->sum("cost"));

        $data['profit'] = (new Decimal($data['turnover']))->sub((clone $order)->sum("rent"))->sub($data['divide_amount'])->sub($data['rebate'])->add($data['cost'])->getAmount();

        $data['business'] = $business->count();

        $data['cash_status_0'] = (clone $cash)->where("status", 0)->count();

        $data['cash_money_status_1'] = (clone $cash)->where("status", 1)->sum("amount");

        $data['recharge_amount'] = (clone $recharge)->where("status", 1)->sum("amount");

        return $this->json(200, 'success', $data);
    }

    public function weekStatistics(): array
    {
        $w = date('w');
        $w = $w == 0 ? 7 : $w;

        $week = [
            1 => "星期一",
            2 => "星期二",
            3 => "星期三",
            4 => "星期四",
            5 => "星期五",
            6 => "星期六",
            7 => "星期七"
        ];

        $weeks = [];

        $series = [
            "profit" => [],
            "trade" => [],
            "cash" => [],
            "recharge" => []
        ];

        for ($i = 1; $i <= $w; $i++) {
            $weeks[] = $week[$i];

            $amount = \App\Model\Order::query()->whereBetween("create_time", [Date::weekDay($i, Date::TYPE_START), Date::weekDay($i, Date::TYPE_END)])->where("status", 1)->sum("amount");
            $series["trade"][] = sprintf("%.2f", $amount);

            $divideAmount = \App\Model\Order::query()->whereBetween("create_time", [Date::weekDay($i, Date::TYPE_START), Date::weekDay($i, Date::TYPE_END)])->where("status", 1)->sum("divide_amount");;
            $rebate = \App\Model\Order::query()->whereBetween("create_time", [Date::weekDay($i, Date::TYPE_START), Date::weekDay($i, Date::TYPE_END)])->where("status", 1)->sum("rebate");;
            $cost = \App\Model\Order::query()->whereBetween("create_time", [Date::weekDay($i, Date::TYPE_START), Date::weekDay($i, Date::TYPE_END)])->where("status", 1)->sum("cost");
            $rent = \App\Model\Order::query()->whereBetween("create_time", [Date::weekDay($i, Date::TYPE_START), Date::weekDay($i, Date::TYPE_END)])->where("status", 1)->sum("rent");

            $series['profit'][] = (new Decimal($amount))->sub($rent)->sub($divideAmount)->sub($rebate)->add($cost)->getAmount();

            $cash = \App\Model\Cash::query()->whereBetween("create_time", [Date::weekDay($i, Date::TYPE_START), Date::weekDay($i, Date::TYPE_END)])->where("status", 1)->sum("amount");
            $series["cash"][] = sprintf("%.2f", $cash);

            $recharge = \App\Model\UserRecharge::query()->whereBetween("create_time", [Date::weekDay($i, Date::TYPE_START), Date::weekDay($i, Date::TYPE_END)])->where("status", 1)->sum("amount");;
            $series["recharge"][] = sprintf("%.2f", $recharge);
        }

        return $this->json(200, "success", [
            "series" => $series,
            "week" => $weeks
        ]);
    }
}