<?php
declare (strict_types=1);

namespace App\Service\Common;

use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Common\Bind\Code::class)]
interface Code
{

    public function create(string $key, int $expire = 60): int;
}