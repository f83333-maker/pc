<?php
declare (strict_types=1);

namespace Kernel\Util;

use GuzzleHttp\Client;

class Http
{

    public static function make(array $opt = []): Client
    {
        return new Client(array_merge(["verify" => false], $opt));
    }

}