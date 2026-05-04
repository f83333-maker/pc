<?php
declare (strict_types=1);

namespace App\Service\User\Bind;

use App\Model\Manage;
use App\Model\User;
use App\Model\UserLog;
use Kernel\Context\Interface\Request;
use Kernel\Util\Context;
use Kernel\Util\Date;

class Log implements \App\Service\User\Log
{

    public function create(int $userId, string $content): void
    {
        
        $request = Context::get(Request::class);

        $user = Context::get(User::class);

        $log = new UserLog();
        $log->user_id = $user->id;
        $log->content = $content;
        $log->create_time = Date::current();
        $log->create_ip = $request->clientIp();
        $log->create_ua = (string)$request->header("UserAgent");
        $log->save();
    }
}