<?php
declare (strict_types=1);

namespace App\Service\Common\Bind;

use App\Model\Config;
use Kernel\Annotation\Inject;
use Kernel\Exception\JSONException;
use Kernel\Session\Session;

class SmsCaptcha implements \App\Service\Common\SmsCaptcha
{

    #[Inject]
    private \App\Service\Common\Sms $sms;

    #[Inject]
    private Session $session;

    public function sendCaptcha(string $key, string $phone): void
    {
        $key = $key . "_" . $phone;
        $captcha = mt_rand(100000, 999999);
        if ($this->session->has($key)) {
            if ($this->session->get($key)['time'] + 60 > time()) {
                throw new JSONException("验证码发送频繁，请稍后再试");
            }
        }

        $smsConfig = Config::auto("sms");
        $platform = (int)$smsConfig['platform'];

        $templateCode = match ($platform) {
            \App\Const\Sms::PLATFORM_ALI => $smsConfig['ali_template_code'], 
            \App\Const\Sms::PLATFORM_TENCENT => $smsConfig['tencent_template_id'], 
            \App\Const\Sms::PLATFORM_DXB => str_replace("{code}", (string)$captcha, $smsConfig['dxb_template'])
        };

        $var = match ($platform) {
            \App\Const\Sms::PLATFORM_ALI => ['code' => $captcha], 
            \App\Const\Sms::PLATFORM_TENCENT => [(string)$captcha], 
            \App\Const\Sms::PLATFORM_DXB => [], 
        };

        $this->session->set($key, ["time" => time(), "code" => $captcha]);
        
        $this->sms->send($smsConfig, $phone, $templateCode, $var);
    }

    public function checkCaptcha(string $key, string $phone, int $code): bool
    {
        $key = $key . "_" . $phone;
        if (!$this->session->has($key)) {
            return false;
        }

        $data = $this->session->get($key);

        if ($data['code'] != $code) {
            return false;
        }

        if ($data['time'] + 300 < time()) {
            return false;
        }

        return true;
    }

    public function destroyCaptcha(string $key, string $phone): void
    {
        $key = $key . "_" . $phone;
        $this->session->remove($key);
    }
}