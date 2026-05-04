<?php
declare (strict_types=1);

namespace Kernel\Validator;

class Rule
{
    
    protected string $title;

    protected array $rule = [];

    protected array $message = [];

    protected function addItem(string $name, mixed $rule = null, string $msg = ''): static
    {
        if ($rule || 0 === $rule) {
            $this->rule[$name] = $rule;
        } else {
            $this->rule[] = $name;
        }

        $this->message[] = $msg;

        return $this;
    }

    public function getRule(): array
    {
        return $this->rule;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getMsg(): array
    {
        return $this->message;
    }

    public function title($title): static
    {
        $this->title = $title;

        return $this;
    }

    public function __call($method, $args)
    {
        if ('is' == strtolower(substr($method, 0, 2))) {
            $method = substr($method, 2);
        }

        array_unshift($args, lcfirst($method));

        return call_user_func_array([$this, 'addItem'], $args);
    }

    public static function __callStatic($method, $args)
    {
        $rule = new static();

        if ('is' == strtolower(substr($method, 0, 2))) {
            $method = substr($method, 2);
        }

        array_unshift($args, lcfirst($method));

        return call_user_func_array([$rule, 'addItem'], $args);
    }
}
