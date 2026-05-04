<?php
declare (strict_types=1);

namespace Kernel\Util;

class Arr
{

    public static function get(array $arr, ?string $chain): mixed
    {
        if (!$chain) {
            return $arr;
        }

        $keys = explode('.', trim($chain));
        foreach ($keys as $key) {
            if (isset($arr[$key])) {
                $arr = $arr[$key];
            } else {
                return null;
            }
        }
        return $arr;
    }

    public static function has(array $arr, ?string $chain): bool
    {
        if (!$chain) {
            return false;
        }

        $keys = explode('.', trim($chain));
        $len = count($keys);
        for ($i = 0; $i < $len; $i++) {
            if (isset($arr[$keys[$i]])) {
                $arr = $arr[$keys[$i]];
            } else {
                return false;
            }
        }

        return true;
    }

    public static function getChainFirst(string $chain): string
    {
        $keys = explode('.', trim($chain));
        return (string)$keys[0];
    }

    public static function getChainIgnoreFirst(string $chain): ?string
    {
        $keys = explode('.', trim($chain));
        if (count($keys) <= 1) {
            return null;
        }
        array_shift($keys);
        return implode('.', $keys);
    }

    public static function strToList(string $str, string $separator = "\n"): array
    {
        $list = explode($separator, $str);
        return array_values(array_filter(array_map(function ($item) {
            $item = trim($item);

            return ($item === '' || str_starts_with($item, '#') || str_starts_with($item, '//')) ? null : $item;
        }, $list)));
    }

    public static function xmlToArray(string $str): array
    {

        $xml = simplexml_load_string($str, 'SimpleXMLElement', LIBXML_NOCDATA);

        if ($xml === false) {
            return [];
        }
        return (array)json_decode(json_encode($xml), true) ?: [];
    }

    public static function override(mixed $primary, mixed $fallback): array
    {
        $primary = is_array($primary) ? $primary : [];
        $fallback = is_array($fallback) ? $fallback : [];
        return $primary + $fallback;
    }
}