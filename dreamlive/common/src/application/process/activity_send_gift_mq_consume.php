<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/9
 * Time: 18:51
 */
set_time_limit(0);
ini_set('memory_limit', '1G');
ini_set("display_errors", "On");
ini_set('default_socket_timeout', -1);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
$ROOT_PATH = dirname(dirname(dirname(dirname(__FILE__))));
$LOAD_PATH = array(
    $ROOT_PATH."/src/www",
    $ROOT_PATH."/config",
    $ROOT_PATH."/src/application/controllers",
    $ROOT_PATH."/src/application/models",
    $ROOT_PATH."/src/application/models/libs",
    $ROOT_PATH."/src/application/models/dao"
);
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $LOAD_PATH));

function __autoload($classname)
{
    $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    include_once "$classname.php";
}

require $ROOT_PATH."/config/server_conf.php";
require_once 'process_client/ProcessClient.php';

try {
    // $ps=new PubSubMq('send_gift_mq');
    PubSubMq::subscribe(
        'send_gift_mq', function ($msg) {
            $uid=$msg['uid'];
            $receiveid=$msg['receiveid'];
            $num=$msg['num'];
            $relateid=$msg['relateid'];
            ActivitySupport::giftSupport($uid, $receiveid, $relateid, $num);
        }
    );
} catch (Exception $e) {
    file_put_contents(__DIR__."/tmp.txt", "\n".json_encode($e)."\n", FILE_APPEND);
    ;
}