<?php
declare(strict_types=1);

namespace Kernel\Plugin\Abstract;

use Kernel\Plugin\Entity\Plugin as PluginEntity;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

abstract class WebSocket implements \Kernel\Plugin\Handle\WebSocket
{

    protected PluginEntity $plugin;

    protected Server $server;

    public function __construct(PluginEntity $plugin, Server $server)
    {
        $this->plugin = $plugin;
        $this->server = $server;
    }

    protected function push(int $fd, string $data): void
    {
        \Kernel\Plugin\WebSocket::inst()->push($fd, $data);
    }

    protected function kill(int $fd): void
    {
        $this->server->close($fd);
    }

    protected function disconnect(int $fd, int $code = SWOOLE_WEBSOCKET_CLOSE_NORMAL, string $reason = ''): bool
    {
        return $this->server->disconnect($fd, $code, $reason);
    }

    public function isEstablished(int $fd): bool
    {
        return $this->server->isEstablished($fd);
    }

    public function pack(Frame|string $data, int $opcode = SWOOLE_WEBSOCKET_OPCODE_TEXT, int $flags = SWOOLE_WEBSOCKET_FLAG_FIN): string
    {
        return $this->server::pack($data, $opcode, $flags);
    }

    public function unpack(string $data): Frame
    {
        return $this->server::unpack($data);
    }
}