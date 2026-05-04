<?php
declare(strict_types=1);

namespace App\Model;

use Kernel\Database\Model;

class Permission extends Model
{

    protected ?string $table = "permission";

    public bool $timestamps = false;

    protected array $casts = ['id' => 'integer', 'pid' => 'integer', 'type' => 'integer', 'rank' => 'integer'];

    private static array $data = [];

    public static function isRegister(string $route): bool
    {
        if (isset(self::$data [$route])) {
            return self::$data[$route];
        }
        $exists = Permission::where("route", $route)->exists();
        self::$data[$route] = $exists;
        return $exists;
    }
}