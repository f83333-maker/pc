<?php
declare(strict_types=1);

namespace App\Util;

class Aes
{
    
    public static function encrypt(mixed $data, string $key, string $iv): string
    {
        return base64_encode(openssl_encrypt(serialize($data), 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $iv));
    }

    public static function decrypt(string $data, string $key, string $iv): mixed
    {
        return unserialize((string)openssl_decrypt(base64_decode($data), 'aes-128-cbc', $key, 1, $iv));
    }
}