<?php
declare(strict_types=1);

namespace App\Util;

use Kernel\Exception\JSONException;

class File
{

    private static array $cache = [];

    public static function copyDirectory(string $src, string $dst): void
    {
        if (!is_dir($src)) {
            throw new \Exception("源目录不存在: {$src}");
        }

        if (!is_dir($dst)) {
            if (!mkdir($dst, 0755, true) && !is_dir($dst)) {
                throw new \Exception("创建目标目录失败: {$dst}");
            }
            @chmod($dst, 0755);
        }

        $dir = opendir($src);
        if ($dir === false) {
            throw new \Exception("无法打开源目录: {$src}");
        }

        try {
            while (($file = readdir($dir)) !== false) {
                if ($file === '.' || $file === '..') {
                    continue;
                }

                $srcPath = $src . DIRECTORY_SEPARATOR . $file;
                $dstPath = $dst . DIRECTORY_SEPARATOR . $file;

                if (is_dir($srcPath)) {
                    self::copyDirectory($srcPath, $dstPath);
                    @chmod($dstPath, 0755);
                } else {
                    if (!is_dir(dirname($dstPath))) {
                        if (!mkdir(dirname($dstPath), 0755, true) && !is_dir(dirname($dstPath))) {
                            throw new \Exception("创建父目录失败: " . dirname($dstPath));
                        }
                    }

                    if (!copy($srcPath, $dstPath)) {
                        throw new \Exception("复制文件失败: {$srcPath} -> {$dstPath}");
                    }

                    @chmod($dstPath, 0644);
                }
            }
        } finally {
            closedir($dir);
        }
    }

    public static function delDirectory(string $path): void
    {
        if ($handle = opendir($path)) {
            while (false !== ($item = readdir($handle))) {
                if ($item != "." && $item != "..") {
                    if (is_dir("{$path}/{$item}")) {
                        self::delDirectory("{$path}/{$item}");
                    } else {
                        unlink("{$path}/{$item}");
                    }
                }
            }
            closedir($handle);
            rmdir($path);
        }
    }

    public static function codeLoad(string $path, bool $cli = false): mixed
    {

        if ($cli) {
            Opcache::invalidate($path);
            return require($path);
        }

        if (isset(self::$cache[$path])) {
            return self::$cache[$path];
        }
        self::$cache[$path] = require($path);
        return self::$cache[$path];
    }

    public static function exists(string $path): bool
    {
        if (!file_exists($path)) {
            return false;
        }

        $dir = dirname($path);
        $file = basename($path);
        if (!is_dir($dir)) {
            return false;
        }

        $files = scandir($dir);
        if ($files === false) {
            return false;
        }
        return in_array($file, $files, true);
    }
}