<?php
declare (strict_types=1);

namespace App\View;

use App\Model\User;
use App\Service\Common\Config;
use Kernel\Annotation\Inject;
use Kernel\Component\Singleton;
use Kernel\Container\Di;
use Kernel\Context\App;
use Kernel\Context\Interface\Response;
use Kernel\Exception\NotFoundException;
use Kernel\Plugin\Const\Plugin as PGN;
use Kernel\Plugin\Plugin;
use Kernel\Plugin\Usr;
use Kernel\Util\Context;
use Kernel\Util\Str;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class Helper extends AbstractExtension
{

    use Singleton;

    #[Inject]
    private Config $config;

    
    public function getFunctions(): array
    {
        return [
            new TwigFunction('js', [$this, 'loadJs']),
            new TwigFunction('css', [$this, 'loadCss']),
            new TwigFunction('icon', [$this, 'loadIcon']),
            new TwigFunction('ready', [$this, 'ready']),
            new TwigFunction('var', [$this, 'setScriptVar']),
            new TwigFunction('i18n', [$this, 'i18n']),
            new TwigFunction('hook', [$this, 'hook']),
            new TwigFunction('multi_hook', [$this, 'multiHook']),
            new TwigFunction('user_env', [$this, 'userEnv']),
            new TwigFunction('usr', [$this, 'usr']),
            new TwigFunction('env', [$this, 'env']),
            new TwigFunction('point', [$this, 'point']),
            new TwigFunction('ccy', [$this, 'currency']),
            new TwigFunction('plugin_backstage_route', [$this, 'getPluginBackstageRoute']),
            new TwigFunction('m_config', [$this, 'getMainConfig']),
        ];
    }

    
    public function getMainConfig(string $key): mixed
    {
        return $this->config->getMainConfig($key);
    }

    public function getPluginBackstageRoute(string $name, string $uri = ""): string
    {
        $user = Context::get(User::class);
        return "/plugin/" . ($user ? "{$user->id}/" : "") . Str::camelToSnake($name) . "/" . trim($uri, "/");
    }

    public function currency(): string
    {
        return Di::instance()->make(Config::class)->getCurrency()->symbol;
    }

    
    public function hook(string $env, int $point, int $type = PGN::HOOK_TYPE_PAGE, ...$arg): array|string|bool|Response
    {
        return Plugin::instance()->hook($env, $point, $type, ...$arg);
    }

    public function multiHook(array $users, int $point, int $type = PGN::HOOK_TYPE_PAGE, ...$arg): array|string|bool|Response
    {
        return Plugin::instance()->multiHook($users, $point, $type, ...$arg);
    }

    public function env(bool $forceSys = false): string
    {
        if ($forceSys) {
            return Usr::MAIN;
        }
        return Usr::inst()->getEnv();
    }

    public function userEnv(?int $userId): string
    {
        return Usr::inst()->userToEnv($userId);
    }

    public function usr(): string
    {
        return Usr::inst()->getUsr();
    }

    public function point(string $name): int
    {
        return constant("Kernel\\Plugin\\Const\\Point::" . $name);
    }

    function loadCss(array|string $resource, array|string|null $backup = null, bool $cdn = true): string
    {
        if (App::$debug && $backup !== null) {
            $resource = $backup;
        }
        $res = '';
        $debugRandom = App::$debug ? "&debug=" . Str::generateRandStr(8) : "";
        $cdnSupport = $cdn ? 'class="cdn-support"' : '';
        if (is_array($resource)) {
            foreach ($resource as $item) {
                $res .= sprintf('<link rel="stylesheet" href="%s" ' . $cdnSupport . '>', $item . '?v=' . App::$version . $debugRandom);
            }
        } else {
            $res = sprintf('<link rel="stylesheet" href="%s" ' . $cdnSupport . '>', $resource . '?v=' . App::$version . $debugRandom);
        }
        return $res;
    }

    function loadJs(array|string $resource, array|string|null $backup = null, bool $cdn = true): string
    {
        if (App::$debug && $backup !== null) {
            $resource = $backup;
        }
        $res = '';
        $debugRandom = App::$debug ? "&debug=" . Str::generateRandStr(8) : "";
        $cdnSupport = $cdn ? ' class="cdn-support"' : '';
        if (is_array($resource)) {
            foreach ($resource as $item) {
                $res .= sprintf('<script src="%s" ' . $cdnSupport . '></script>', $item . (str_contains($item, "?") ? "&" : "?") . 'v=' . App::$version . $debugRandom);
            }
        } else {
            $res = sprintf('<script src="%s" ' . $cdnSupport . '></script>', $resource . (str_contains($resource, "?") ? "&" : "?") . 'v=' . App::$version . $debugRandom);
        }
        return $res;
    }

    
    public function loadIcon(string $icon, string ...$class): string
    {
        return '<svg class="mcy-icon ' . (implode(" ", $class)) . '" aria-hidden="true"><use xlink:href="#' . $icon . '"></use></svg>';
    }

    
    public function ready(string $resource, array $variable = []): string
    {
        $var = '';
        foreach ($variable as $key => $value) {
            $var .= "setVar('{$key}' , {$this->getValue($value)});";
        }
        return '<script>' . $var . 'ready("' . $resource . (str_contains($resource, "?") ? "&" : "?") . 'v=' . App::$version . (App::$debug ? "&debug=" . Str::generateRandStr(8) : '') . '");</script>';
    }

    private function getValue(mixed $value): string|bool|null
    {
        if (is_numeric($value) || is_bool($value)) {
            
            $value = var_export($value, true);
        } elseif (is_array($value)) {
            
            $value = json_encode($value);
        } else {
            
            $value = addslashes($value);
            $value = "\"$value\"";
        }
        return $value;
    }

    public function setScriptVar(array $vars): string
    {
        $str = "<script>";
        foreach ($vars as $name => $var) {
            $str .= "setVar(\"{$name}\",{$this->getValue($var)});";
        }
        return $str . "</script>";
    }

    public function i18n(mixed $text): string
    {
        return \Kernel\Language\Language::instance()->output((string)$text);
    }

    public function compressCss(string $css): string
    {
        
        $css = preg_replace('!/\*.*?\*/!s', '', $css);
        
        $css = preg_replace('/\s*([{}|:;,])\s+/', '$1', $css);
        
        $css = preg_replace('/\s\s+(.*)/', '$1', $css);
        
        $css = str_replace(';}', '}', $css);
        return trim($css);
    }
}