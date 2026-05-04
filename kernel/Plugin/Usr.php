<?php
declare (strict_types=1);

namespace Kernel\Plugin;

use Kernel\Component\Singleton;
use Kernel\Context\App;
use Kernel\Context\Interface\Request;
use Kernel\Exception\NotFoundException;
use Kernel\Exception\RuntimeException;
use Kernel\Plugin\Entity\Route;
use Kernel\Util\Context;
use Kernel\Util\Str;

class Usr
{
    use Singleton;

    private const USR = "/usr/Plugin/M_%d";
    public const MAIN = "/app/Plugin";

    
    public function getRoute(string $uri): ?Entity\Route
    {
        $rt = explode("/", trim($uri, "/"));
        if (count($rt) < 2) {
            return null;
        }
        if (strtolower($rt[0]) != "plugin") {
            return null;
        }
        if (!preg_match("/^\d+$/", $rt[1])) {
            
            return new Entity\Route(Str::snakeToPascal($rt[1]), "*");
        } else {
            
            if (!isset($rt[2])) {
                return null;
            }
            return new Entity\Route(Str::snakeToPascal($rt[2]), (string)$rt[1]);
        }
    }

    public function envToUsr(string $env): string
    {
        $arr = explode("/", trim($env, "/"));
        return count($arr) > 2 && str_contains($arr[2], "M_") ? str_replace("M_", "", $arr[2]) : "*";
    }

    
    public function pathToNamespace(string $path): string
    {
        $namespace = str_replace('/', '\\', trim($path, "/"));
        return ucwords($namespace, '\\');
    }

    
    public function pathToEnv(string $path): ?array
    {
        $path = trim($path, "/");
        $explode = explode("/", $path);
        if (count($explode) == 1) {
            return null;
        }
        $name = array_pop($explode);
        $env = "/" . implode("/", $explode);
        return ["name" => $name, "env" => $env];
    }

    
    public function userToEnv(int|string|null $userId = null): string
    {
        if (!$userId || $userId == "*") {
            return self::MAIN;
        }
        return sprintf(self::USR, $userId);
    }

    
    public function getType(string $env): string
    {
        $exp = explode("/", trim($env, "/"));
        $count = count($exp);
        if ($count == 2) {
            return end($exp);
        } elseif ($count == 3) {
            return $exp[1];
        }
        throw new RuntimeException("wrong plugin type");
    }

    public function getUsr(): string
    {
        $host = Context::get(Request::class)->header("Host");
        return App::usr($host);
    }

    
    public function getEnv(): string
    {
        return App::env();
    }
}