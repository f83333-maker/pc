<?php
declare(strict_types=1);

namespace App\Pay\SuperPay\Impl;

use Kernel\Exception\JSONException;

class Signature implements \App\Pay\Signature
{
    
    public static function safetyEquals(mixed $str, string $local): bool
    {
        if (!is_string($str) || $str === '') {
            return false;
        }

        return hash_equals($local, $str);
    }

    public static function md5(array $params, string $key): string
    {
        unset($params['sign'], $params['sign_type']);
        $params = array_filter($params, function ($v) {
            return $v !== '' && $v !== null;
        });
        ksort($params);
        $signStr = urldecode(http_build_query($params)) . '&key=' . $key;
        return strtoupper(md5($signStr));
    }

    
    public static function rsa(array $params, string $privateKey): string
    {
        unset($params['sign'], $params['sign_type']);
        $params = array_filter($params, function ($v) {
            return $v !== '' && $v !== null;
        });
        ksort($params);
        $signStr = urldecode(http_build_query($params));

        $private_key = "-----BEGIN PRIVATE KEY-----\n" .
            wordwrap($privateKey, 64, "\n", true) .
            "\n-----END PRIVATE KEY-----";
        $privateKey = openssl_get_privatekey($private_key);

        if (!$privateKey) {
            throw new JSONException('签名失败，商户私钥错误');
        }

        openssl_sign($signStr, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        return base64_encode($signature);
    }

    
    public static function rsaVerify(array $params, string $publicKey): bool
    {
        $sign = $params['sign'];
        unset($params['sign'], $params['sign_type']);
        $params = array_filter($params, function ($v) {
            return $v !== '' && $v !== null;
        });
        ksort($params);
        $signStr = urldecode(http_build_query($params));

        $key = "-----BEGIN PUBLIC KEY-----\n" .
            wordwrap($publicKey, 64, "\n", true) .
            "\n-----END PUBLIC KEY-----";
        $publicKey = openssl_get_publickey($key);
        if (!$publicKey) {
            return false;
        }
        
        return openssl_verify($signStr, base64_decode($sign), $publicKey, OPENSSL_ALGO_SHA256) === 1;
    }

    
    public function verification(array $data, array $config): bool
    {
        if ($config['version'] == 1) {
            return self::rsaVerify($data, $config['platform_public_key']);
        } elseif ($config['version'] == 0) {
            return self::safetyEquals((string)$data['sign'], self::md5($data, $config['key']));
        }
        return false;
    }
}