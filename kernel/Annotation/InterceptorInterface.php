<?php
declare (strict_types=1);

namespace Kernel\Annotation;

interface InterceptorInterface
{
    
    public function handle(int $type): void;
}