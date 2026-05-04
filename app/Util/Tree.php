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
            if (isset($items[$item[$parentKey]])) {
                $items[$item[$parentKey]][$childrenName][] = &$items[$k];
            } else {
                $tree[] = &$items[$k];
            }
        }
        return $tree;
    }
}