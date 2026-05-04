<?php
declare (strict_types=1);

namespace App\Service\Admin\Bind;

use App\Model\ManageLoginLog;
use Kernel\Util\Date;

class LoginLog implements \App\Service\Admin\LoginLog
{

    public function create(int $manageId, string $ip, string $ua): void
    {
        $ipCount = ManageLoginLog::query()->where("manage_id", $manageId)->where("ip", $ip)->count();
        $count = ManageLoginLog::query()->where("manage_id", $manageId)->count();
        $log = new ManageLoginLog();
        $log->manage_id = $manageId;
        $log->ip = $ip;
        $log->ua = $ua;
        $log->is_dangerous = (int)($count > 0 && $ipCount == 0);
        $log->create_time = Date::current();
        $log->save();
    }
}