<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Base\View\Manage;
use App\Interceptor\ManageSession;
use Kernel\Annotation\Interceptor;

#[Interceptor(ManageSession::class)]
class Dashboard extends Manage
{

    public function index(): string
    {
        return  $this->render("控制台","Dashboard/Index.html");
    }
}