<?php
declare(strict_types=1);

namespace App\Service;

use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Bind\Sms::class)]
interface Sms
{
    const CAPTCHA_REGISTER = 0x1;
    const CAPTCHA_FORGET = 0x2;
    const CAPTCHA_BIND_NEW = 0x3;

    public function send(array $smsConfig, string $phone, string $templateCode, array $var = []): void;

    
    public function sendCaptcha(string $phone, int $type): void;

    public function checkCaptcha(string $phone, int $type, int $code): bool;

    
    public function destroyCaptcha(string $phone, int $type): void;
}