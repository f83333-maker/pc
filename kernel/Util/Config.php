<?php
declare (strict_types=1);

namespace Kernel\Util;

use Kernel\Exception\JSONException;

class Config
{
    
    private static array $config = [];

    public static function set(array $data, string $file, bool $merge = false): void
    {
        $config = [];

        if ($merge && file_exists($file)) {
            $config = require $file;
        }

        $config = array_merge($config, $data);

        $content = "<?php\n";
        $content .= "declare(strict_types=1);\n\n";
        $content .= "return [\n";
        $content .= self::arrayToString($config);
        $content .= "];\n";

        if (file_put_contents($file, $content) === false) {
            throw new \RuntimeException("无法写入配置文件: {$file}");
        }
    }

    private static function arrayToString(array $array, int $depth = 1): string
    {
        $indent = str_repeat("    ", $depth);
        $result = '';
        foreach ($array as $key => $value) {
            $key = str_replace("'", "\\'", $key);
            if (is_array($value)) {
                $subArray = self::arrayToString($value, $depth + 1);
                $result .= "{$indent}'{$key}' => [\n{$subArray}{$indent}],\n";
            } elseif (is_string($value)) {
                $value = str_replace("'", "\\'", $value);
                $result .= "{$indent}'{$key}' => '{$value}',\n";
            } elseif (is_numeric($value)) {
                $result .= "{$indent}'{$key}' => {$value},\n";
            } elseif (is_bool($value)) {
                $result .= "{$indent}'{$key}' => " . ($value ? 'true' : 'false') . ",\n";
            } else {
                throw new \InvalidArgumentException("不支持的数据类型: key={$key}");
            }
        }
        return $result;
    }

    public static function get(string $name): mixed
    {
        $column = Arr::getChainFirst($name);

        if (isset(self::$config[$column])) {
            return Arr::get(self::$config[$column] , Arr::getChainIgnoreFirst($name));
        }
        $file = BASE_PATH . '/config/' . $name . ".php";
        if (!file_exists($file)) {
            return [];
        }
        $data = (array)require($file);
        self::$config[$column] = $data;

        return Arr::get($data , Arr::getChainIgnoreFirst($name));
    }

}