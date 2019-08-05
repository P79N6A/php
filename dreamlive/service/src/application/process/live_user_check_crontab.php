<?php
// crontab 初始化正在直播人和直播id对应关系
// php /home/dream/codebase/service/src/application/process/live_user_check_crontab.php
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

$cache = Cache::getInstance("REDIS_CONF_CACHE");


$elements = $cache->zRevRange('User_living_cache', 0, -1);

$dao_live = new DAOLive();
$sql = "select uid from live where status = ? ";
$liveing_users = $dao_live->getAll($sql, array('1'));

$live = new Live();

if (!empty($liveing_users)) {
    
    $liveingUsers = [];
    foreach ($liveing_users as $key => $value) {
        $liveingUsers[] = $value['uid'];
    }
    
    
    foreach($elements as $element) {
        if (!in_array($element, $liveingUsers)) {
            Live::remRedisUserLive($element);
            var_dump($element . '===deleted');
        }
        
        
    }
    
} else {
    var_dump("empty living ");
}
