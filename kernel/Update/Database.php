<?php
declare (strict_types=1);

namespace Kernel\Update;

interface Database
{
    
    public function handle(): void;
}