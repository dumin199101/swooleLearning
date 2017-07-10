<?php
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
for ($i=0;$i<100;$i++) {
    $redis->lPush('QueueList', $i);
}

