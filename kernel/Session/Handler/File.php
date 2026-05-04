<?php
declare (strict_types=1);

namespace Kernel\Session\Handler;

use Kernel\Context\App;
use Kernel\Context\Interface\Request;
use Kernel\Exception\RuntimeException;
use Kernel\Session\Session;
use Kernel\Util\Context;
use Kernel\Util\File as Filesystem;
use Symfony\Component\Finder\Finder;

class File implements Session
{

    private string $path = BASE_PATH . "/runtime/session/";

    public function __construct()
    {
        if (!is_dir($this->path)) {
            mkdir($this->path, 0755, true);
        }
    }

    public function getPath(): string
    {
        return $this->path . $this->id();
    }

    
    public function get(?string $key = null): mixed
    {
        if (!App::$cli) {
            return $_SESSION[$key] ?? null;
        }

        $path = $this->getPath();

        if (!is_file($path)) {
            return null;
        }

        $data = unserialize((string)Filesystem::read($path));

        if ($key) {
            return $data[$key] ?? null;
        }

        return $data;
    }

    public function set(string $key, mixed $value): void
    {
        if (!App::$cli) {
            $_SESSION[$key] = $value;
            return;
        }

        Filesystem::writeForLock($this->getPath(), function (string $contents) use ($value, $key) {
            $data = unserialize($contents) ?: [];
            $data[$key] = $value;
            return serialize($data);
        });
    }

    public function has(string $key): bool
    {
        if (!App::$cli) {
            return isset($_SESSION[$key]);
        }

        $path = $this->getPath();
        if (!is_file($path)) {
            return false;
        }

        $data = (array)$this->get();
        return isset($data[$key]);
    }

    public function remove(string $key): void
    {
        if (!App::$cli) {
            $_SESSION[$key] = null;
            unset($_SESSION[$key]);
            return;
        }

        $path = $this->getPath();
        if (!is_file($path)) {
            return;
        }

        Filesystem::writeForLock($path, function (string $contents) use ($key) {
            $data = unserialize($contents) ?: [];
            unset($data[$key]);
            return serialize($data);
        });
    }

    public function clear(): void
    {
        if (!App::$cli) {
            $_SESSION = [];
            unset($_SESSION);
            return;
        }
        Filesystem::remove($this->getPath());
    }

    public function gc(): bool
    {
        $files = Finder::create()
            ->in($this->path)
            ->files()
            ->ignoreDotFiles(true)
            ->date('<= now - ' . App::$session['options']['lifetime'] . ' seconds');

        foreach ($files as $file) {
            Filesystem::remove($file->getRealPath());
        }

        return true;
    }

    public function id(): string
    {
        return Context::get(Request::class)->cookie(Session::NAME);
    }
}