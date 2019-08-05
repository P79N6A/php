<?php
$redis = new redis();
$result = $redis->connect('127.0.0.1', 6379);
$redis->auth('food-1-pAss');
$a = $redis->get("name");
$redis->hset("h","gg", 7);

$b = $redis->hgetAll("h");

///usr/local/lib/php/pecl/20160303/
$c = $redis->hget("h", "gg");
var_dump($c);
print_r($b);