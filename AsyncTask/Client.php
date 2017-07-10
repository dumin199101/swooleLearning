<?php

class Client
{
    private $client;
    private $data;

    public function __construct($data)
    {
        $this->client = new swoole_client(SWOOLE_SOCK_TCP,SWOOLE_SOCK_ASYNC);
        $this->client->on('connect',[$this, 'onConnect']);
        $this->client->on('receive', [$this,'onReceive']);
        $this->client->on('close', [$this, 'onClose']);
        $this->client->on('error', [$this, 'onError']);
        $this->client->connect('127.0.0.1', 9501, 1);
        $this->data = $data;
    }



    public function onConnect($client)
    {
        echo "Client Connect Success\n";
        //发送数据到服务器端执行任务：
        $this->client->send($this->data);
    }

    public function onReceive($client, $data)
    {

    }


    public function onClose($client)
    {
        echo "Server closed \n";
    }


    public function onError($client)
    {
        echo "Server Error \n";
    }
}

$params = array();
$params['email'] = "1766266374@qq.com";
$params['subject'] = "世界你好";
$params['content'] = "四姐你好啊，我是三哥，哈哈哈哈哈哈";
$msg = json_encode($params);
$client = new Client($msg);
echo "[".date("Y-m-d H:i:s")."]发送邮件任务".PHP_EOL;
echo "正再给[" . $params['email'] . "]发送邮件,大概需要等待20s..." . PHP_EOL;
echo "[".date("Y-m-d H:i:s")."]继续忙其他的".PHP_EOL;