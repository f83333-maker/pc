<?php
declare(strict_types=1);

namespace Kernel\Util;

class System
{
    
    public static function getBitSize(): ?int
    {
        if (PHP_INT_SIZE === 4) {
            return 32;
        } else if (PHP_INT_SIZE === 8) {
            return 64;
        } else {
            return null;
        }
    }

    
    public static function checkPortAvailable(int $port, string $host = "127.0.0.1"): bool
    {
        $connection = @fsockopen($host, $port);
        if (is_resource($connection)) {
            fclose($connection);
            return false;
        } else {
            return true;
        }
    }

    public static function getRandPort(): int
    {
        $port = rand(1024, 65535);
        if (self::checkPortAvailable($port)) {
            return $port;
        } else {
            return self::getRandPort();
        }
    }
}