<?php

namespace app\admin\socket;

use Workerman\Connection\TcpConnection;

/**
 * 作用于admin模块的socket
 * User: Shiroi
 * EMail: 707305003@qq.com
 */
class Server extends \think\worker\Server
{
    protected $protocol = 'websocket';

    protected $host     = '0.0.0.0';

    protected array $connections = [];

    public function __construct($port)
    {
        $this->port = $port;
        parent::__construct();
    }

    public function onMessage(TcpConnection $connection,$data)
    {

        //存储所有的socket client
        $this->connections[$connection->id] = $connection;
        foreach ($this->connections as $c) {
            $c->send(json_encode($data));
        }
    }

    public function onClose(TcpConnection $connection){
        unset($this->connections[$connection->id]);
    }
}