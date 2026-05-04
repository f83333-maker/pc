<?php
declare (strict_types=1);

namespace App\Service\Store\Bind;

use App\Entity\Store\Authentication;
use Kernel\Annotation\Inject;
use Kernel\Exception\ServiceException;

class Identity implements \App\Service\Store\Identity
{
    #[Inject]
    private \App\Service\Store\Http $http;

    public function status(Authentication $authentication, string $tradeNo = ""): array
    {
        $data = [];
        if ($tradeNo) {
            $data['trade_no'] = $tradeNo;
        }
        $http = $this->http->request("/user/identity/status", $data, $authentication);
        if ($http->code != 200) {
            throw new ServiceException($http->message);
        }
        return $http->data;
    }

    public function certification(string $certName, string $certNo, Authentication $authentication): string
    {
        $http = $this->http->request("/user/identity/certification", [
            "cert_name" => $certName,
            "cert_no" => $certNo
        ], $authentication);

        if ($http->code != 200) {
            throw new ServiceException($http->message);
        }
        return $http->data['url'];
    }
}