<?php
declare(strict_types=1);

namespace App\Controller\User;

use Kernel\Annotation\Get;

class Captcha
{

    public function image(#[Get] string $action): void
    {
        \App\Util\Captcha::generate($action);
    }
}