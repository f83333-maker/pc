<?php
declare (strict_types=1);

namespace App\Service\Store;

use App\Entity\Store\Authentication;
use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Store\Bind\Identity::class)]
interface Identity
{

    public function status(Authentication $authentication, string $tradeNo = ""): array;

    public function certification(string $certName, string $certNo, Authentication $authentication): string;
}