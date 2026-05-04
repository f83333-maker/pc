<?php
declare (strict_types=1);

namespace App\Service\Store;

use App\Entity\Store\Authentication;
use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Store\Bind\Personal::class)]
interface Personal
{
    
    public function getInfo(Authentication $authentication): array;
}