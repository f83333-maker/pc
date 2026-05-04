<?php
declare (strict_types=1);

namespace App\Service\Store;

use App\Entity\Store\Login;
use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Store\Bind\Auth::class)]
interface Auth
{

    public function captcha(string $type): string;

    
    public function login(string $username, string $password, string $captcha): Login;

    
    public function register(string $username, string $password, string $phone, string $code, string $captcha): Login;

    
    public function reset(string $phone, string $password, string $code, string $captcha): Login;

    public function sendSms(string $type, string $phone, string $captcha): void;
}