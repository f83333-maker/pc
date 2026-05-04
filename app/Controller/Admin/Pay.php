<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Base\View\Manage;
use App\Interceptor\ManageSession;
use Kernel\Annotation\Interceptor;

#[Interceptor(ManageSession::class)]
class Pay extends Manage
{
    
    public function index(): string
    {
        return $this->render("支付设置", "Config/Pay.html");
    }

    public function plugin(): string
    {
        return $this->render("支付插件", "Config/PayPlugin.html");
    }
}