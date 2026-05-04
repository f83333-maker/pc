<?php
declare (strict_types=1);

namespace Kernel\Annotation;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Bind
{
    
    public string $class;

    public function __construct(string $class)
    {
        $this->class = $class;
    }
}