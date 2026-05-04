<?php
declare (strict_types=1);

namespace App\Service\User\Bind;

use App\Entity\Site\NginxInfo;
use App\Model\Config;
use App\Model\User;
use Kernel\Annotation\Inject;
use Kernel\Cache\Variable;
use Kernel\Container\Memory;
use Kernel\Context\App;
use Kernel\Context\Interface\Request;
use Kernel\Context\Interface\Route;
use Kernel\Database\Db;
use Kernel\Dns\Dns;
use Kernel\Exception\JSONException;
use Kernel\Exception\NotFoundException;
use Kernel\Exception\ViewException;
use Kernel\Plugin\Const\Point;
use Kernel\Plugin\Plugin;
use Kernel\Plugin\Theme;
use Kernel\Plugin\Usr;
use Kernel\Util\Arr;
use Kernel\Util\Context;
use Kernel\Util\Date;
use Kernel\Util\File;
use Kernel\Util\Shell;
use Kernel\Util\SSL;
use Kernel\Util\Url;
use Kernel\Util\UserAgent;
use Kernel\Util\Verify;
use Kernel\Plugin\Const\Plugin as PGN;

class Site implements \App\Service\User\Site
{

    private const EFFECTIVE_WHITELIST = [
        [\App\Controller\User\API\Pay\PayOrder::class, "async"]
    ];

    #[Inject]
    private \App\Service\Common\Config $config;

    
    public function getNginxInfo(string $host): NginxInfo
    {
        return new NginxInfo(
            host: $host,
            pem: str_replace("*", "_", BASE_PATH . "config/nginx/{$host}/ssl.pem"),
            key: str_replace("*", "_", BASE_PATH . "config/nginx/{$host}/ssl.key"),
            conf: str_replace("*", "_", BASE_PATH . "config/nginx/{$host}.conf"),
            path: str_replace("*", "_", BASE_PATH . "config/nginx/{$host}")
        );
    }

    public function bind(int $themePage, string $template, array &$data): array
    {
        
        $request = Context::get(Request::class);
        $data['request']['url'] = $request->url();
        $this->setTemplateData($data);

        $templatePath = BASE_PATH . "/app/View/User/";
        $theme = $data['site']['pc_theme'];

        if (UserAgent::isMobile($request->header("UserAgent"))) {
            $theme = $data['site']['mobile_theme'];
        }

        if ($theme != "default") {
            $env = App::env();
            $themeEntity = Theme::instance()->getTheme($theme, $env);
            if ($themeEntity && key_exists($themePage, $themeEntity->theme)) {
                $templatePath = BASE_PATH . "{$env}/{$theme}/Theme/";
                $template = $themeEntity->theme[$themePage];
                $data['plugin'] = Variable::inst()->getOrNotCallback(\Kernel\Plugin\Entity\Plugin::class, function () use ($env, $theme) {
                    return Plugin::inst()->getPlugin($theme, $env);
                });
                if (file_exists($templatePath . "/Helper.php")) {
                    $class = str_replace("/", "\\", ucfirst(trim($env, "/")) . "/" . ucfirst($theme)) . "\\Theme\\Helper";
                    $data['__theme_helper_class'] = $class;
                }
            }
        }
        return ["template" => $template, "data" => $data, "templatePath" => $templatePath];
    }

    public function setTemplateData(array &$data): void
    {
        
        $request = Context::get(Request::class);
        $host = (string)$request->header("Host");
        $user = \App\Model\Site::getUser($host);
        if ($user) {
            $values = $this->config->getUserConfig("site");
            if (empty($values)) {
                throw new ViewException("商家没有设置网站信息");
            }
            $data['site'] = (new \App\Entity\Config\Site($values))->toArray();
        } else {
            $data['site'] = (new \App\Entity\Config\Site($this->config->getMainConfig("site")))->toArray();
        }
    }

    public function effective(): bool
    {
        return true; 
    }

    public function getMainDomains(): array
    {
        $pull = $this->config->getMainConfig("site");
        return Arr::strToList((string)$pull['domains'], "\n");
    }

    public function add(User $user, int $type, string $domain, string $subdomain = "", string $pem = "", string $key = ""): void
    {
        $group = $user->group;
        if (!$group || $group->is_merchant == 0) {
            throw new JSONException("用户不是商家");
        }
        $config = Config::main("subdomain");
        $certInfo = [];

        if ($type === \App\Const\Site::TYPE_SUBDOMAIN) { 
            if (!isset($config['subdomain'])) {
                throw new JSONException("系统没有配置主域名，请联系客服处理");
            }
            $subs = explode("\n", trim($config['subdomain']));
            if (!in_array($domain, $subs)) {
                throw new JSONException("未找到该主域名");
            }
            Verify::isBlank($subdomain, "域名前缀不能为空");

            if ($subdomain == "www" || $subdomain == "*") {
                throw new JSONException("域名前缀不能为www或*");
            }

            $domain = $subdomain . "." . $domain;
        } else {
            if (!isset($config['dns_status']) || $config['dns_status'] != 1) {
                throw new JSONException("系统未启用独立域名功能");
            }

            $certInfo = SSL::inst()->getCertInfo($pem);

            if (!$certInfo) {
                throw new JSONException("您提供的SSL证书不是一个有效的证书");
            }

            if (strtotime($certInfo['expire']) < time()) {
                throw new JSONException("您的SSL证书已过期");
            }
        }

        if (!filter_var($domain, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) && !preg_match("/^(\*\.)?([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}$/", $domain)) {
            throw new JSONException("该域名无效");
        }

        if (\App\Model\Site::where("host", $domain)->orWhere("host", Url::getWildcard($domain))->exists()) {
            throw new JSONException("该域名已被他人使用");
        }

        $proxyPass = App::$cli ? "http://127.0.0.1:" . \Kernel\Util\Config::get("cli-server.port") : $config['nginx_fpm_url'];

        $nginxInfo = $this->getNginxInfo($domain);
        $nginxConf = $this->getNginxProxyConfig($nginxInfo, $proxyPass);

        Plugin::instance()->unsafeHook(Usr::inst()->getEnv(), Point::SERVICE_SITE_ADD_BEFORE, PGN::HOOK_TYPE_PAGE, $user, $type, $domain, $pem, $key, $proxyPass, $nginxInfo, $nginxConf);

        try {
            $site = Db::transaction(function () use ($nginxInfo, $key, $certInfo, $pem, $type, $domain, $user, $nginxConf) {
                $site = new \App\Model\Site();
                $site->user_id = $user->id;
                $site->host = $domain;
                $site->create_time = Date::current();
                $site->type = $type;
                $site->status = 1;
                if ($type == \App\Const\Site::TYPE_DOMAIN) {
                    $site->ssl_expire_time = $certInfo['expire'];
                    $site->ssl_domain = $certInfo['domain'];
                    $site->ssl_issuer = $certInfo['issuer'];
                    
                    if (!File::write($nginxInfo->pem, $pem) || !File::write($nginxInfo->key, $key)) {
                        throw new JSONException("证书写入失败，请联系客服");
                    }
                    if (!File::write($nginxInfo->conf, $nginxConf)) {
                        throw new JSONException("配置环境写入失败，请联系客服");
                    }
                }
                $site->save();
                return $site;
            });

            Plugin::instance()->unsafeHook(Usr::inst()->getEnv(), Point::SERVICE_SITE_ADD_AFTER, PGN::HOOK_TYPE_PAGE, $site, $user, $type, $domain, $pem, $key, $proxyPass, $nginxInfo, $nginxConf);
        } catch (\Throwable $e) {
            if ($type == \App\Const\Site::TYPE_DOMAIN) {
                File::remove($nginxInfo->pem, $nginxInfo->key, $nginxInfo->conf);
                rmdir($nginxInfo->path);
            }
            throw $e;
        }

        if ($type == \App\Const\Site::TYPE_DOMAIN) {
            
            Shell::inst()->exec("sudo nginx -s reload");
        }
    }

    
    public function del(string $domain): void
    {
        
        $site = \App\Model\Site::where("host", $domain)->first();
        if (!$site) {
            throw new JSONException("该域名不存在");
        }

        Plugin::instance()->unsafeHook(Usr::inst()->getEnv(), Point::SERVICE_SITE_DEL_BEFORE, PGN::HOOK_TYPE_PAGE, $site, $domain);

        if ($site->type == \App\Const\Site::TYPE_DOMAIN) {
            $nginxInfo = $this->getNginxInfo($domain);
            File::remove($nginxInfo->pem, $nginxInfo->key, $nginxInfo->conf);
            rmdir($nginxInfo->path);
            Shell::inst()->exec("sudo nginx -s reload");
        }

        $site->delete();

        Plugin::instance()->unsafeHook(Usr::inst()->getEnv(), Point::SERVICE_SITE_DEL_AFTER, PGN::HOOK_TYPE_PAGE, $site, $domain);
    }

    public function modifyCertificate(string $domain, string $pem, string $key): void
    {
        $site = \App\Model\Site::where("host", $domain)->first();
        if (!$site) {
            throw new JSONException("该域名不存在");
        }

        if ($site->type != \App\Const\Site::TYPE_DOMAIN) {
            throw new JSONException("非独立域名无法修改证书");
        }

        $certInfo = SSL::inst()->getCertInfo($pem);

        if (!$certInfo) {
            throw new JSONException("您提供的SSL证书不是一个有效的证书");
        }

        $nginxInfo = $this->getNginxInfo($domain);

        if (File::read($nginxInfo->pem) == $pem && File::read($nginxInfo->key) == $key) {
            throw new JSONException("证书没有更改，无需保存");
        }

        $site->ssl_expire_time = $certInfo['expire'];
        $site->ssl_domain = $certInfo['domain'];
        $site->ssl_issuer = $certInfo['issuer'];

        if (!File::write($nginxInfo->pem, $pem) || !File::write($nginxInfo->key, $key)) {
            throw new JSONException("证书写入失败，请联系客服");
        }
        Plugin::instance()->unsafeHook(Usr::inst()->getEnv(), Point::SERVICE_SITE_MODIFY_CERTIFICATE_BEFORE, PGN::HOOK_TYPE_PAGE, $site, $domain, $pem, $key);
        $site->save();
        Plugin::instance()->unsafeHook(Usr::inst()->getEnv(), Point::SERVICE_SITE_MODIFY_CERTIFICATE_AFTER, PGN::HOOK_TYPE_PAGE, $site, $domain, $pem, $key);
        Shell::inst()->exec("sudo nginx -s reload");
    }

    public function getCertificate(string $domain): array
    {
        $site = \App\Model\Site::where("host", $domain)->first();
        if (!$site) {
            throw new JSONException("该域名不存在");
        }

        if ($site->type != \App\Const\Site::TYPE_DOMAIN) {
            throw new JSONException("非独立域名禁止读取证书");
        }

        $nginxInfo = $this->getNginxInfo($domain);

        return ["pem" => File::read($nginxInfo->pem), "key" => File::read($nginxInfo->key)];
    }

    public function getConfig(string $key, ?string $userId = null): array
    {
        
        $request = Context::get(Request::class);
        if ($request) {
            
            $host = $request->header("Host");
            $user = \App\Model\Site::getUser((string)$host);
            $cacheKey = "config_find_sql_{$key}" . ($user ? "_{$user->id}" : "");
            if (Memory::instance()->has($cacheKey)) {
                return Memory::instance()->get($cacheKey);
            }
            if ($user) {
                $config = Config::where("key", $key)->where("user_id", $user->id)->first();
            } else {
                $config = Config::where("key", $key)->whereNull("user_id")->first();
            }
            if (!$config) {
                return [];
            }
            $json = json_decode($config->value, true);
            Memory::inst()->set($cacheKey, $json);
            return $json;
        } else {
            
            if (is_numeric($userId)) {
                $config = Config::where("key", $key)->where("user_id", $userId)->first();
            } else {
                $config = Config::where("key", $key)->whereNull("user_id")->first();
            }
            if (!$config) {
                return [];
            }
            return json_decode($config->value, true);
        }
    }

    
    public function getDnsRecord(string $host): array
    {
        $cache = str_replace("*", "_", BASE_PATH . "/runtime/dns/{$host}");
        $overTime = File::getChangeOverTime($cache);
        if ($overTime !== false && $overTime < 60) {
            return json_decode(File::read($cache), true) ?: [];
        }
        $type = $this->config->getMainConfig("subdomain.dns_type") == 1 ? "CNAME" : "A";
        $dns = Dns::inst()->getRecord($host, $type);
        $arr = [];
        foreach ($dns as $item) {
            $arr[] = $item->value;
        }
        File::write($cache, json_encode($arr));
        return $arr;
    }

    public function getNginxProxyConfig(NginxInfo $nginxInfo, string $proxyPass, ?string $conf = null): string
    {
        $nginxConf = $conf ?? $this->config->getMainConfig("subdomain.nginx_conf");
        $nginxConf = str_replace('${server_name}', $nginxInfo->host, $nginxConf); 
        $nginxConf = str_replace('${ssl_certificate}', $nginxInfo->pem, $nginxConf); 
        $nginxConf = str_replace('${ssl_certificate_key}', $nginxInfo->key, $nginxConf); 
        
        return str_replace('${proxy_pass}', $proxyPass, $nginxConf);
    }
}