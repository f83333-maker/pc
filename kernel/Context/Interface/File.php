<?php
declare(strict_types=1);

namespace Kernel\Context\Interface;

interface File
{

    public function getFileName(): string;

    public function getMime(): string;

    public function getTmp(): string;

    public function getError(): int;

    public function getSize(): int;

    public function getSuffix(): string;

    public function save(string $path, array $ext = ['jpg', 'png', 'jpeg', 'ico', 'gif', 'mp4', 'zip', 'woff', 'woff2', 'ttf', 'otf'], int $size = 10240, string $dir = BASE_PATH): string;
}