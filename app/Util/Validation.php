<?php
declare(strict_types=1);

namespace App\Util;

class Validation
{
    
    public static function username(string $username, int $length = 6): bool
    {
        if (mb_strlen($username) < $length) {
            return false;
        }
        return true;
    }

    public static function email(string $email): bool
    {
        if (preg_match("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/", $email)) {
            return true;
        }
        return false;
    }

    public static function phone(string $phone): bool
    {
        if (preg_match("/^(1[3-9][0-9])\d{8}$/", $phone)) {
            return true;
        }
        return false;
    }

    public static function password(string $password): bool
    {
        if (mb_strlen($password) < 6) {
            return false;
        }
        return true;
    }

    public static function domain(string $domain): bool
    {
        if (preg_match("/^(?=^.{3,255}$)[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+$/", $domain)) {
            return true;
        }
        return false;
    }

}