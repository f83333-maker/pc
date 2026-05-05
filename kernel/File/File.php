<?php
declare (strict_types=1);

namespace Kernel\File;

use Kernel\Exception\RuntimeException;

class File
{

    public mixed $resource = false;

    public string $path = "";

    private bool $lock = false;

    public int $size = 0;

    public function __construct(string $path, string $mode = "r")
    {
        if (!file_exists($path)) {
            $directory = dirname($path);
            if (!is_dir($directory) && !mkdir($directory, 0777, true)) {
                throw new RuntimeException('could not create the directory:' . $directory . ' (check permissions of ' . dirname($directory) . ')');
            }
            $file = @fopen($path, 'w');
            if ($file === false) {
                throw new RuntimeException('could not create the file:' . $path . ' (the web user has no write permission on directory ' . $directory . ')');
            }
            fclose($file);
            @chmod($path, 0666);
        }

        $resource = @fopen($path, $mode);
        if ($resource === false) {
            // Likely a permission issue on an existing file. Try to relax permissions and retry once.
            @chmod($path, 0666);
            $resource = @fopen($path, $mode);
        }
        if ($resource === false) {
            $owner = function_exists('posix_getpwuid') && function_exists('fileowner')
                ? (posix_getpwuid((int)@fileowner($path))['name'] ?? 'unknown')
                : 'unknown';
            $runner = function_exists('posix_geteuid') && function_exists('posix_getpwuid')
                ? (posix_getpwuid(posix_geteuid())['name'] ?? 'unknown')
                : 'unknown';
            throw new RuntimeException(
                'could not open the file:' . $path .
                ' (file owner=' . $owner . ', web user=' . $runner . '; please run: chown -R ' . $runner . ':' . $runner . ' ' . dirname($path) . ' && chmod -R 755 ' . dirname($path) . ')'
            );
        }
        $this->resource = $resource;
        $this->path = $path;
    }

    public function shareLock(): self
    {
        if (!flock($this->resource, LOCK_SH)) {
            $this->close();
            throw new RuntimeException('could not get the lock:' . $this->path);
        }
        $this->lock = true;
        return $this;
    }

    public function lock(): self
    {
        if (!flock($this->resource, LOCK_EX)) {
            $this->close();
            throw new RuntimeException('could not get the lock:' . $this->path);
        }
        $this->lock = true;
        return $this;
    }

    public function unlock(): self
    {
        if (!$this->lock) {
            return $this;
        }
        $this->lock = false;
        flock($this->resource, LOCK_UN);
        return $this;
    }

    public function close(): void
    {
        $this->unlock();
        fclose($this->resource);
    }

    public function size(): int
    {
        $this->size = filesize($this->path);
        return $this->size;
    }

    public function contents(): string
    {
        if (!file_exists($this->path)) {
            return "";
        }
        clearstatcache();
        $size = $this->size();
        if ($size <= 0) {
            return "";
        }
        return (string)fread($this->resource, $this->size());
    }

    public function write(string $contents): void
    {
        if (fwrite($this->resource, $contents) === false) {
            throw new RuntimeException('could not write to the file:' . $this->path);
        }
    }

    public function rewind(): void
    {
        rewind($this->resource);
    }

    public function autoTruncate(): void
    {
        ftruncate($this->resource, ftell($this->resource));
    }

}
