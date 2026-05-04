<?php
declare (strict_types=1);

namespace Kernel\Pool;

interface Connection
{

    public function createObject(): mixed;
}