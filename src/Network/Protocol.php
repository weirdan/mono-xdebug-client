<?php
namespace Weirdan\Xdebug\Network;

use function
    socket_create_listen,
    socket_getsockname,
    socket_accept,
    socket_getpeername,
    socket_read,
    strlen,
    socket_shutdown,
    socket_close;


class Protocol
{
    protected $socket;
    protected $connection;
    protected $port = 9000;

    public function __construct()
    {

    }

    public function run()
    {
        $this->trace("Initialized XDebug protocol wrapper");
        $this->socket = socket_create_listen($this->port);
        socket_getsockname($this->socket, $addr, $port);
        $tihs->trace("Created socket on port {$addr}:{$port}");

        $this->connection = socket_accept($this->socket);
        socket_getpeername($this->connection, $raddr, $rport);

        $this->traceIn("Accepted connection from {$raddr}:{$rport}");

        while ($data = socket_read($this->connection, 2048)) {
            if (strlen($data)) {
                $this->traceIn($data);
            }
        }

        $this->traceIn('Closing connection');
        socket_shutdown($this->connection, 2);
        socket_close($this->connection);
        $this->connection = false;
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
