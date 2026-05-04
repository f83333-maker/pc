<?php
declare (strict_types=1);

namespace Kernel\Exception;

class RedirectException extends \Exception
{
    private string $url;
    private int $time = 0;

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function getTime(): int
    {
        return $this->time;
    }

    public function setTime(int $time): self
    {
        $this->time = $time;
        return $this;
    }
}