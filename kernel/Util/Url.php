<?php
declare (strict_types=1);

namespace Kernel\Util;

class Url
{

    
    public static function getWildcard(string $domain): string
    {
        $parts = explode('.', $domain);
        if (count($parts) > 2) {
            $parts[0] = '*';
            return implode('.', $parts);
        }
        return $domain;
    }

}