<?php
declare (strict_types=1);

namespace App\Service\Common;

use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Common\Bind\Captcha::class)]
interface Captcha
{

    public function create(string $key, int $expire, int $limiter = 60): string;

    
    public function verify(string $key, string $code): bool;

    
    public function destroy(string $key): void;
}