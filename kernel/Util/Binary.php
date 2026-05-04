<?php
declare(strict_types=1);

namespace Kernel\Util;

use Kernel\Component\Singleton;

class Binary
{
    use Singleton;

    private function decrypt(string $data, string $key): string|false
    {
        return openssl_decrypt($data, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $key);
    }

    private function encrypt(string $data, string $key): string|false
    {
        return openssl_encrypt((string)$data, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $key);
    }

    private function generateKey(): string
    {
        $config = config('database');
        return strtoupper(substr(md5($config['database'] . $config['password'] . $config['username'] . $config['prefix'] . __FILE__), 0, 16));
    }

    public function pack(mixed $data, ?string $key = null): string
    {
        return $this->encrypt(serialize($data), $key ?? $this->generateKey()) ?: "";
    }

    public function unpack(string $data, ?string $key = null): mixed
    {
        return unserialize($this->decrypt($data, $key ?? $this->generateKey()) ?: "");
    }
}