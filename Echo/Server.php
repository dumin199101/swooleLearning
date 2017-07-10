<?php

class Server
{
    private $serv;

    public function __construct()
    {
        $this->serv = new swoole_server("0.0.0.0", 9501);
        $this->serv->set([
            'worker_num' => 4,
            'daemonize' => false,
        ]);
        $this->serv->on('Start', array($this, 'onStart'));
        $this->serv->on('Connect', array($this, 'onConnect'));
        $this->serv->on('Receive', array($this, 'onReceive'));
        $this->serv->on('Close', array($this, 'onClose'));
        $this->serv->start();  //服务启动
    }

    public function onStart($serv)
    {
        echo "Start\n";
    }

    /**
     * @param $serv
     * @param $fd   服务端对客户端的唯一标识符
     * @param $from_id 标明来自于那个reactor线程
     */
    public function onConnect($serv, $fd, $from_id)
    {
        echo "Connect\n";
        $serv->send($fd, "Hello {$fd}!");
    }

    /*接收客户端发来的消息响应客户端*/
    public function onReceive(swoole_server $serv, $fd, $from_id, $data)
    {
        $serv->send($fd, $data);
    }

    public function onClose($serv, $fd, $from_id)
    {
        echo "Client {$fd} Close Connection\n";
    }
}

//实例化：
$swoole_server = new Server();