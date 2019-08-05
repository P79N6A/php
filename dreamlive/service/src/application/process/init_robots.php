<?php
// crontab 初始化机器人
// php /home/dream/codebase/service/src/application/process/clean_relict_live_worker.php
// insert into robots(uid,addtime) select uid,addtime from user limit 200000 ;
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


$cache = Cache::getInstance("REDIS_CONF_USER");

$dao_robots = new DAORobots();
$robots_list = $dao_robots->getList();
if (!empty($robots_list)) {
    foreach ($robots_list as $key => $value) {
        $bool = $cache->zIncrBy("robots", 1, $value['uid']);
        var_dump($value['uid'].'----' . $bool);
    }
} else {
    var_dump("empty robots");
}
