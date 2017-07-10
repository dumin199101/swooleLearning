<?php
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
while (1) {
    sleep(2);
    $val = $redis->rPop('QueueList');
    if ($val!==FALSE) {
        echo "弹出元素：{$val}，执行后台进程" . PHP_EOL;
    }else{
        echo "队列已清空\n";
    }
}