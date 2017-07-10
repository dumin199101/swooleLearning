<?php
/*利用Redis的订阅发布实现异步任务：此客户端一直运行在后台，做服务器端监听*/
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->setOption(Redis::OPT_READ_TIMEOUT, -1);
$redis->subscribe(array('channel1', 'channel2'), function ($redis,$channel,$msg) {
    switch ($channel) {
        case 'channel1':
            echo 'channel1-' . $msg . PHP_EOL;
            break;
        case 'channel2':
            echo "channel2-" . $msg . PHP_EOL;
            break;
    }
});