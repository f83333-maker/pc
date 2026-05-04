<?php
declare (strict_types=1);

namespace App\Controller\Admin\Manage;

use App\Controller\Admin\Base;
use App\Interceptor\Admin;
use Kernel\Annotation\Interceptor;
use Kernel\Context\Interface\Response;

#[Interceptor(class: Admin::class)]
class Permission extends Base
{

    public function index(): Response
    {
        return $this->render("Manage/Permission.html", "权限管理");
    }
}