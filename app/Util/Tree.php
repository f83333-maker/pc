<?php
declare (strict_types=1);

namespace App\Util;

class Tree
{

    public static function generate(array $array, string $primaryKey = 'id', string $parentKey = 'pid', string $childrenName = 'children'): array
    {
        $items = [];
        foreach ($array as $row) {
            $row = (array)$row;
            $items[$row[$primaryKey]] = $row;
        }
        $tree = [];
        foreach ($items as $k => $item) {
            // 用 null 合并安全提取父级，避免 pid 键缺失时触发 PHP 8 "Undefined array key" 警告。
            // 原始逻辑 isset($items[null]) === false，等价于落到 else 分支，结果与原实现完全一致。
            $parentId = $item[$parentKey] ?? null;
            if ($parentId !== null && isset($items[$parentId])) {
                $items[$parentId][$childrenName][] = &$items[$k];
            } else {
                $tree[] = &$items[$k];
            }
        }
        return $tree;
    }
}
