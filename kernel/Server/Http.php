<?php
declare (strict_types=1);

namespace Kernel\Server;

use Kernel\Annotation\Collector;
use Kernel\Component\Singleton;
use Kernel\Constant\Exception;
use Kernel\Container\Di;
use Kernel\Context\App;
use Kernel\Context\Interface\Request;
use Kernel\Context\Interface\Response;
use Kernel\Exception\NotFoundException;
use Kernel\Language\Entity\Language;
use Kernel\Plugin\Const\Plugin as PGC;
use Kernel\Plugin\Const\Point;
use Kernel\Plugin\Plugin;
use Kernel\Plugin\Usr;
use Kernel\Util\Context;
use Kernel\Util\Route;

class Http
{

    use Singleton;

    
    public function call(Request $request): mixed
    {
        
        Context::set(Language::class, \Kernel\Language\Language::instance()->getPreferredLanguage($request));
        if (($hook = Plugin::instance()->hook(Usr::MAIN, Point::HTTP_REQUEST_ENTER, PGC::HOOK_TYPE_HTTP, $request, Context::get(Response::class))) instanceof Response) return $hook;
        $_env = App::env();
        if (($hook = Plugin::instance()->hook($_env, Point::HTTP_REQUEST_START, PGC::HOOK_TYPE_HTTP, $request, Context::get(Response::class))) instanceof Response) return $hook;
        $uri = $request->uri();

        $method = $request->method();
        $route = $uri;

        if (!Route::has($uri, $method)) { 
            if (($hook = Plugin::instance()->hook($_env, Point::HTTP_NOT_FOUND, PGC::HOOK_TYPE_HTTP, $request, Context::get(Response::class))) instanceof Response) return $hook;
            throw new NotFoundException(Exception::NOT_FOUND);
        }

        $router = clone(Route::get($route, $method));
        $namespace = $router->class();
        $action = $router->action();  
        $router->setAction($action);
        $router->setRoute($uri);

        Context::set(\Kernel\Context\Interface\Route::class, $router);

        if (!class_exists($namespace)) {
            if (($hook = Plugin::instance()->hook($_env, Point::HTTP_NOT_FOUND, PGC::HOOK_TYPE_HTTP, $request, Context::get(Response::class))) instanceof Response) return $hook;
            throw new NotFoundException(Exception::NOT_FOUND);
        }

        $controller = new $namespace;

        if (!method_exists($controller, $action)) {
            if (($hook = Plugin::instance()->hook($_env, Point::HTTP_NOT_FOUND, PGC::HOOK_TYPE_HTTP, $request, Context::get(Response::class))) instanceof Response) return $hook;
            throw new NotFoundException(Exception::NOT_FOUND);
        }

        Collector::instance()->classParse($controller, function (\ReflectionAttribute $attribute) {
            $attribute->newInstance();
        });

        Collector::instance()->methodParse($controller, $action, function (\ReflectionAttribute $attribute) {
            $attribute->newInstance();
        });

        $response = Context::get(Response::class);
        $forcedEnd = $response->getOptions("forced_end");
        if ($forcedEnd) {
            return $response;
        }

        Di::instance()->inject($controller);
        
        $parameters = Collector::instance()->getMethodParameters($controller, $action, $request->get());

        if (($hook = Plugin::instance()->hook($_env, Point::HTTP_REQUEST_CONTROLLER, PGC::HOOK_TYPE_HTTP, $router, $request, Context::get(Response::class))) instanceof Response) return $hook;
        return call_user_func_array([$controller, $action], $parameters);
    }
}