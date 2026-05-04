<?php
declare (strict_types=1);

namespace App\Controller\User\User;

use App\Controller\User\Base;
use App\Interceptor\User;
use Kernel\Annotation\Interceptor;
use Kernel\Context\Interface\Response;
use Kernel\Plugin\Const\Theme;

#[Interceptor(class: User::class)]
class Inviter extends Base
{
    
    public function index(): Response
    {
        return $this->theme(Theme::USER_INVITER, "User/Inviter.html", "推广返利", ['inviteUrl' => $this->request->url() . "?invite=" . $this->getUser()->id]);
    }
}