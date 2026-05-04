<?php
declare (strict_types=1);

namespace App\Service;

use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Bind\Image::class)]
interface Image
{

    public function createThumbnail(string $imagePath, int $newHeight, string $basePath = BASE_PATH): bool|string;

    public function downloadRemoteImage(string $url, bool $isCreateThumbnail = true, ?int $userId = null): array;

    public function isRealImageFromURL($url): bool;

    public function getImageExtensionFromURL(string $url): string;

    public function isRealImage(string $filePath): bool;
}