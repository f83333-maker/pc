<?php
declare(strict_types=1);

namespace Kernel\Plugin\Handle;

interface Database
{

    public function install(): void;

    public function uninstall(): void;
}