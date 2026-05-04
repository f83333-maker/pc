<?php
declare(strict_types=1);

namespace App\Util;
use Zxing\QrReader;

ini_set('memory_limit', '1024M');

class QrCode
{
    
    public static function parse(string $path): string
    {
        $qrReader = new QrReader($path);
        return (string)$qrReader->text();
    }
}