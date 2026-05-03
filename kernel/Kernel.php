<?php
declare(strict_types=1);

use Illuminate\Database\Capsule\Manager;
use Kernel\Annotation\Collector;
use Kernel\Consts\Base;
use Kernel\Container\Di;
use Kernel\Context\Request;
use Kernel\Exception\NotFoundException;
use Kernel\Plugin\Hook;
use Kernel\Util\Context;
use Kernel\Util\Plugin;
use Kernel\Util\RequestLogger;
use Kernel\Waf\Firewall;


date_default_timezone_set("Asia/Shanghai");
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);
ini_set('display_errors', '0');
const BASE_PATH = __DIR__ . "/../";
define("DEBUG", false);
require(BASE_PATH . '/vendor/autoload.php');
require("Helper.php");

\Kernel\Context\App::$debug = DEBUG;
\Kernel\Context\App::$version = config('app')['version'];
\Kernel\Context\App::$startTime = (int)microtime(true);
//define
define("BASE_APP_SERVER", match ((int)config("store")['server']) {
    0 => App\Service\App::MAIN_SERVER,
    1 => App\Service\App::STANDBY_SERVER1,
    2 => App\Service\App::STANDBY_SERVER2,
    3 => App\Service\App::GENERAL_SERVER
});
define("APP_VERSION", config('app')['version']);

//session
session_name("ACG-SHOP");
//session_start();
//session_write_close();
try {
    preg_match('/\/item\/(\d+)/', $_GET['s'] ?? "/", $_item);
    preg_match('/\/cat\/(\d+|recommend)/', $_GET['s'] ?? "/", $_cat);

    if (isset($_item[1]) && is_numeric($_item[1])) {
        $_GET['s'] = "/user/index/item";
        $_GET['mid'] = $_item[1];
    }

    if (isset($_cat[1]) && (is_numeric($_cat[1]) || $_cat[1] == "recommend")) {
        $_GET['s'] = "/user/index/index";
        $_GET['cid'] = $_cat[1];
    }

    //waf install -> 2025-07-26
    $routePath = $_GET['s'] = $_GET['s'] ?? "/";
    Context::set(\Kernel\Context\Interface\Request::class, new Request());

    if (trim($routePath, "/") == 'admin') {
        header('location:' . "/admin/authentication/login");
        exit;
    }

    $s = explode("/", trim((string)$routePath, '/'));
    if ($s[0] == "" || (count($s) == 1 && $s[0] == "index.php")) {
        $s = ["user", "index", "index"]; // Default route
    }
    
    $fullRoute = "/" . implode("/", $s);
    Context::set(Base::ROUTE, $fullRoute);
    Context::set(Base::LOCK, (string)@file_get_contents(BASE_PATH . "/kernel/Install/Lock"));
    Context::set(Base::IS_INSTALL, file_exists(BASE_PATH . '/kernel/Install/Lock'));

    Context::set(Base::OPCACHE, extension_loaded("Zend OPcache") || extension_loaded("opcache"));
    Context::set(Base::STORE_STATUS, file_exists(BASE_PATH . "/kernel/Plugin.php"));

    if (Context::get(Base::STORE_STATUS)) {
        require("Plugin.php");
    }

    // If not installed and not currently on the install route, redirect to install
    $isInstall = Context::get(Base::IS_INSTALL);

    // FIX: 只要未安装且不是以 /install 开头的路径，就强制进入安装页面
    if (!$isInstall) {
        if (!str_starts_with($fullRoute, "/install")) {
            header('location: /install');
            exit;
        }
        // 如果是 /install，强制指向安装控制器，防止路由解析失败
        $s = explode("/", trim($fullRoute, '/'));
        if (count($s) == 1) {
            $s = ["install", "step"];
        } else {
            // /install/submit -> ["install", "submit"]
            array_shift($s);
            $s = array_merge(["install"], $s);
        }
    }

    $count = count($s);
    $controller = "App\\Controller";
    $ends = end($s);

    if (isset($s[0]) && strtolower($s[0]) == "plugin") {
        $controller = "App";
        Plugin::$currentControllerPluginName = ucfirst(trim((string)$s[1]));
    }

    foreach ($s as $j => $x) {
        if ($j == ($count - 1)) {
            break;
        }
        if (isset($s[0]) && strtolower($s[0]) == "plugin" && $j == 2) {
            $controller .= "\\Controller";
        }
        $controller .= '\\' . ucfirst(trim($x));
    }

    //参数
    $parameter = explode('.', $ends);
    //需要执行的方法
    $action = array_shift($parameter);
    //存储
    $_GET["_PARAMETER"] = Firewall::inst()->xssKiller($parameter);

    //初始化数据库 (仅在已安装时初始化，避免安装前因配置错误导致白屏)
    if ($isInstall) {
        $capsule = new Manager();
        $db_config = config('database');
        if (!empty($db_config)) {
            if (class_exists('PDO')) {
                $db_config['options'][PDO::ATTR_PERSISTENT] = true;
            }
            $capsule->addConnection($db_config);
            $capsule->setAsGlobal();
            $capsule->bootEloquent();
        }
    }

    //插件库
    if (Context::get(Base::STORE_STATUS) && $isInstall) {
        //插件初始化
        Hook::inst()->load();
        //插件初始化
        hook(\App\Consts\Hook::KERNEL_INIT);
    }


    //记录日志
    RequestLogger::logCurrentRequest(Context::get(\Kernel\Context\Interface\Request::class));

    //检测类是否存在
    if (!class_exists($controller)) {
        throw new NotFoundException("404 Not Found");
    }

    $controllerInstance = new $controller;

    //检测method是否存在
    if (!method_exists($controllerInstance, $action)) {
        throw new NotFoundException("404 Not Found");
    }


    Collector::instance()->classParse($controllerInstance, function (\ReflectionAttribute $attribute) {
        $attribute->newInstance();
    });

    Collector::instance()->methodParse($controllerInstance, $action, function (\ReflectionAttribute $attribute) {
        $attribute->newInstance();
    });

    //依赖注入
    Di::instance()->inject($controllerInstance);


    //参数注入
    $parameters = Collector::instance()->getMethodParameters($controllerInstance, $action, $_REQUEST);
    hook(\App\Consts\Hook::CONTROLLER_CALL_BEFORE, $controllerInstance, $action);
    $result = call_user_func_array([$controllerInstance, $action], $parameters);
    hook(\App\Consts\Hook::CONTROLLER_CALL_AFTER, $controllerInstance, $action, $result);
    hook(\App\Consts\Hook::HTTP_ROUTE_RESPONSE, $routePath, $result);


    if ($result === null) {
        return;
    }

    if (!is_scalar($result)) {
        header('content-type:application/json;charset=utf-8');
        echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    } else {
        header("Content-type: text/html; charset=utf-8");
        echo $result;
    }
} catch (Throwable $e) {
    if ($e instanceof NotFoundException) {
        exit(feedback("404 Not Found"));
    } elseif ($e instanceof \Kernel\Exception\ParameterMissException) {
        header('content-type:application/json;charset=utf-8');
        exit(json_encode(["code" => $e->getCode(), "msg" => $e->getMessage()], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    } elseif ($e instanceof \Kernel\Exception\JSONException) {
        header('content-type:application/json;charset=utf-8');
        exit(json_encode(["code" => $e->getCode(), "msg" => $e->getMessage()], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    } elseif ($e instanceof \Kernel\Exception\ViewException) {
        header("Content-type: text/html; charset=utf-8");
        exit(feedback($e->getFile() . "<br>" . $e->getMessage()));
    } else {
        exit(feedback($e->getFile() . ":" . $e->getLine() . "<br>" . $e->getMessage()));
    }
}
