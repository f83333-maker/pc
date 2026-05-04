<?php
declare (strict_types=1);

namespace Kernel\Console;

use Kernel\Annotation\Collector;
use Kernel\Component\Singleton;
use Kernel\Container\Di;
use Kernel\Context\App;
use Kernel\Context\App as AppContext;
use Kernel\Context\Interface\Command;
use Kernel\Exception\RuntimeException;
use Kernel\Pool\ConnectionPool;
use Kernel\Util\File;
use Kernel\Log\Log;
use Swoole\Coroutine;

class Console
{
    use Singleton;

    public function isCommand(array $arg): bool
    {
        if (isset($arg[1]) && $arg[1] === "console") {
            return true;
        }
        return false;
    }

    public function execute(array $arg): void
    {

        if (count($arg) < 3) {
            $list = $this->list();
            if (count($list) > 0) {
                echo "\033[1;32m已注册的命令列表：\033[0m\n";

                foreach ($list as $item) {
                    $desc = $item->getDesc();
                    echo "\033[1;32m[{$item->getCommand()}]\033[0m - \033[1;33m{$item->getName()}\033[0m" . ($desc ? "\033[0;36m「{$item->getDesc()}」\033[0m \n" : "\n");
                }

            }
            exit;
        }

        $commandStr = $arg[2];  
        unset($arg[0], $arg[1], $arg[2]);
        $arg = array_values($arg);

        $command = $this->get($commandStr);

        if (!$command) {
            echo sprintf("%s: 未找到命令\n", $commandStr);
            exit;
        }

        $this->startMysql();
        \Co\run(function () use ($commandStr, $command, $arg) {
            try {
                $var = new ($command->getClass())($arg, $command);
                Di::instance()->inject($var);
                call_user_func_array([$var, $command->getMethod()], $this->getMethodParameters($command->getClass(), $command->getMethod(), $arg));
            } catch (\Throwable $e) {
                echo sprintf("%s: %s\n", $commandStr, $e->getMessage());
            }
        });
    }

    public function add(string $command, array $callable, mixed $extend = null, ?string $name = null, ?string $desc = null): void
    {
        try {
            File::writeForLock(BASE_PATH . "/runtime/command", function (string $contents) use ($desc, $name, $extend, $callable, $command) {
                $commands = unserialize($contents) ?: [];
                $obj = new \Kernel\Context\Command($command, $callable[0], $callable[1], $extend, $name, $desc);
                $commands[$command] = $obj;
                return serialize($commands);
            });
        } catch (\Throwable $e) {
            Log::inst()->error("添加控制台指令时，出现错误：{$e->getMessage()}");
        }
    }

    public function del(string $command): void
    {
        File::writeForLock(BASE_PATH . "/runtime/command", function (string $contents) use ($command) {
            $commands = unserialize($contents) ?: [];
            unset($commands[$command]);
            return serialize($commands);
        });
    }

    public function get(?string $command = null): Command|array|null
    {
        $runtime = BASE_PATH . "/runtime/command";
        if (!is_file($runtime)) {
            return null;
        }

        $commands = unserialize(File::read($runtime)) ?: [];

        if ($command === null) {
            return $commands;
        }

        if (isset($commands[$command])) {
            return $commands[$command];
        }
        return null;
    }

    public function list(): array
    {
        $runtime = BASE_PATH . "/runtime/command";
        if (!is_file($runtime)) {
            return [];
        }
        return unserialize(File::read($runtime)) ?: [];
    }

    private function startMysql(): void
    {
        Coroutine\run(function () {
            Di::instance()->set(ConnectionPool::class, new ConnectionPool(\Kernel\Database\MysqlConnection::class, AppContext::$database['pool']));
        });
    }

    public function getMethodParameters(string $class, string $method, array $arg): array
    {
        $methodRef = new \ReflectionMethod($class, $method);
        $parameters = [];
        $reflectionParameters = $methodRef->getParameters();
        foreach ($reflectionParameters as $index => $param) {
            $type = $param->getType()->getName();
            $name = $param->getName();
            $value = Collector::instance()->dat($type, $arg[$index] ?? null);
            $parameters[$name] = $value;
        }
        return $parameters;
    }

    public function generateCompletion(): void
    {
        if (!App::$cli) {
            return;
        }
        try {
            $commands = $this->get();
            $completionOptions = "";

            if (empty($commands)) {
                return;
            }

            foreach ($commands as $command) {
                $completionOptions .= $command->getCommand() . " ";
            }
            $this->updateBashCompletion(trim($completionOptions));
        } catch (\Throwable $e) {
            Log::inst()->error("当前系统不支持Command自动补全：" . $e->getMessage());
        }
    }

    private function updateBashCompletion(string $options): void
    {
        $escapedOptions = escapeshellarg($options);
        $bashrcPath = getenv("HOME") . '/.bashrc';
        $completionCommand = "complete -W {$escapedOptions} mcy\n";

        $file = new \Kernel\File\File($bashrcPath, "c+");
        $file->lock();
        $bashrcContents = $file->contents();

        $pattern = '/complete -W .* mcy/';
        if (preg_match($pattern, $bashrcContents)) {

            $newBashrcContents = preg_replace($pattern, $completionCommand, $bashrcContents);
        } else {

            $newBashrcContents = $bashrcContents . "\n" . $completionCommand;
        }

        $file->rewind();
        $file->write($newBashrcContents);
        $file->autoTruncate();
        $file->close();
    }

}