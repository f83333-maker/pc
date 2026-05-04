<?php
declare (strict_types=1);

namespace App\Controller\Admin\Pay;

use App\Controller\Admin\Base;
use App\Interceptor\Admin;
use Kernel\Annotation\Interceptor;
use Kernel\Context\Interface\Response;

#[Interceptor(class: Admin::class)]
class Pay extends Base
{
    
    public function index(): Response
    {
        return $this->render("Pay/Pay.html", "支付接口");
    }

    public function order(): Response
    {
        return $this->render("Pay/Order.html", "支付订单");
    }
}