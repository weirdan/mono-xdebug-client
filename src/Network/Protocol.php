<?php
namespace Weirdan\Xdebug\Network;

use
    System\Net\Sockets;

use function var_dump, strlen, str_repeat;


class Protocol
{
    protected $listener = null;
    protected $socket = null;
    protected $port = 9000;

    public function __construct()
    {

    }

    public function run()
    {
        $this->trace("Initialized XDebug protocol wrapper");

        $this->listener = new Sockets\TcpListener($this->port);

        $local = $this->listener->LocalEndpoint;
        $this->trace("Created socket on port {$local->Address->toString()}:{$local->Port}");

        $this->listener->Start();

        $this->socket = $this->listener->acceptSocket();

        $remote = $this->socket->RemoteEndPoint;
        $this->traceIn("Accepted connection from {$remote->Address->toString()}:{$remote->Port}");

        $data = (binary) str_repeat(0x00, 2048);
        var_dump('begin', $data);
        while ($this->socket->receive($data, 0, 2048, Sockets\SocketFlags::None)) {
            if (strlen($data)) {
                var_dump($data);
                $this->traceIn($data);
                $data = (binary) str_repeat(0x00, 2048);
            }
        }

        $this->traceIn('Closing connection');
        $this->socket->shutdown(Sockets\SocketShutdown::Both);
        $this->socket->close();

        $this->socket = null;
    }

    protected function trace($msg)
    {
        echo $msg .  "\n";
    }

    protected function traceIn($msg)
    {
        $this->trace('<<< ' . $msg);
    }

    protected function traceOut($msg)
    {
        $this->trace('>>> ' . $msg);
    }
}
