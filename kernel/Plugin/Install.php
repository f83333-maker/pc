<?php
declare (strict_types=1);

namespace Kernel\Plugin;

use Kernel\Component\Singleton;
use Symfony\Component\Finder\Finder;

class Install
{
    use Singleton;

    public function getSandbox(string $name, string $env = "/app/Plugin"): ?array
    {
        $path = BASE_PATH . $env . "/{$name}/Config/Sandbox.php";
        if (file_exists($path)) {
            return (array)require($path);
        }
        return null;
    }

    public function createEnvironment(int $userId, string $name): void
    {
        $env = Usr::inst()->userToEnv($userId);
        $sandbox = $this->getSandbox($name, $env);
        $finder = Finder::create()->files()->name('*.php')->name('*.html')->name("*.css")->name("*.js")->in(BASE_PATH . $env . "/{$name}");
        $ignorePlugin = $sandbox['ignore_plugin'] ?? [];
        foreach ($finder as $file) {
            $raw = file_get_contents($file->getRealPath());
            switch ($file->getExtension()) {
                case "php":

                    $raw = preg_replace_callback('/(?:use\s+)?App\\\Plugin\\\([^\\\]+)\\\[^\\\](?:\s+as\s+\w+)?+/', function ($matches) use ($ignorePlugin, $userId) {
                        if (in_array($matches[1], $ignorePlugin)) {
                            return $matches[0];
                        }
                        return preg_replace("#App\\\\Plugin\\\\#", "Usr\\Plugin\\M_{$userId}\\", $matches[0]);
                    }, $raw);

                    $raw = preg_replace_callback('/(protected\s+\?string\s+\$table\s*=\s*(?:\'|"))([^\'"]+)/', function ($matches) use ($userId) {
                        if (str_contains($matches[2], "{$userId}_")) {
                            return $matches[1] . $matches[2];
                        }
                        return $matches[1] . "{$userId}_" . $matches[2];
                    }, $raw);

                    $raw = preg_replace_callback('#/app/Plugin/([^/]+)#', function ($matches) use ($ignorePlugin, $userId) {
                        if (in_array($matches[1], $ignorePlugin)) {
                            return $matches[0];
                        }
                        return preg_replace("#/app/Plugin/#", "/usr/Plugin/M_{$userId}/", $matches[0]);
                    }, $raw);
                    break;
                case "css":
                case "html":
                case "js":

                    $raw = preg_replace_callback('#/app/Plugin/([^/]+)#', function ($matches) use ($ignorePlugin, $userId) {
                        if (in_array($matches[1], $ignorePlugin)) {
                            return $matches[0];
                        }
                        return preg_replace("#/app/Plugin/#", "/usr/Plugin/M_{$userId}/", $matches[0]);
                    }, $raw);
                    break;
            }
            file_put_contents($file->getRealPath(), $raw);
        }
    }
}