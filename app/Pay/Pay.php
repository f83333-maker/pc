<?php
declare(strict_types=1);

namespace App\Pay;

use App\Entity\PayEntity;

interface Pay
{

    const TYPE_REDIRECT = 2;

    const TYPE_LOCAL_RENDER = 3;

    const TYPE_SUBMIT = 4;

    public function trade(): PayEntity;
}