<?php
declare (strict_types=1);

namespace App\Service\Common;

use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Common\Bind\SmsCaptcha::class)]
interface SmsCaptcha
{
    
    public function sendCaptcha(string $key, string $phone): void;

    public function checkCaptcha(string $key, string $phone, int $code): bool;

    
    public function destroyCaptcha(string $key, string $phone): void;
}