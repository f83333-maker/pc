<?php

declare(strict_types=1);

namespace App\Util;

use DirectoryIterator;
use Kernel\Exception\JSONException;

class FileCache
{

    public static function getJsonFile(string $key, string $name): array
    {
        $filePath = BASE_PATH . "/runtime/{$key}/{$name}.json";
        if (!file_exists($filePath)) {
            return [];
        }

        $fileContents = json_decode(file_get_contents($filePath), true);
        if ($fileContents['timeout'] != null && $fileContents['timeout'] < time()) {
            unlink($filePath);
            return [];
        }
        return $fileContents['contents'];
    }

    public static function setJsonFile(string $key, string $name, array $data = [], int $cache = 0): bool
    {
        try {
            $dirPath = BASE_PATH . "/runtime/{$key}";
            if (!is_dir($dirPath)) {
                mkdir($dirPath, 0777, true);
            }
            $filePath = $dirPath . "/{$name}.json";
            file_put_contents($filePath, json_encode([
                "contents" => $data,
                "timeout" => $cache == 0 ? null : (time() + $cache)
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        } catch (\Throwable $throwable) {
            return false;
        }

        return true;
    }

    public static function clearCache(string $key): bool
    {
        $dirPath = BASE_PATH . "/runtime/{$key}";

        if (!is_dir($dirPath)) {
            return false;
        }

        $dir = new DirectoryIterator($dirPath);

        foreach ($dir as $fileinfo) {

            if (!$fileinfo->isDot() && strtolower($fileinfo->getExtension()) == 'json') {
                $filePath = $fileinfo->getPathname();

                $jsonContent = file_get_contents($filePath);
                $data = json_decode($jsonContent, true);

                if (!empty($data)) {

                    if (isset($data['timeout'])) {

                        if ($data['timeout'] < time()) {
                            unlink($filePath);
                        }
                    }
                }
            }
        }
        return true;
    }
}
