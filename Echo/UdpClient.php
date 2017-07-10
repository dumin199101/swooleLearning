<?php
$client = new swoole_client(SWOOLE_SOCK_UDP);
$client->connect('127.0.0.1', 9501, 1);
while (1) {
    $client->send("Hello Swoole");
    $data = $client->recv();
    echo "Receive Data From Server{$data}\n";
    sleep(2);
}
