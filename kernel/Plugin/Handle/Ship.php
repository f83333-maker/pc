<?php
declare(strict_types=1);

namespace Kernel\Plugin\Handle;

interface Ship
{

    public function delivery(): string;

    public function stock(): int|string;

    public function hasEnoughStock(int $quantity = 1): bool;

    public function inspection(array $map): bool;

    public function isCustomRender(): bool;

    public function render(): string;
}