<?php
declare(strict_types=1);

namespace App\Service\Admin;

use Kernel\Annotation\Bind;
use Kernel\Context\Interface\Request;
use Kernel\Context\Interface\Response;

#[Bind(class: \App\Service\Admin\Bind\Manage::class)]
interface Manage
{
    
    public function login(Request $request, Response $response): Response;

    
    public function getMenu(\App\Model\Manage $manage): array;
}