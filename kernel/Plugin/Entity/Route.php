<?php
declare (strict_types=1);

namespace Kernel\Plugin\Entity;

class Route
{
    
    public string $usr;

    public string $name;

    public function __construct(string $name, string $usr)
    {
        $this->usr = $usr;
        $this->name = $name;
    }
}