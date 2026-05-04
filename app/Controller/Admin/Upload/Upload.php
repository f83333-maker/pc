<?php
declare (strict_types=1);

namespace App\Controller\Admin\Upload;

use App\Controller\Admin\Base;
use App\Interceptor\Admin;
use Kernel\Annotation\Interceptor;
use Kernel\Context\Interface\Response;

#[Interceptor(class: Admin::class)]
class Upload extends Base
{

    public function index(): Response
    {
        return $this->render("Upload/Upload.html", "文件管理");
    }
}