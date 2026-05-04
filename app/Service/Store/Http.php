<?php
declare (strict_types=1);

namespace App\Service\Store;

use App\Entity\Store\Authentication;
use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Store\Bind\Http::class)]
interface Http
{

    public function getBaseUrl(): string;

    public function ping(): array;

    public function setNode(int $index): void;

    public function getNode(): int;

    public function request(string $url, array $data = [], ?Authentication $authentication = null): \App\Entity\Store\Http;

    public function download(string $url, string $path, ?Authentication $authentication = null, string $method = "GET", array $data = []): bool;

    public function upload(string $mime, string $file, ?Authentication $authentication = null): \App\Entity\Store\Http;
}