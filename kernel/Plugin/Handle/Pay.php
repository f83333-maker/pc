<?php
declare (strict_types=1);

namespace Kernel\Plugin\Handle;

use Kernel\Context\Interface\Response;

interface Pay
{

    public function create(): \Kernel\Plugin\Entity\Pay;

    public function async(): Response;
}