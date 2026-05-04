<?php
declare(strict_types=1);

namespace Kernel\Annotation;

use Kernel\Container\Di;
use Kernel\Exception\InterceptorException;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class Interceptor
{
    const TYPE_API = 1; 
    const TYPE_VIEW = 0; 

    public static array $run = [];

    public function __construct(mixed $class, int $type = self::TYPE_VIEW)
    {
        if (is_array($class)) {
            foreach ($class as $c) {
                $this->run($c, $type);
            }
            return;
        }
        $this->run($class, $type);
    }

    private function run(string $class, int $type): void
    {
        if (array_key_exists($class, Interceptor::$run)) {
            return;
        }

        $var = new $class();
        if ($var instanceof InterceptorInterface) {
            Di::inst()->inject($var);
            $var->handle($type);
            Interceptor::$run[$class] = 0x1;
            return;
        }
        throw new InterceptorException("interceptor not found");
    }

}