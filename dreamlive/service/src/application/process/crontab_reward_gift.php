<?php
/**
 * 每十分钟没人送礼，机器人送2-5朵玫瑰花
 * User: User
 * Date: 2018/4/9
 * Time: 14:02
 */
if (substr(php_sapi_name(), 0, 3) !== 'cli') { die("cli mode only");
}

set_time_limit(0);
ini_set('memory_limit', '2G');
ini_set("display_errors", "On");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
$ROOT_PATH = dirname(dirname(dirname(dirname(__FILE__))));

$LOAD_PATH = array(
    $ROOT_PATH . "/src/www",
    $ROOT_PATH . "/config",
    $ROOT_PATH . "/src/application/controllers",
    $ROOT_PATH . "/src/application/models",
    $ROOT_PATH . "/src/application/models/libs",
    $ROOT_PATH . "/src/application/models/libs/stream_client",
    $ROOT_PATH . "/src/application/models/dao",
    $ROOT_PATH . "/../",
);
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $LOAD_PATH));

function __autoload($classname)
{
    $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    include_once "$classname.php";
}

require $ROOT_PATH . "/config/server_conf.php";

//常量定义
$giftid     = 3855;
$sendid     = 12181934;
$time       = time();
$old_time   = $time - 60*10;
$time       = date("Y-m-d H:i:s", $time);
$old_time   = date("Y-m-d H:i:s", $time);


$gift       = new Gift();
$live       = new DAOLive();
$gift_log   = new DAOGiftLog();
$cache      = Cache::getInstance("REDIS_CONF_CACHE");
$uids       = [];
$uid_live   = [];
//获取有等级用户
$leve_uids  = json_decode($cache->get('crontab_reward_gift_uids'), true);

//获取正在直播用户
$sql_live   = "select uid,liveid from live where status=? and addtime<?";
$live_uids  = $live->getAll($sql_live, [1,$old_time]);
if($live_uids) {
    foreach($live_uids as $v){
        $uid_live[$v['uid']]        = $v['liveid'];
    }
}
//获取收礼主播uid
$sql_gift   = "select receiver from giftlog where addtime>? and addtime<?";
$gift_uids  = $gift_log->getAll($sql_gift, [$old_time,$time]);
//
$uids       = array_intersect($leve_uids, array_column($live_uids, 'uid'));
$uids       = array_diff($uids, array_column($gift_uids, 'receiver'));
//发送礼物
$giftUniTag = strval(time());
if(!empty($uids)) {
    foreach($uids as $v){
        $num    = rand(2, 5);
        $gift->sendGiftProcess($sendid, $v, $giftid, $num, $uid_live[$v], 1, $giftUniTag);
    }
}


