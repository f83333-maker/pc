<?php
declare (strict_types=1);

namespace Kernel\Plugin\Abstract;

abstract class Process extends Plugin
{

    public abstract function handle(): void;
}