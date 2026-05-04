<?php
declare(strict_types=1);

namespace App\Model;

use App\Util\Client;
use App\Util\Date;
use Illuminate\Database\Eloquent\Model;

class ManageLog extends Model
{

    protected $table = "manage_log";

    public $timestamps = false;

    protected $casts = ['id' => 'integer', 'risk' => 'integer'];

    public static function log(Manage $manage, string $content): void
    {
        $manageLog = new ManageLog();
        $manageLog->email = $manage->email;
        $manageLog->nickname = $manage->nickname;
        $manageLog->content = $content;
        $manageLog->create_time = Date::current();
        $manageLog->create_ip = Client::getAddress();
        $manageLog->ua = Client::getUserAgent();
        $manageLog->risk = $manage->last_login_ip != $manageLog->create_ip ? 1 : 0;
        $manageLog->save();
    }
}