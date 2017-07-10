<?php
  class Client{
      private $client;

      /**
       * Client constructor.
       * @param $client
       */
      public function __construct()
      {
          $this->client = new swoole_client(SWOOLE_SOCK_TCP);
      }

      //此实例下Client发送完消息后就自动断开了，接收不到服务器发来的消息
      public function connect()
      {
          if (!$this->client->connect('127.0.0.1', 9501, 1)) {
              echo "Error {$this->client->errMsg}[{$this->client->errCode}]\n";
          }
          fwrite(STDOUT, "请输入消息：");
          $msg = trim(fgets(STDIN));
          //客户端发送数据给服务端
          $this->client->send($msg);
          //客户端接收服务端的响应信息：
          $message = $this->client->recv();
          echo "Get Message From Server {$message} \n";
      }
  }

  $client = new Client();
  $client->connect(); //连接服务器