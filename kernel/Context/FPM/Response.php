<?php
declare (strict_types=1);

namespace Kernel\Context\FPM;

use Kernel\Exception\NotFoundException;
use Kernel\Template\Template;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Response implements \Kernel\Context\Interface\Response
{
    
    private array $options = [];

    public function withCookie(string $key, string $value, int $expire): static
    {
        setcookie($key, $value, time() + $expire, "/");
        return $this;
    }

    public function withHeader(string $key, string $value): static
    {
        if ($key == "Status") {
            header("HTTP/1.1 {$value}");
            return $this;
        }
        header("{$key}:{$value}");
        return $this;
    }

    public function redirect(string $url): static
    {
        $this->options['type'] = \Kernel\Context\Interface\Response::TYPE_REDIRECT;
        $this->options['url'] = $url;
        return $this;
    }

    public function json(int $code = 200, string $message = "success", ?array $data = null, array $ext = []): static
    {
        $this->withHeader("Content-Type", "application/json;charset=utf-8");
        $this->options['type'] = \Kernel\Context\Interface\Response::TYPE_JSON;
        $json = ["code" => $code, "msg" => $message];
        foreach ($ext as $k => $v) {
            $json[$k] = $v;
        }
        if ($data !== null) {
            $json['data'] = $data;
        }
        $this->options['json'] = json_encode($json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        return $this;
    }

    public function render(string $template, ?string $title = null, array $data = [], string|array $path = BASE_PATH . "/app/View/"): static
    {
        $this->withHeader("Content-Type", "text/html; charset=utf-8");
        $this->options['type'] = \Kernel\Context\Interface\Response::TYPE_RENDER;
        $data['title'] = $title;
        $this->options['template'] = $template;
        $this->options['data'] = $data;
        $this->options['path'] = $path;
        return $this;
    }

    public function raw(string $data): static
    {
        $this->options['type'] = \Kernel\Context\Interface\Response::TYPE_RAW;
        $this->options['data'] = $data;
        return $this;
    }

    public function draw(): void
    {
        switch ($this->options['type']) {
            case self::TYPE_REDIRECT:
                $this->withHeader("location", $this->options['url']);
                exit;
            case self::TYPE_JSON:
                echo $this->options['json'];
                exit;
            case self::TYPE_RENDER:
                $render = Template::instance()->load($this->options['template'], $this->options['data'], $this->options['path']);
                echo $render;
                exit;
            case self::TYPE_RAW:
                echo $this->options['data'];
                exit;
        }
    }

    public function getOptions(?string $key = null): mixed
    {
        if ($key) {
            return $this->options[$key] ?? null;
        }
        return $this->options;
    }

    public function end(): static
    {
        $this->options['forced_end'] = true;
        return $this;
    }
}