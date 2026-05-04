<?php
declare (strict_types=1);

namespace App\Service\User\Bind;

use App\Model\UserBill;
use App\Service\User\Balance;
use Kernel\Annotation\Inject;
use Kernel\Log\Log;

class Bill implements \App\Service\User\Bill
{

    #[Inject]
    private Balance $balance;

    public function unfreeze(string $tradeNo): void
    {
        $bill = UserBill::query()->where("status", 1)->where("trade_no", $tradeNo)->get();
        
        foreach ($bill as $item) {
            try {
                $this->balance->unfreeze($item->id);
            } catch (\Throwable $e) {
                Log::inst()->error("解冻资金时出错：" . $e->getMessage());
            }
        }
    }

    public function rollback(string $tradeNo): void
    {
        $bill = UserBill::query()->where("status", 1)->where("trade_no", $tradeNo)->get();

        foreach ($bill as $item) {
            $this->balance->rollback($item->id);
        }
    }
}