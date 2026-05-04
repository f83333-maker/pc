<?php 
declare (strict_types=1);

use Kernel\Console\Console;
use Kernel\Context\App;
use Kernel\Server\CLI;
use Swoole\Coroutine;

Coroutine::set(['hook_flags' => SWOOLE_HOOK_ALL, 'enable_deadlock_check' => false]);

if (Console::instance()->isCommand($argv)) {
    App::$isCommand = true;
    
    Console::instance()->execute($argv);
} else {
    
    CLI::instance()->start();
}