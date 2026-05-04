<?php
declare(strict_types=1);

namespace App\Service\Bind;

use App\Util\Http;
use Kernel\Annotation\Inject;
use Kernel\Exception\JSONException;
use Kernel\Util\Session;
use Mrgoon\AliSms\AliSms;

class Sms implements \App\Service\Sms
{

    #[Inject]
    private AliSms $sms;

    private function tencentSms(array $smsConfig, string $phone, string $templateCode, array $var = [])
    {
        $host = "sms.tencentcloudapi.com";
        $param = [
            "Nonce" => 11886,
            "Timestamp" => time(),
            "Region" => "ap-guangzhou",
            "SecretId" => $smsConfig['tencentSecretId'],
            "Version" => "2021-01-11",
            "Action" => "SendSms",
            "SmsSdkAppId" => $smsConfig['tencentSdkAppId'],
            "SignName" => $smsConfig['tencentSignName'],
            "TemplateId" => $templateCode,
            "PhoneNumberSet.0" => "+86" . $phone,
        ];
        foreach ($var as $index => $item) {
            $param["TemplateParamSet." . $index] = $item;
        }
        ksort($param);
        $signStr = "GET" . $host . "/?";
        foreach ($param as $key => $value) {
            $signStr = $signStr . $key . "=" . $value . "&";
        }
        $signStr = substr($signStr, 0, -1);
        $signature = base64_encode(hash_hmac("sha1", $signStr, $smsConfig['tencentSecretKey'], true));
        $param["Signature"] = $signature;
        $paramStr = "";
        foreach ($param as $key => $value) {
            $paramStr = $paramStr . $key . "=" . urlencode((string)$value) . "&";
        }
        $paramStr = substr($paramStr, 0, -1);
        $response = \App\Util\Http::make()->get("https://" . $host . "/?{$paramStr}");
        $json = json_decode((string)$response->getBody()->getContents(), true);
        if ((string)$json['Response']['SendStatusSet'][0]['Code'] != "Ok") {
            throw new JSONException("短信发送失败");
        }
    }

    public function send(array $smsConfig, string $phone, string $templateCode, array $var = []): void
    {
        $platform = (int)$smsConfig['platform'];
        if ($platform == 0) {

            $config = [
                'access_key' => $smsConfig['accessKeyId'],
                'access_secret' => $smsConfig['accessKeySecret'],
                'sign_name' => $smsConfig['signName'],
            ];
            $response = $this->sms->sendSms($phone, $templateCode, $var, $config);
            if ($response->Message != "OK") {
                throw new JSONException($response->Message);
            }

        } elseif ($platform == 1) {
            $this->tencentSms($smsConfig, $phone, $templateCode, $var);
        } elseif ($platform == 2) {
            $response = Http::make()->get("https://api.smsbao.com/sms?u={$smsConfig['dxbao_username']}&p=" . md5((string)$smsConfig['dxbao_password']) . "&m={$phone}&c={$templateCode}");
            $contents = $response->getBody()->getContents();
            if ($contents != "0") {
                throw new JSONException("短信发送失败");
            }
        }
    }

    public function sendCaptcha(string $phone, int $type): void
    {
        $capthca = mt_rand(100000, 999999);
        $key = match ($type) {
            Sms::CAPTCHA_REGISTER => sprintf(\App\Consts\Sms::CAPTCHA_REGISTER, $phone),
            Sms::CAPTCHA_FORGET => sprintf(\App\Consts\Sms::CAPTCHA_FORGET, $phone),
            Sms::CAPTCHA_BIND_NEW => sprintf(\App\Consts\Sms::CAPTCHA_BIND_NEW, $phone),
        };

        if (Session::has($key)) {
            if (Session::get($key)['time'] + 60 > time()) {
                throw new JSONException("验证码发送频繁，请稍后再试");
            }
        }

        $smsConfig = (array)json_decode(\App\Model\Config::get("sms_config"), true);
        $platform = (int)$smsConfig['platform'];

        $templateCode = match ($platform) {
            0 => $smsConfig['templateCode'], 
            1 => $smsConfig['tencentTemplateId'], 
            2 => str_replace("{code}", (string)$capthca, $smsConfig['dxbao_template'])
        };

        $var = match ($platform) {
            0 => ['code' => $capthca], 
            1 => [(string)$capthca], 
            2 => [], 
        };

        $this->send($smsConfig, $phone, $templateCode, $var);

        Session::set($key, ["time" => time(), "code" => $capthca]);
    }

    public function checkCaptcha(string $phone, int $type, int $code): bool
    {
        $key = match ($type) {
            Sms::CAPTCHA_REGISTER => sprintf(\App\Consts\Sms::CAPTCHA_REGISTER, $phone),
            Sms::CAPTCHA_FORGET => sprintf(\App\Consts\Sms::CAPTCHA_FORGET, $phone),
            Sms::CAPTCHA_BIND_NEW => sprintf(\App\Consts\Sms::CAPTCHA_BIND_NEW, $phone),
        };

        if (!Session::has($key)) {
            return false;
        }

        if (Session::get($key)['code'] != $code) {
            return false;
        }

        if (Session::get($key)['time'] + 300 < time()) {
            return false;
        }

        return true;
    }

    public function destroyCaptcha(string $phone, int $type): void
    {
        $key = match ($type) {
            Sms::CAPTCHA_REGISTER => sprintf(\App\Consts\Sms::CAPTCHA_REGISTER, $phone),
            Sms::CAPTCHA_FORGET => sprintf(\App\Consts\Sms::CAPTCHA_FORGET, $phone),
            Sms::CAPTCHA_BIND_NEW => sprintf(\App\Consts\Sms::CAPTCHA_BIND_NEW, $phone),
        };
        Session::remove($key);
    }

}