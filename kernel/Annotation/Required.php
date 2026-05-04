<?php
declare (strict_types=1);

namespace Kernel\Annotation;
#[\Attribute(\Attribute::TARGET_METHOD)]
class Required
{

    public int $mode;
    public string $message;

    public function __construct(string $message, int $mode = \Kernel\Validator\Required::EXTREME)
    {
        $this->mode = $mode;
        $this->message = $message;
    }
}