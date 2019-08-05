<?php
require("./../vendor/autoload.php");

// Parameters passed using a named array:
$client = new Predis\Client([
    'scheme' => 'tcp',
    'host'   => '127.0.0.1',
    'port'   => 6379,
]);

// Same set of parameters, passed using an URI string:
$redis = new Predis\Client('tcp://127.0.0.1:6379');

$redis->set('library', 'preids');

$retval = $redis->get('library');
var_dump($retval);
$redis->setex('str', 10, 'bar'); //表示存储有效期为10秒

//setnx/msetnx相当于add操作,不会覆盖已有值

$redis->setnx('foo', 12); //true
$redis->setnx('foo', 34); //false
//getset操作,set的变种,结果返回替换前的值

$redis->getset('foo', 56); //返回34
//incrby/incr/decrby/decr 对值的递增和递减

$redis->incr('foo'); //foo为57
$redis->incrby('foo', 2); //foo为59
//exists检测是否存在某值

$redis->exists('foo'); //true