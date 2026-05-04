<?php
declare (strict_types=1);

namespace Kernel\Context\Interface;

interface Command
{
    
    public function getCommand(): string;

    public function getClass(): string;

    public function getMethod(): string;

    public function getExtend(): mixed;
}