<?php
declare (strict_types=1);

namespace Kernel\Annotation;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Regex
{
    
    public string $regex;
    public string $message;

    public function __construct(string $regex, string $message)
    {
        $this->regex = $regex;
        $this->message = $message;
    }
}