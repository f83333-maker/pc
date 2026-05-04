<?php
declare(strict_types=1);

namespace Kernel\Util;

use Kernel\Exception\RuntimeException;

class File
{

    private static array $data = [];

    private static array $files = [];

    public static function writeForLock(string $path, callable $content): void
    {
        $file = new \Kernel\File\File($path, "c+");
        $file->lock();
        $data = $file->contents();
        $file->rewind();
        $file->write(call_user_func($content, $data));
        $file->autoTruncate();
        $file->close();
    }

    public static function write(string $path, string $content): bool
    {
        $directory = dirname($path);
        !is_dir($directory) && (mkdir($directory, 0755, true));
        return (bool)file_put_contents($path, $content);
    }

    public static function getChangeOverTime(string $file): false|int
    {
        if (file_exists($file)) {
            clearstatcache();
            return time() - filemtime($file);
        } else {
            return false;
        }
    }

    public static function read(string $path, callable $handle = null): mixed
    {
        clearstatcache();
        $exists = file_exists($path);
        if (!$exists) {
            return "";
        }

        if (isset(self::$files[$path]) && filemtime($path) == self::$files[$path]["time"]) {
            return self::$files[$path]['data'];
        }

        $contents = file_get_contents($path);

        if (is_callable($handle)) {
            $contents = call_user_func($handle, $contents);
        }

        self::$files[$path] = [
            "time" => filemtime($path),
            "data" => $contents
        ];
        return self::$files[$path]['data'];
    }

    
    public static function remove(string ...$path): void
    {
        foreach ($path as $p) {
            if (is_file($p)) {
                unlink($p);
            }
        }
    }

    
    public static function copy(string $src, string $dst): bool
    {
        if (!is_file($src)) {
            return false;
        }

        $directory = dirname($dst);
        !is_dir($directory) && (mkdir($directory, 0777, true));

        return copy($src, $dst);
    }

    public static function loadFile(string $path): ?string
    {
        if (!isset(self::$data[$path])) {
            self::$data[$path] = file_get_contents($path);
        }
        return self::$data[$path] ?? null;
    }

    public static function scan(string $path, bool $file = false): array
    {
        $list = scandir($path);
        $dir = [];
        foreach ($list as $item) {
            if ($item != '.' && $item != '..' && ($file || is_dir($path . $item))) {
                $dir[] = $item;
            }
        }
        return $dir;
    }

}