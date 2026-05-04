<?php
declare (strict_types=1);

namespace App\Entity;

class PayEntity
{
    
    private int $type;

    
    private string $url;

    private array $option = [];

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getOption(): array
    {
        return $this->option;
    }

    public function setOption(array $option): void
    {
        $this->option = $option;
    }
}