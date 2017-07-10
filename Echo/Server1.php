<?php
   class Server1{
       private $serv;
       /**
        * Server1 constructor.
        * @param $serv
        */
       public function __construct()
       {
           $this->serv = new swoole_server('0.0.0.0',9502);
           $this->serv->set([
               'worker_num'=>4,
               'daemonize'=>false
           ]);
           $this->serv->on('start',[$this,'onStart']);
           $this->serv->on('connect',[$this,'onConnect']);
           $this->serv->on('receive',[$this,'onReceive']);
           $this->serv->on('close',[$this,'onclose']);
           $this->serv->start();
       }
       public function onStart($serv)
       {
           echo "Start\n";
       }

       public function onConnect($serv,$fd,$from_id)
       {
           echo "Connect\n";
           $serv->send($fd,"Hello {$fd}!");
       }

       public function onReceive(swoole_server $serv,$fd,$from_id,$data)
       {
           $serv->send($fd,$data);
       }

       public function onClose($serv,$fd,$from_id)
       {
           echo "Client {$fd} Closed\n";
       }
   }
   $serve = new Server1();
