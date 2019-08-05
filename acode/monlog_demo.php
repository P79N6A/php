<?php
ini_set('date.timezone','Asia/Shanghai'); // 'Asia/Shanghai' 为上海时区
require("./../vendor/autoload.php");

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// create a log channel
$log = new Logger('name');
$log->pushHandler(new StreamHandler('/Users/xbg/php/acode/xubaoguo.log', Logger::WARNING));

// add records to the log
$log->warning('Foo');
$log->error('Bar');
