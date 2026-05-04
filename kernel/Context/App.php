<?php
declare (strict_types=1);

namespace Kernel\Context;

use App\Model\Site;
use Hyperf\Context\ApplicationContext;
use Hyperf\Database\Exception\QueryException;
use Kernel\Console\Console;
use Kernel\Container\Di;
use Kernel\Container\Memory;
use Kernel\Context\Interface\Request;
use Kernel\Context\Interface\Response;
use Kernel\Database\Connection;
use Kernel\Database\Container;
use Kernel\Exception\HandleException;
use Kernel\Exception\JSONException;
use Kernel\Exception\NotFoundException;
use Kernel\Exception\RedirectException;
use Kernel\Exception\ServiceException;
use Kernel\Exception\ViewException;
use Kernel\Language\Language;
use Kernel\Plugin\Usr;
use Kernel\Session\Session;
use Kernel\Template\Template;
use Kernel\Util\Config;
use Kernel\Util\Context;
use Kernel\Util\File;
use Psr\Container\ContainerInterface;
use Throwable;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class App
{
    
    public static bool $cli = false;

    
    public static array $database = [];

    
    public static array $dependencies = [];

    
    public static array $session = [];

    
    public static bool $debug = false;

    public static string $version = "4.0.0";

    public static bool $opcache = false;

    public static string $lock = "";

    
    public static array $language = [];

    public static bool $install = false;

    public static int $startTime = 0;

    public static string $mEnv = "/app/Plugin";

    public static string $mode = "dev"; 

    public static bool $isCommand = false;

    
    public static function container(): void
    {
        Di::instance()->set(ContainerInterface::class, Container::class);
        Di::instance()->set(Session::class, App::$session["handler"]);
        ApplicationContext::setContainer(Di::instance()->get(ContainerInterface::class));
    }

    public static function error(Throwable $e, Response &$response): string
    {
        if ($e instanceof QueryException) {
            
            Connection::instance()->release();
        }

        if ($e instanceof RedirectException) {
            $response->withHeader("Content-Type", "text/html; charset=utf-8");
            $message = Template::instance()->redirect(Language::instance()->output($e->getMessage()), $e->getUrl(), $e->getTime());
        } elseif ($e instanceof NotFoundException) {
            $response->withHeader("Status", "404")->withHeader("Content-Type", "text/html; charset=utf-8");
            $message = Template::instance()->load("404.html");
        } elseif ($e instanceof ViewException) {
            $response->withHeader("Content-Type", "text/html; charset=utf-8");
            $message = Template::instance()->load("Msg.html", ["msg" => Language::instance()->output($e->getMessage())]);
        } elseif ($e instanceof JSONException || $e instanceof ServiceException || $e instanceof HandleException) {
            $response->withHeader("Content-Type", "application/json;charset=utf-8");
            $message = json_encode(["code" => $e->getCode(), "msg" => Language::instance()->output($e->getMessage())], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } else {
            $response->withHeader("Content-Type", "text/html; charset=utf-8");
            $message = Template::instance()->error($e);
        }

        return $message;
    }

    
    public static function route(): void
    {
        if (!file_exists(BASE_PATH . "/runtime/updated") && file_exists(BASE_PATH . "/runtime/route") && !App::$cli) {
            return;
        }
        Config::get("route");
        File::remove(BASE_PATH . "/runtime/updated");
    }

    public static function command(): void
    {
        if (!App::$cli) {
            return;
        }
        
        Config::get("command");
    }

    
    public static function usr(string $host): string
    {
        $_usrKey = "init_usr_{$host}";
        if (!self::$install) {
            return "*";
        }

        if (Memory::instance()->has($_usrKey)) {
            return Memory::instance()->get($_usrKey);
        }

        $user = Site::getUser($host);
        if (!$user) {
            return "*";
        }

        $usr = (string)$user->id;
        Memory::instance()->set($_usrKey, $usr);
        return $usr;
    }

    public static function env(): string
    {
        $_envKey = "init_env_plugin";

        if (!self::$install) {
            return Usr::MAIN;
        }

        if (Memory::instance()->has($_envKey)) {
            return Memory::instance()->get($_envKey);
        }

        $request = Context::get(Request::class);
        if (!$request) {
            return Usr::MAIN;
        }

        $uri = $request->uri();
        $url = explode("/", trim($uri, "/"));

        if (isset($url[0]) && $url[0] == "admin") {
            return Usr::MAIN;
        }

        $user = Site::getUser((string)$request->header("Host"));

        if (!$user) {
            return Usr::MAIN;
        }

        $env = "/usr/Plugin/M_" . $user?->id;
        Memory::instance()->set($_envKey, $env);
        return $env;
    }
}