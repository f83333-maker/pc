<?php
declare (strict_types=1);

namespace App\Controller\Admin\API\System;

use App\Controller\Admin\Base;
use App\Interceptor\Admin;
use App\Interceptor\PostDecrypt;
use Kernel\Annotation\Interceptor;
use Kernel\Context\Interface\Response;
use Kernel\Exception\RuntimeException;
use Kernel\Util\Call;

#[Interceptor(class: [PostDecrypt::class, Admin::class], type: Interceptor::API)]
class App extends Base
{

    public function restart(): Response
    {
        Call::defer(fn() => \Kernel\Service\App::inst()->restart());
        return $this->json();
    }

    
    public function state(): Response
    {
        return $this->json();
    }
}