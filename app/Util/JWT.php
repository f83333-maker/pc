<?php
declare (strict_types=1);

namespace App\Util;

class JWT
{

    public static function getHead(string $jwt): array
    {
        $arr = explode(".", $jwt);
        if (count($arr) != 3) {
            return [];
        }

        $head = base64_decode($arr[0]);
        return $head ? (array)json_decode($head, true) : [];
    }
}