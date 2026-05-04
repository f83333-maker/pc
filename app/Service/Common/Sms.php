<?php
declare (strict_types=1);

namespace App\Service\Common;

use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Common\Bind\Sms::class)]
interface Sms
{

    public function send(array $config, string $phone, string $templateCode, array $var = []): void;
}