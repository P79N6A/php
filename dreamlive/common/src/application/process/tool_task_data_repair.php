<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/5
 * Time: 14:16
 * 修复线上做任务，星光，或者星钻加失败的。
 */

if (substr(php_sapi_name(), 0, 3) !== 'cli') { die("cli mode only");
}

set_time_limit(0);
ini_set('memory_limit', '2G');
ini_set("display_errors", "On");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
$ROOT_PATH = dirname(dirname(dirname(dirname(__FILE__))));

$LOAD_PATH = array(
    $ROOT_PATH."/src/www",
    $ROOT_PATH."/config",
    $ROOT_PATH."/src/application/controllers",
    $ROOT_PATH."/src/application/models",
    $ROOT_PATH."/src/application/models/libs",
    $ROOT_PATH."/src/application/models/dao",
    $ROOT_PATH."/../",
);
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $LOAD_PATH));

function __autoload($classname)
{
    $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    include_once "$classname.php";
}

require $ROOT_PATH."/config/server_conf.php";

if($argc!=5) { die("argc != 5 uid taskid star diamond awardid");
}

$uid=$argv[1];
$taskid=$argv[2];
$star=$argv[3];
$diamond=$argv[4];
$awardid=$argv[5];

$award=[
    'starlight'=>$star,
    'diamonds'=>$diamond,
];


try{
    $result=StarTask::increase($uid, $taskid, $award, $awardid);
    print_r($result);
}catch (Exception $e){
    throw $e;
}