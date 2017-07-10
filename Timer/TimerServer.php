<?php

class TimerServer
{
    private $str = "Say Hello";

    public function onAfter()
    {
        echo $this->str . PHP_EOL;
    }
}

$timerServer = new TimerServer();
swoole_timer_after(2000, array($timerServer, 'onAfter'));
//通过闭包实现回调
swoole_timer_after(1000, function () use ($timerServer) {
    $timerServer->onAfter();
});
//
swoole_timer_tick(2000, array($timerServer, 'onAfter'));
$str = "Hello";
$timer_id = swoole_timer_tick(2000, function ($timer_id, $params) use ($timerServer, $str) {
    echo $timer_id . '---' . $str . "---" . $params['World'] . PHP_EOL;
    $timerServer->onAfter();
}, array('World' => 'World'));

//10秒后删除定时器
swoole_timer_after(10000, function () use ($timer_id) {
    swoole_timer_clear($timer_id);
});

//$redis = new Redis();
//
//$redis->subscribe('redis', function ($redis,$channel,$msg) {
//    switch ($channel) {
//        case 1 :
//            echo $msg . '1';
//            break;
//        case 2:
//            echo $msg . '2';
//            break;
//    }
//});
//$redis->publish(array($channel1,$channel2),$msg);