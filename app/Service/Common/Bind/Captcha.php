<?php
declare (strict_types=1);

namespace App\Service\Common\Bind;

use Kernel\Annotation\Inject;
use Kernel\Exception\JSONException;
use Kernel\Session\Session;

class Captcha implements \App\Service\Common\Captcha
{

    #[Inject]
    private Session $session;

    public function create(string $key, int $expire, int $limiter = 60): string
    {
        if ($this->session->has($key) && $limiter > 0) {
            if ($this->session->get($key)['time'] + $limiter > time()) {
                throw new JSONException("验证码创建频繁，请稍后再试");
            }
        }
        $code = mt_rand(100000, 999999);
        $this->session->set($key, ["time" => time(), "expire" => $expire, "code" => $code]);
        return (string)$code;
    }

    public function verify(string $key, string $code): bool
    {
        if (!$this->session->has($key)) {
            return false;
        }

        $data = $this->session->get($key);

        if ($data['code'] != $code) {
            return false;
        }

        if ($data['time'] + $data['expire'] < time()) {
            return false;
        }

        return true;
    }

    public function destroy(string $key): void
    {
        $this->session->remove($key);
    }
}