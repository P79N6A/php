<?php

set_time_limit(0);
ini_set('memory_limit', '1G');
ini_set("display_errors", "On");
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

$dao_patroller = new DAOPatroller();
$sql = "select uid,relateid,type,endtime,addtime from user_guard order by id asc ";
$alls = $dao_patroller->getAll($sql);
$redis_cache = Cache::getInstance('REDIS_CONF_CACHE');

$i = 1;
foreach ($alls as $option) {
    
    $uid = $option['uid'];
    $relateid = $option['relateid'];
    $type = $option['type'];
    $expires = strtotime($option['endtime']) - strtotime($option['addtime']);
    
    $exists = UserGuard::getUserGuardRedis($uid, $relateid);
    if (! $exists) {
        $result = UserGuard::addRedisBySet($uid, $relateid, $type, $expires);
    
        if ($result) {
            var_dump($i);
            $i++;
        }
    } else {
        var_dump("存在");
    }
    
}