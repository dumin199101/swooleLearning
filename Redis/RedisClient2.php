<?php
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->setOption(Redis::OPT_READ_TIMEOUT, -1);
$redis->subscribe(array('channel1'), function ($redis,$channel,$msg) {
    switch ($channel) {
        case 'channel1':
            echo 'channel1-' . $msg . PHP_EOL;
            break;
    }
});