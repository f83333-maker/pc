<?php
declare (strict_types=1);

namespace Kernel\Context\Interface;

interface Response
{

    const TYPE_REDIRECT = 0x1;

    const TYPE_JSON = 0x2;

    const TYPE_RENDER = 0x3;

    const TYPE_RAW = 0x4;

    public function withCookie(string $key, string $value, int $expire): static;

    public function withHeader(string $key, string $value): static;

    public function redirect(string $url): static;

    public function json(int $code = 200, string $message = "success", ?array $data = null, array $ext = []): static;

    public function render(string $template, ?string $title = null, array $data = [], string|array $path = BASE_PATH . "/app/View/"): static;

    public function raw(string $data): static;

    public function getOptions(?string $key = null): mixed;

    public function draw(): void;

    public function end(): static;
}