<?php
declare(strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\HasOne;
use Kernel\Context\Interface\Request;
use Kernel\Database\Model;
use Kernel\Util\Context;
use Kernel\Util\Date;

class UserLog extends Model
{
    
    protected ?string $table = "user_log";

    public bool $timestamps = false;

    protected array $casts = ['id' => 'integer', 'user_id' => 'integer'];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, "id", "user_id");
    }

    public static function add(string $content): void
    {

        $request = Context::get(Request::class);

        $user = Context::get(User::class);

        $log = new self();
        $log->user_id = $user->id;
        $log->content = $content;
        $log->create_time = Date::current();
        $log->create_ip = $request->clientIp();
        $log->ua = (string)$request->header("UserAgent");
        $log->save();
    }
}