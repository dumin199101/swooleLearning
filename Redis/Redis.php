<?php
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
//$redis->publish('channel1', "大家好啊");
//$redis->publish('channel2', "大家好啊");
//$redis->publish('channel3', "大家好啊");
$redis->publish('channel1', '哈哈哈');

//pubsub CHANNELS 查看已订阅发布的频道，phpredis不支持此操作