<?php
declare(strict_types=1);

namespace Kernel\Annotation;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Thread
{

    public string $name;

    public int $num;

    public function __construct(string $name, int $num = 1)
    {
        $this->name = $name;
        $this->num = $num;
    }
}