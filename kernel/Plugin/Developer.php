<?php
declare (strict_types=1);

namespace Kernel\Plugin;

use Kernel\Component\Singleton;
use Kernel\Exception\JSONException;
use Kernel\Util\Config;

class Developer
{
    use Singleton;

    private string $path = BASE_PATH . "app/Plugin/";

    public function getTmp(string $name): string
    {
        $file = BASE_PATH . "/kernel/Plugin/Developer/Template/{$name}.tmp";
        if (!file_exists($file)) {
            throw new JSONException("模板文件不存在");
        }
        return file_get_contents($file);
    }

    public function getReplaceTmp(array $replace, string $name): string
    {
        $tmp = $this->getTmp($name);
        foreach ($replace as $key => $value) {
            $tmp = str_replace("\${$key}", $value, $tmp);
        }
        return $tmp;
    }

    public function writeConfig(array $replace, string $name, string $file): void
    {
        $replaceTmp = $this->getReplaceTmp($replace, $name);
        $path = $this->path . $name . "/" . trim($file, "/");
        file_put_contents($path, $replaceTmp);
    }

    public function createPlugin(array $data): void
    {

        $key = ucfirst($data['key']);
        $path = $this->path . $key;

        if (is_dir($path)) {
            return;
        }

        mkdir($path, 0777, true);
        mkdir($path . "/Config", 0777, true);

        $cli = 'Plugin::ARCH_CLI';
        $fpm = 'Plugin::ARCH_FPM';

        $HOOK_SCOPE_GLOBAL = 'Plugin::HOOK_SCOPE_GLOBAL';
        $HOOK_SCOPE_USR = 'Plugin::HOOK_SCOPE_USR';

        $type = ["Plugin::TYPE_GENERAL", "Plugin::TYPE_PAY", "Plugin::TYPE_SHIP", "Plugin::TYPE_THEME"];

        $this->writeConfig([
            "name" => $data['name'],
            "author" => $data['author'],
            "description" => htmlspecialchars(strip_tags($data['description']), ENT_QUOTES),
            "version" => $data['version'],
            "arch" => $data['arch'] == 0 ? ($cli . " | " . $fpm) : ($data['arch'] == 1 ? $cli : $fpm),
            "hook_scope" => $data['scope'] == 0 ? $HOOK_SCOPE_GLOBAL : $HOOK_SCOPE_USR,
            "type" => $type[$data['type']]
        ], $key, "/Config/Info.php");
    }
}