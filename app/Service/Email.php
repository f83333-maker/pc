<?php
declare(strict_types=1);

namespace App\Service;

use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Bind\Email::class)]
interface Email
{
    const CAPTCHA_REGISTER = 0x1;
    const CAPTCHA_FORGET = 0x2;
    const CAPTCHA_BIND_NEW = 0x3;
    const CAPTCHA_BIND_OLD = 0x4;

    public function send(string $email, string $title, string $content): bool;

    public function sendCaptcha(string $email, int $type): void;

    public function checkCaptcha(string $email, int $type, int $code): bool;

    public function destroyCaptcha(string $email, int $type): void;
}