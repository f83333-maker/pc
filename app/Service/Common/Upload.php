<?php
declare (strict_types=1);

namespace App\Service\Common;

use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Common\Bind\Upload::class)]
interface Upload
{
    
    public function add(string $path, string $type, ?int $userId = null): void;

    
    public function get(string $hash): ?string;

    
    public function remove(string $path): void;
}