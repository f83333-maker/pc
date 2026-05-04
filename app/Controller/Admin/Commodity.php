<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Base\View\Manage;
use App\Interceptor\ManageSession;
use Kernel\Annotation\Interceptor;
use Kernel\Exception\ViewException;

#[Interceptor(ManageSession::class)]
class Commodity extends Manage
{

    public function index(): string
    {

        $data = [];

        $data['not'] = \App\Model\Commodity::query()->where("status", 0)->count();

        $data['shelves'] = \App\Model\Commodity::query()->where("status", 1)->count();

        $data['main'] = \App\Model\Commodity::query()->where("owner", 0)->count();

        $data['all'] = $data['not'] + $data['shelves'];

        $data['child'] = $data['all'] - $data['main'];

        $data['child_shelves'] = \App\Model\Commodity::query()->where("status", 1)->where("owner", "!=", 0)->count();

        return $this->render("商品管理", "Trade/Commodity.html", $data);
    }
}