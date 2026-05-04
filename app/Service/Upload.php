<?php
declare(strict_types=1);

namespace App\Service;

use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Bind\Upload::class)]
interface Upload
{

    public function handle($upload, $dir, $type, int $size = 10000, string $file_name = ''): mixed;

    public function add(string $path, string $type, ?int $userId = null): void;

    public function get(string $hash): ?string;

    public function remove(string $path): void;
}