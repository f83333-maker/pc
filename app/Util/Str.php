<?php
declare(strict_types=1);

namespace App\Util;

class Str
{

    public static function generatePassword(string $pass, string $salt): string
    {
        return sha1(md5(md5($pass) . md5($salt)));
    }

    public static function generateRandStr(int $length = 32): string
    {
        mt_srand();
        $md5 = md5(uniqid(md5((string)time())) . mt_rand(10000, 9999999));
        return substr($md5, 0, $length);
    }

    public static function isInvalidSign(mixed $sign): bool
    {
        if (!is_string($sign)) {
            return true;
        }

        $sign = trim($sign);

        return $sign === '';
    }

    
    public static function generateSignature(array $data, $appKey): string
    {
        unset($data['sign']);
        ksort($data);
        foreach ($data as $key => $val) {
            if ($val === '') {
                unset($data[$key]);
            }
        }
        return md5(urldecode(http_build_query($data) . "&key=" . (string)$appKey));
    }

    public static function generateTradeNo()
    {
        return mt_rand(100, 999) . date("ymdHis", time()) . mt_rand(100, 999);
    }

    public static function generateRandAmount(float $amount, int $min, int $max): float
    {
        mt_srand();
        return $amount + (mt_rand($min, $max) / 100);
    }

    
    public static function generateContact(int $type): string|int
    {
        return match ($type) {
            0 => self::generateRandStr(16),
            1 => "188" . mt_rand(1000, 9999) . mt_rand(1000, 9999),
            2 => self::generateRandStr(10) . "@system.do",
            3 => mt_rand(1000000, 99999999)
        };
    }

    public static function isValid(string $str): bool
    {
        return (bool)preg_match('/^[A-Za-z0-9]+$/', $str);
    }

    
    public static function safetyEquals(mixed $str, string $local): bool
    {
        if (!is_string($str) || $str === '') {
            return false;
        }

        return hash_equals($local, $str);
    }
}