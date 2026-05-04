<?php
declare (strict_types=1);

namespace App\Controller\Admin\API\Plugin;

use App\Controller\Admin\Base;
use App\Interceptor\Admin;
use App\Interceptor\PostDecrypt;
use Kernel\Annotation\Interceptor;
use Kernel\Context\Interface\Response;
use Kernel\Exception\JSONException;
use Kernel\Exception\RuntimeException;
use Kernel\Plugin\Const\Point;
use Kernel\Plugin\Query;
use Kernel\Plugin\Usr;
use Kernel\Waf\Filter;

#[Interceptor(class: [PostDecrypt::class, Admin::class], type: Interceptor::API)]
class Plugin extends Base
{

    public function get(): Response
    {
        $state = $this->request->post("state");
        $state = ($state === "" || $state === null) ? -1 : (int)$state;
        $query = new Query(Usr::MAIN);
        $query->setState($state);
        $query->setKeyword((string)$this->request->post("keyword"));
        $query->setType((int)$this->request->post("equal-type"));
        $query->setPaginate((int)$this->request->post("page"), (int)$this->request->post("limit"));
        return $this->json(data: $query->list());
    }

    public function getLogs(string $hash): Response
    {
        $name = $this->request->post("name");
        $data = \Kernel\Plugin\Plugin::instance()->getLogs($hash, $name, Usr::MAIN)->toArray();
        return $this->json(data: $data);
    }

    public function icon(string $name): Response
    {
        $path = realpath(BASE_PATH . Usr::MAIN . "/" . $name . "/Icon.ico");

        if (!$path) {
            throw new JSONException("ICON不存在");
        }
        $file = fopen($path, 'rb');
        if (!$file) {
            throw new JSONException("无法读取文件");
        }
        $image = stream_get_contents($file);
        fclose($file);

        return $this->response->raw($image)
            ->withHeader("Content-Type", "image/png")
            ->withHeader("Cache-Control", "public, max-age=31536000")
            ->withHeader("Pragma", "public, max-age=31536000")
            ->withHeader("Expires", gmdate("D, d M Y H:i:s", time() + 31536000) . " GMT")
            ->withHeader("Date", gmdate("D, d M Y H:i:s", time() + 31536000) . " GMT");
    }

    public function clearLog(): Response
    {
        $name = $this->request->post("name");
        \Kernel\Plugin\Plugin::instance()->clearLog($name, Usr::MAIN);
        return $this->json();
    }

    public function setCfg(string $name): Response
    {
        $post = $this->request->post(flags: Filter::NORMAL);
        \Kernel\Plugin\Plugin::inst()->instantHook($name, Usr::MAIN, Point::APP_SAVE_CFG_BEFORE, $post);
        \Kernel\Plugin\Plugin::inst()->setConfig($name, Usr::MAIN, $post);
        \Kernel\Plugin\Plugin::inst()->instantHook($name, Usr::MAIN, Point::APP_SAVE_CFG_AFTER, $post);
        return $this->json();
    }

    public function setSysCfg(string $name): Response
    {
        \Kernel\Plugin\Plugin::instance()->setSystemConfig($name, Usr::MAIN, $this->request->post());
        return $this->json();
    }

    public function start(): Response
    {
        $name = $this->request->post("name");
        \Kernel\Plugin\Plugin::instance()->start($name, Usr::MAIN);
        return $this->json();
    }

    public function stop(): Response
    {
        $name = $this->request->post("name");
        \Kernel\Plugin\Plugin::instance()->stop($name, Usr::MAIN);
        return $this->json();
    }

    public function restart(): Response
    {
        $name = $this->request->post("name");
        \Kernel\Plugin\Plugin::instance()->stop($name, Usr::MAIN);
        \Kernel\Plugin\Plugin::instance()->start($name, Usr::MAIN);
        return $this->json();
    }
}