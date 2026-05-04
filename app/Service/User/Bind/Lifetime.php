<?php
declare (strict_types=1);

namespace App\Service\User\Bind;

use App\Model\UserLifetime;
use Kernel\Util\Date;

class Lifetime implements \App\Service\User\Lifetime
{

    public function create(int $userId, string $ip, string $ua): void
    {

        if (UserLifetime::query()->where("user_id", $userId)->exists()) {
            return;
        }

        $userLifetime = new UserLifetime();
        $userLifetime->user_id = $userId;
        $userLifetime->register_time = Date::current();
        $userLifetime->register_ip = $ip;
        $userLifetime->register_ua = $ua;
        $userLifetime->save();
    }

    public function update(int $userId, string $column, int|float|string $value): void
    {
        UserLifetime::query()->where("user_id", $userId)->update([$column => $value]);
    }

    public function increment(int $userId, string $column, string $amount = "1"): void
    {
        UserLifetime::query()->where("user_id", $userId)->increment($column, $amount);
    }

    public function get(int $userId, ?string $column = null): mixed
    {
        $lifetime = UserLifetime::query()->where("user_id", $userId)->first($column ? ["id", $column] : ["*"]);
        return $column ? ($lifetime?->$column ?? null) : $lifetime;
    }
}