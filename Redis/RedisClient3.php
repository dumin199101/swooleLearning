<?php
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->setOption(Redis::OPT_READ_TIMEOUT, -1);
$redis->subscribe(array('channel2'), function ($redis,$channel,$msg) {
    switch ($channel) {
        case 'channel2':
            echo "channel2-" . $msg . PHP_EOL;
            break;
    }
});