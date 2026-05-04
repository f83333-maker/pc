<?php
declare (strict_types=1);

namespace App\Service\Common;

use App\Entity\Config\Currency;
use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Common\Bind\Config::class)]
interface Config
{

    public function getUserConfig(string $key, ?int $userId = null): mixed;

    
    public function getMainConfig(string $key): mixed;

    
    public function getUserOrMainConfig(string $key, ?int $userId = null): mixed;

    
    public function getCurrency(): Currency;

    
    public function getAsyncUrl(): string;
}