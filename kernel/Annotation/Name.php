<?php
declare (strict_types=1);

namespace Kernel\Annotation;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Name
{

    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}