<?php
// crontab 监测直播人数
// php /home/dream/codebase/service/src/application/process/check_people_num_crontab.php
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

require_once 'message_client/RongCloudClient.php';
$rongcloud_client = new RongCloudClient();

$dao_live = new DAOLive();
$live = new Live();

$cache = Cache::getInstance("REDIS_CONF_CACHE");
$redis_cache_key = "heartbeat_live_cache";
$total = $cache->zCard($redis_cache_key);

$live_elements = $cache->zRevRange($redis_cache_key, 0, $total);
$livelist = $dao_live->getActiveAndPausedLives();

$arr = [];
$nowtime = time();
$dao_live = new DAOLive();
foreach ($live_elements as $elem) {
    $liveinfo = $dao_live->getLiveInfo($elem);
    
    $result = $rongcloud_client->queryUsers($elem, 500, 2);
    $userinfo = User::getUserInfo($liveinfo['uid']);
    
    $arr[$elem] = array(
    'num' => $result['total'],
    'uid' => $liveinfo['uid'],
    'nickanme' => $userinfo['nickname']
    );

    var_dump($elem);
    
}

$live_user_real_num = "dreamlive_live_user_real_num";

$cache->set($live_user_real_num, json_encode($arr));


$t = json_decode($cache->get($live_user_real_num), true);

print_r($t);
?>