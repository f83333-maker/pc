<?php
declare (strict_types=1);

namespace App\Service\Common\Bind;

use Kernel\Annotation\Inject;
use Kernel\Exception\JSONException;
use Kernel\Session\Session;

class Code implements \App\Service\Common\Code
{

    #[Inject]
    private Session $session;

    
    public function create(string $key, int $expire = 60): int
    {

        $key = sprintf(\App\Const\Session::CODE, $key);
        $var = $this->session->get($key);

        if ($var) {
            $tm = $var['time'] + $expire;
            if ($tm > time()) {
                throw new JSONException(sprintf("验证码创建频繁，%d后再进行尝试", $tm - time()));
            }
        }

        mt_srand();
        $code = mt_rand(100000, 999999);
        $this->session->set($key, [
            "code" => $code,
            "time" => time()
        ]);

        return $code;
    }

    
    public function verify(string $key, int $code, int $expire = 300): bool
    {
        if ($code == 0) {
            return false;
        }

        $key = sprintf(\App\Const\Session::CODE, $key);
        $var = $this->session->get($key);

        if (!$var) {
            return false;
        }

        $tm = $var['time'] + $expire;

        if ($tm < time()) {
            return false;
        }

        if ($code != $var['code']) {
            return false;
        }

        $this->session->remove($key);
        return true;
    }
}