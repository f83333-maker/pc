<?php
declare(strict_types=1);

namespace App\Controller;

use Kernel\Annotation\Inject;
use Kernel\Context\Interface\Request;
use Kernel\Context\Interface\Response;
use Kernel\Exception\NotFoundException;
use Kernel\Plugin\Sync;
use Kernel\Plugin\Usr;

class Index
{

    #[Inject]
    protected Request $request;

    #[Inject]
    protected Response $response;

    public function hello(): Response
    {
        return $this->response->json();
    }

    public function wait(): Response
    {
        $list = Sync::inst()->list();
        return $this->response->json(data: ["state" => empty($list)]);
    }

    public function owner(): Response
    {
        $usr = Usr::inst()->getUsr();
        return $this->response->json(data: ["usr" => $usr]);
    }
}