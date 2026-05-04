<?php
declare(strict_types=1);

namespace Kernel\Plugin\Handle;

use Kernel\Context\Interface\Request;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

interface WebSocket
{

    public function message(Frame $frame, Server $server): void;

    public function open(Request $request, int $fd, Server $server): void;

    public function close(int $fd, Server $server): void;
}