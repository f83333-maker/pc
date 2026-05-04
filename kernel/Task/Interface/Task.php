<?php
declare (strict_types=1);

namespace Kernel\Task\Interface;

interface Task
{

    public function handle(): mixed;
}