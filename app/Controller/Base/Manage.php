<?php
declare(strict_types=1);

namespace App\Controller\Base;

use App\Util\Context;
use Kernel\Annotation\Inject;
use Kernel\Context\Interface\Request;

abstract class Manage
{
    #[Inject]
    protected Request $request;

    protected function getManage(): ?\App\Model\Manage
    {
        return Context::get(\App\Consts\Manage::SESSION);
    }
}