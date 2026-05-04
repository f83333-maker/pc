<?php
declare (strict_types=1);

namespace Kernel\Plugin\Handle;

use Kernel\Plugin\Entity\Item;

interface ForeignShip
{

    public function getItems(): array;

    public function getItem(string $uniqueId, array $options = []): ?Item;
}