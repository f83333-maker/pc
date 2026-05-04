<?php
declare (strict_types=1);

namespace App\Service\Common;

use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Common\Bind\Smtp::class)]
interface Smtp
{

    public function send(string $to, string $title, string $body, array $files = [], array $config = []): bool;
}