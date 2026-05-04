<?php
declare (strict_types=1);

namespace App\Service\User;

use App\Model\User;
use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\User\Bind\Category::class)]
interface Category
{

    public function only(?User $user): array;
}