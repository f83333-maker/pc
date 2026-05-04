<?php
declare(strict_types=1);

namespace App\Pay;

use App\Util\PayConfig;
use GuzzleHttp\Client;

abstract class Base
{

    public float $amount;

    public string $tradeNo;

    public array $config;

    public string $callbackUrl;

    public string $returnUrl;

    public string $clientIp;

    public string $code;

    public string $handle;

    protected function log(string $message): void
    {
        PayConfig::log($this->handle, "TRADE", $message);
    }

    protected function http(): Client
    {
        return new Client(["verify" => false]);
    }
}