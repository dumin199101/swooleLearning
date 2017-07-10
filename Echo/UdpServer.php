<?php
$upd_server = new swoole_server('0.0.0.0', 9501,SWOOLE_PROCESS, SWOOLE_SOCK_UDP);
$upd_server->set([
    'worker_num'=>4,
    'dispatch_mode'=>2, //1.轮询 2.固定分配 3.争抢
]);
$upd_server->on('receive', function ($serv,$fd,$from_id,$data) {
    $serv->send($fd,"Receive Data from Client:{$data}!\n",$from_id);
});
$upd_server->start();