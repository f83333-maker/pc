<?php
declare (strict_types=1);

namespace Kernel\Waf;

use Kernel\Cache\Cache;
use Kernel\Component\Singleton;
use Kernel\Exception\RuntimeException;

class Firewall
{

    use Singleton;

    private array $rule = [];

    private ?\HTMLPurifier $HTMLPurifier = null;

    private Cache $cache;

    public function __construct()
    {
        if (!is_dir(BASE_PATH . "/runtime/waf")) mkdir(BASE_PATH . "/runtime/waf", 0777, true);
        $this->cache = new Cache(BASE_PATH . "runtime/waf/PACKET", Cache::OPTIONS_STRING);
    }

    private function HTMLPurifierInit(): void
    {
        if ($this->HTMLPurifier) {
            return;
        }

        $config = \HTMLPurifier_Config::createDefault();

        $config->set('Cache.SerializerPath', BASE_PATH . "/runtime/waf"); 
        $config->set('Cache.SerializerPermissions', 0755);
        $config->set('Cache.DefinitionImpl', 'Serializer');

        $config->set('HTML.DefinitionID', 'firewall.html');
        $config->set('HTML.DefinitionRev', 1);

        $config->set('Filter.Custom', [IgnoreStyleTagFilter::make()]);

        $config->getDefinition('URI')->addFilter(URISchemeFilter::make(), $config);

        if ($def = $config->maybeGetRawHTMLDefinition()) {
            $def->addElement(
                'video',
                'Block',
                'Flow',
                'Common',
                array(
                    'poster' => 'URI',
                    'controls' => 'Bool#controls',
                    'width' => 'Text',
                    'height' => 'Text',
                    'src' => 'URI'
                )
            );
            $def->addElement(
                'source',
                'Block',
                'Flow',
                'Common',
                array(
                    'src*' => 'URI',
                    'type' => 'Text'
                )
            );
            $def->addElement(
                'marquee',
                'Block',
                'Flow',
                'Common',
                array(
                    'behavior' => 'Enum#scroll,slide,alternate',
                    'direction' => 'Enum#left,right,up,down',
                    'scrollamount' => 'Number',
                    'scrolldelay' => 'Number',
                    'loop' => 'Number',
                    'bgcolor' => 'Text',
                    'width' => 'Text',
                    'height' => 'Text',
                    'style' => 'Text'
                )
            );
            $def->addElement(
                'iframe',
                'Block',
                'Flow',
                'Common',
                array(
                    'src*' => 'URI',
                    'scrolling' => 'Enum#yes,no,auto',
                    'border' => 'Text',
                    'frameborder' => 'Text',
                    'framespacing' => 'Text',
                    'allowfullscreen' => 'Bool',
                    'sandbox' => 'Text',
                    'width' => 'Text',
                    'height' => 'Text',
                    'allow' => 'Text'
                )
            );
            $def->addAttribute('div', 'data-w-e-type', 'Text');
            $def->addAttribute('div', 'data-w-e-is-void', 'Bool');
            $def->addAttribute('a', 'target', 'Text');
            $def->addAttribute('img', 'width', 'Text');
            $def->addAttribute('img', 'height', 'Text');
        }

        $this->HTMLPurifier = new \HTMLPurifier($config);
    }

    public function check(callable $callable): void
    {
        $path = BASE_PATH . "/kernel/Waf/Rule";
        $this->rule["POST"] = json_decode(file_get_contents($path . "/post.json"), true);
        $this->rule["URL"] = json_decode(file_get_contents($path . "/url.json"), true);
        $this->rule["ARG"] = json_decode(file_get_contents($path . "/args.json"), true);
        $this->rule["COOKIE"] = json_decode(file_get_contents($path . "/cookie.json"), true);

        $getPara = urldecode(http_build_query($_GET));
        foreach ($this->rule["ARG"] as $key => $value) {
            if (preg_match("#" . $value[1] . "#i", $getPara)) {
                $callable($value);
                return;
            }
        }

        foreach ($this->rule["URL"] as $key => $value) {
            if (preg_match("#" . $value[1] . "#i", $getPara)) {
                $callable($value);
                return;
            }
        }

        $postPara = urldecode(http_build_query($_POST));
        foreach ($this->rule["POST"] as $key => $value) {
            if (preg_match("#" . $value[1] . "#i", $postPara)) {
                $callable($value);
                return;
            }
        }

        $cookiePara = urldecode(http_build_query($_COOKIE));
        foreach ($this->rule["COOKIE"] as $key => $value) {
            if (preg_match("#" . $value[1] . "#i", $cookiePara)) {
                $callable($value);
                return;
            }
        }
    }

    private function getCache(string $input): mixed
    {

        $this->HTMLPurifierInit();
        return $this->HTMLPurifier->purify(urldecode(str_replace("+", "%2B", $input)));

    }

    public function xssKiller(mixed $input): mixed
    {
        if (is_array($input)) {
            $cleanedArray = [];
            foreach ($input as $key => $value) {
                if (is_string($value)) {

                    $cleanedArray[$key] = $this->getCache($value);
                } elseif (is_array($value)) {
                    $cleanedArray[$key] = $this->xssKiller($value);
                } else {
                    $cleanedArray[$key] = $value;
                }
            }
            return $cleanedArray;
        } elseif (is_string($input)) {

            return $this->getCache($input);
        } else {
            return $input;
        }
    }

    public function filterContent(mixed $input, int $flags): mixed
    {
        if (is_null($input)) {
            return null;
        }

        if (is_array($input)) {
            $cleanedArray = [];
            foreach ($input as $key => $value) {
                if (is_string($value)) {
                    $cleanedArray[$key] = $this->filter($value, $flags);
                } elseif (is_array($value)) {
                    $cleanedArray[$key] = $this->filterContent($value, $flags);
                } else {
                    $cleanedArray[$key] = $value;
                }
            }
            return $cleanedArray;
        } else {
            return $this->filter($input, $flags);
        }
    }

    public function filter(mixed $content, int $flags): mixed
    {
        if (is_string($content)) {
            $content = trim($content);
            if ($flags & Filter::STRING_UNSIGNED) {
                $content = htmlspecialchars(strip_tags($content), ENT_QUOTES, 'UTF-8');
            }
        }
        if ($flags & Filter::INTEGER) {
            $content = (int)$content;
        }
        if ($flags & Filter::FLOAT) {
            $content = (float)$content;
        }
        if ($flags & Filter::BOOLEAN) {
            $content = (bool)$content;
        }
        return $content;
    }
}