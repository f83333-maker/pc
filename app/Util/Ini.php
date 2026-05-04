<?php
declare(strict_types=1);

namespace App\Util;

use Kernel\Exception\JSONException;

class Ini
{

    private static function arrayMerge(array $a1, array $a2): array
    {
        $arr = $a1 + $a2;
        foreach ($arr as $k => $v) {
            if (is_array($v) && isset($a1[$k]) && isset($a2[$k])) {
                $arr[$k] = self::arrayMerge($a1[$k], $a2[$k]);
            }
        }
        return $arr;
    }

    private static function parseObj(&$src, array $link, string $value)
    {
        if (count($link) <= 0) {
            $src = $value;
            return;
        }
        
        $shift = array_shift($link);
        
        if (str_contains($shift, '[]')) {
            
            $key = str_replace("[]", "", $shift);
            $src[$key][] = [];
            $index = count($src[$key]) - 1;
            self::parseObj($src[$key][$index], $link, $value);
            
        } else {
            $src[$shift] = [];
            self::parseObj($src[$shift], $link, $value);
        }
    }

    public static function toArray(string $content): array
    {
        $data = preg_split('/[\r\n]+/s', trim($content));
        $list = [];
        $nodeName = "";
        foreach ($data as $var) {
            if (empty($var)) {
                continue;
            }
            preg_match('#\\[(.*?)\\]$#', $var, $match);
            if (isset($match[1])) {
                $nodeName = $match[1];
                if (!array_key_exists($nodeName, $list)) {
                    $list[$nodeName] = [];
                }
            } else {
                if (!empty($nodeName)) {
                    $temporary = explode('=', $var);
                    if (count($temporary) != 2) {
                        throw new JSONException('配置解析异常，' . $var . ' 没有赋值');
                    }

                    $left = $temporary[0];
                    $leftParse = explode(".", $left);
                    $src = [];
                    self::parseObj($src, $leftParse, $temporary[1]);
                    $list[$nodeName] = self::arrayMerge($list[$nodeName], $src);
                } else {
                    throw new JSONException("配置解析异常，{$var} 不能没有节点");
                }
            }
        }
        return $list;
    }

    private static function parseContent(array $config, ?string $prefix = null): string
    {
        $cfg = "";
        foreach ($config as $key => $val) {
            if (is_array($val)) {
                $cfg .= self::parseContent($val, $prefix ? $prefix . "." . $key : $key);
            } else {
                
                $cfg .= ($prefix ? $prefix . "." : "") . $key . "=" . $val . PHP_EOL;
            }
        }

        return $cfg;
    }

    public static function toConfig(array $config): string
    {
        $cfg = "";
        foreach ($config as $key => $value) {
            if (is_array($value)) {
                $cfg .= "[{$key}]" . PHP_EOL;
                $cfg .= self::parseContent($value);
            }
        }
        return trim($cfg);
    }
}