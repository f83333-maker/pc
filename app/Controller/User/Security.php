<?php
declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\Base\View\User;
use App\Interceptor\UserSession;
use App\Interceptor\Waf;
use Kernel\Annotation\Interceptor;

#[Interceptor([Waf::class, UserSession::class])]
class Security extends User
{

    public function personal(): string
    {
        return $this->theme("个人资料", "PERSONAL", "User/Personal.html");
    }

    public function email(): string
    {
        return $this->theme("邮箱设置", "EMAIL", "User/Email.html");
    }

    public function phone(): string
    {
        return $this->theme("手机设置", "PHONE", "User/Phone.html");
    }

    public function password(): string
    {
        return $this->theme("密码设置", "PASSWORD", "User/Password.html");
    }
}