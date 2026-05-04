<?php
declare(strict_types=1);

namespace App\Service;

use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Bind\Pay::class)]
interface Pay
{

    public function getPlugins(): array;

    public function getPluginInfo(string $name): array;

    public function getPluginLog(string $handle): string;

    public function ClearPluginLog(string $handle): bool;
}