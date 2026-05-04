<?php
declare (strict_types=1);

namespace Kernel\Console;

use Kernel\Log\Const\Color;
use Kernel\Log\Log;

abstract class Command
{

    protected array $param;

    protected \Kernel\Context\Command $command;

    public function __construct(array $param, \Kernel\Context\Command $command)
    {
        $this->param = $param;
        $this->command = $command;
    }

    protected function info(string $message): void
    {
        Log::inst()->stdout(sprintf("[%s]: %s", $this->command->getCommand(), $message), Color::BLUE, true);
    }

    protected function success(string $message): void
    {
        Log::inst()->stdout(sprintf("[%s]: %s", $this->command->getCommand(), $message), Color::GREEN, true);
    }

    protected function error(string $message): void
    {
        Log::inst()->stdout(sprintf("[%s]: %s", $this->command->getCommand(), $message), Color::RED, true);
    }
}