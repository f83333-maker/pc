<?php
declare(strict_types=1);

namespace App\Service;

use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Bind\ManageSSO::class)]
interface ManageSSO
{

    public function login(string $username, string $password, bool $remember = false): array;
}