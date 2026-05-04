<?php
declare (strict_types=1);

namespace Kernel\Context;

class Command implements \Kernel\Context\Interface\Command
{

    private string $command;
    private string $class;
    private string $method;
    private mixed $extend = null;

    private ?string $name = null;
    private ?string $desc = null;

    public function __construct(string $command, string $class, string $method, mixed $extend = null , ?string $name = null , ?string $desc = null)
    {
        $this->command = $command;
        $this->class = $class;
        $this->method = $method;
        $this->extend = $extend;
        $this->name = $name;
        $this->desc = $desc;
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getExtend(): mixed
    {
        return $this->extend;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDesc(): ?string
    {
        return $this->desc;
    }
}