<?php
declare(strict_types=1);

namespace Kernel\Annotation;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Hook
{

    public int $point;

    public function __construct(int $point)
    {
    }
}