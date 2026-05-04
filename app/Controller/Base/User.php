<?php
declare(strict_types=1);

namespace App\Controller\Base;

use App\Model\UserGroup;
use App\Util\Context;

abstract class User
{

    protected function getUser(): ?\App\Model\User
    {
        return Context::get(\App\Consts\User::SESSION);
    }

    protected function getUserGroup(): ?UserGroup
    {
        $user = $this->getUser();
        if (!$user) {
            return null;
        }
        return UserGroup::get($user->recharge);
    }
}