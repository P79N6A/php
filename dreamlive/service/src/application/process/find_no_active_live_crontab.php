<?php
// crontab 死循环 删除僵尸直播
// php /home/dream/codebase/service/src/application/process/find_no_active_live_crontab.php
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



$dao_live = new DAOLive();
$livelist = $dao_live->getActiveLives();
$cache = Cache::getInstance("REDIS_CONF_CACHE");
$nowtime = time();
foreach ($livelist as $liveinfo) {
    $last_chat_time = $cache->zScore(RobotChat::LAST_CHATING_KEY, $liveinfo['liveid']);
    

    if ((empty($last_chat_time) || ($nowtime-$last_chat_time) > 120)) {
        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("robot_receive", array("liveid" => $liveinfo['liveid'],"userid" => $liveinfo['uid'],"source" => 4));
    }
}
exit;