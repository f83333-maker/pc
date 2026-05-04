<?php
declare(strict_types=1);

namespace App\Service;

use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Bind\Dict::class)]
interface Dict
{
    
    public function get(string $dictName, string $keywords = '', string $where = ''): array;
}