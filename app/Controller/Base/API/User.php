<?php
declare(strict_types=1);

namespace App\Controller\Base\API;

use App\Model\BusinessLevel;
use Kernel\Annotation\Inject;
use Kernel\Context\Interface\Request;
use Kernel\Exception\JSONException;

abstract class User extends \App\Controller\Base\User
{
    #[Inject]
    protected Request $request;

    protected function json(int $code = 200, ?string $message = null, ?array $data = []): array
    {
        $json['code'] = $code;
        $message ? $json['msg'] = $message : null;
        $json['data'] = $data;
        return $json;
    }

    
    protected function businessValidation(): BusinessLevel
    {
        $level = $this->getUser()->businessLevel;
        if (!$level) {
            throw new JSONException("无权限");
        }
        return $level;
    }
}