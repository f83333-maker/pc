<?php
declare (strict_types=1);

namespace App\Service\User;

use App\Entity\Report\Handle;
use App\Entity\Report\Reply;
use Kernel\Annotation\Bind;
use App\Entity\Report\Order;

#[Bind(class: \App\Service\User\Bind\OrderReport::class)]
interface OrderReport
{

    public function apply(Order $order): void;

    public function handle(Handle $handle): void;

    public function reply(Reply $reply): void;
}