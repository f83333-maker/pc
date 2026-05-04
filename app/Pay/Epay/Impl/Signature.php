<?php
declare(strict_types=1);

namespace App\Pay\Epay\Impl;

use App\Util\Str;
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

    public static function generateSignature(array $data, string $key): string
    {
        unset($data['sign'], $data['sign_type']);
        ksort($data);
        $sign = '';
        foreach ($data as $k => $v) {
            if (is_array($v) || $v === '') continue;
            $sign .= $k . '=' . $v . '&';
        }
        $sign = trim($sign, '&');
        return md5($sign . $key);
    }

    public static function getStr(array $param): string
    {
        ksort($param);
        $signstr = '';
        foreach ($param as $k => $v) {
            $v = (string)$v;
            if (is_array($v) || $v === '' || $k == 'sign' || $k == 'sign_type') continue;
            $signstr .= '&' . $k . '=' . $v;
        }
        return substr($signstr, 1);
    }

    public static function rsa(array $param, string $key): string
    {
        $data = self::getStr($param);
        $private_key = "-----BEGIN PRIVATE KEY-----\n" .
            wordwrap($key, 64, "\n", true) .
            "\n-----END PRIVATE KEY-----";
        $privateKey = openssl_get_privatekey($private_key);
        if (!$privateKey) {
            throw new JSONException('签名失败，商户私钥错误');
        }
        openssl_sign($data, $sign, $privateKey, OPENSSL_ALGO_SHA256);
        return base64_encode($sign);
    }

    public static function rsaVerify(array $params, string $publicKey): bool
    {
        $key = "-----BEGIN PUBLIC KEY-----\n" .
            wordwrap($publicKey, 64, "\n", true) .
            "\n-----END PUBLIC KEY-----";
        $publicKey = openssl_get_publickey($key);
        if (!$publicKey) {
            return false;
        }

        $result = openssl_verify(self::getStr($params), base64_decode($params['sign']), $publicKey, OPENSSL_ALGO_SHA256);
        return $result === 1;
    }

    public function verification(array $data, array $config): bool
    {
        if ($config['version'] == 1) {
            return self::rsaVerify($data, $config['platform_public_key']);
        } elseif ($config['version'] == 0) {
            $sign = $data['sign'];
            unset($data['sign_type'], $data['sign']);
            return $sign === self::generateSignature($data, $config['key']);
        }
        return false;
    }
}