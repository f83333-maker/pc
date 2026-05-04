<?php
declare (strict_types=1);

namespace Kernel\Plugin;

use Kernel\Component\Singleton;
use Kernel\Console\Console;
use Kernel\Context\App;
use Kernel\Exception\RuntimeException;

class Command
{

    use Singleton;

    public function add(string $name, string $env): void
    {
        if ($env != App::$mEnv) {
            return;
        }

        $plugin = Plugin::inst()->getPlugin($name, App::$mEnv);

        foreach ($plugin->command as $command => $callable) {
            Console::instance()->add($command, [$callable[0], $callable[1]], [
                "name" => $plugin->name,
                "env" => App::$mEnv
            ], $callable[2] ?? null, $callable[3] ?? null);
        }

    }

    public function del(string $name, string $env): void
    {
        if ($env != App::$mEnv) {
            return;
        }
        $plugin = Plugin::inst()->getPlugin($name, App::$mEnv);
        $commands = Console::instance()->get();

        foreach ($commands as $command) {
            $extend = $command->getExtend();
            if (is_array($extend) && isset($extend["name"]) && isset($extend["env"]) && $extend['name'] == $plugin->name && $extend['env'] == $plugin->env) {
                Console::instance()->del($command->getCommand());
            }
        }

    }
}