<?php
declare(strict_types=1);

namespace App\Pay\YsmWxPay\Impl;

use Kernel\Exception\JSONException;

class Signature implements \App\Pay\Signature
{

    public static function HashSign(array $data, $secret)
    {
        if (isset($data['sign'])) {
            unset($data['sign']);
        }
        ksort($data);
        reset($data);
        $str = '';
        foreach ($data as $key => $row) {
            if ($key == 'hash' || is_null($row) || $row === '') {
                continue;
            }
            if ($str) {
                $str .= '&';
            }
            $str .= "$key=$row";
        }
        return hash('sha256', $str . $secret, false);

    }

    
    public static function safetyEquals(mixed $str, string $local): bool
    {
        if (!is_string($str) || $str === '') {
            return false;
        }

        return hash_equals($local, $str);
    }

    public function verification(array $data, array $config): bool
    {
        $appid = $config['appid'];
        $secret = $config['secret'];
        if (!isset($data['appid']) || $data['appid'] != $appid) {
            return false;
        }
        $sign = self::HashSign($data, $secret);
        if (!self::safetyEquals($data['sign'], $sign)) {
            return false;
        }
        return true;
    }

    public static function HttpPost($url, $data)
    {
        $header = array(
            'Content-Type:' . 'application/json; charset=UTF-8',
            'Accept:application/json',
            'User-Agent:*/*',
            'Authorization: WECHATPAY2-SHA256-RSA2048 '
        );
        $curl = curl_init(); 
        curl_setopt($curl, CURLOPT_URL, $url); 
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); 
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); 
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); 
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); 
        curl_setopt($curl, CURLOPT_POST, 1); 
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); 
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); 
        curl_setopt($curl, CURLOPT_HEADER, 0); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($curl); 
        if (curl_errno($curl)) {
            echo 'Errno' . curl_error($curl);
        }
        curl_close($curl); 
        return json_decode($result, true);
    }

    public static function isMobile(): bool
    {
        
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        
        if (isset ($_SERVER['HTTP_VIA'])) {
            
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        
        if (isset ($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array(
                'nokia'
            , 'sony'
            , 'ericsson'
            , 'mot'
            , 'samsung'
            , 'htc'
            , 'sgh'
            , 'lg'
            , 'sharp'
            , 'sie-'
            , 'philips'
            , 'panasonic'
            , 'alcatel'
            , 'lenovo'
            , 'iphone'
            , 'ipod'
            , 'blackberry'
            , 'meizu'
            , 'android'
            , 'netfront'
            , 'symbian'
            , 'ucweb'
            , 'windowsce'
            , 'palm'
            , 'operamini'
            , 'operamobi'
            , 'openwave'
            , 'nexusone'
            , 'cldc'
            , 'midp'
            , 'wap'
            , 'mobile'
            );
            
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        
        if (isset ($_SERVER['HTTP_ACCEPT'])) {

            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }
        return false;
    }
}