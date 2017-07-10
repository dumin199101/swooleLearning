<?php

class Client1
{
    protected $client = null;

    /**
     * Client constructor.
     * @param $client
     */
    public function __construct()
    {
        //注意这里需设置为异步，不然下面无法设置事件回调函数
        $this->client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);

        $this->client->on('connect', array($this, 'connect'));
        $this->client->on('receive', array($this, 'receive'));
        $this->client->on('close', array($this, 'close'));
        $this->client->on('error', array($this, 'error'));
        //连接服务端
        $this->client->connect('0.0.0.0', 9502);
    }
    public function connect($client)
    {
        echo "Connect\n";
        fwrite(STDOUT, "Please Enter Message:");
        $message = trim(fgets(STDIN));
        $this->client->send($message);
    }
    public function receive($client, $data)
    {
        echo "Receive Data from Server: {$data}\n";
    }

//    public function receive()
//    {
//        $data = $this->client->recv();
//        echo "Receive Data from Server: {$data}\n";
//    }


    public function close()
    {
        echo "close \n";
    }
    public function error($client)
    {
        echo "error \n";
    }
}
$client = new Client1();




//class EchoClient {
//    protected $client = null;
//
//    public function __construct() {
//        //注意这里需设置为异步，不然下面无法设置事件回调函数
//        $this->client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);
//
//        $this->client->on('connect', array($this, 'connect'));
//        $this->client->on('receive', array($this, 'receive'));
//        $this->client->on('close', array($this, 'close'));
//        $this->client->on('error', array($this, 'error'));
//        //连接服务端
//        $this->client->connect('0.0.0.0', 9502);
//    }
//
//    public function connect($client) {
//        echo "connect \n";
//        //向标准输出写入数据
//        fwrite(STDOUT, "请输入消息：");
//        //获取标准输入数据
//        $msg = trim(fgets(STDIN));
//        //向服务端发送数据
//        $client->send($msg);
//    }
//
//    public function receive($client, $data) {
//        echo "Receive Data from Server: {$data}\n";
//
//
//    }
//
//    public function close($client) {
//        echo "Server close \n";
//    }
//
//    public function error($client) {
//        echo "error \n";
//    }
//}
//
//$cli = new EchoClient();
