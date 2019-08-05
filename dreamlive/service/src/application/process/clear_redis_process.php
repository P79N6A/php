<?php
set_time_limit(0);
ini_set('memory_limit', '1G');
ini_set("display_errors", "On");
error_reporting(E_ALL & ~ E_NOTICE & ~ E_WARNING);
$ROOT_PATH = dirname(dirname(dirname(dirname(__FILE__))));
$LOAD_PATH = array(
        $ROOT_PATH . "/src/www",
        $ROOT_PATH . "/config",
        $ROOT_PATH . "/src/application/controllers",
        $ROOT_PATH . "/src/application/models",
        $ROOT_PATH . "/src/application/models/libs",
        $ROOT_PATH . "/src/application/models/dao"
);
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $LOAD_PATH));
function __autoload($classname)
{
    $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    include_once "$classname.php";
}


require $ROOT_PATH."/config/server_conf.php";
require_once 'process_client/ProcessClient.php';

//清理bak队列
require $ROOT_PATH . "/config/server_conf.php";

$cache = Cache::getInstance("REDIS_CONF_PROCESS");

$today =  substr(date("Ymd"), -6);
$lastday = substr(date("Ymd", strtotime("-1 day")), -6);
$lastlastday = substr(date("Ymd", strtotime("-2 day")), -6);
$cache->del("dream_live_admin_start_broadcast_bak".$today);
$cache->del("dream_live_admin_start_broadcast_bak".$lastday);
$cache->del("dream_live_admin_start_broadcast_bak".$lastlastday);

$cache->del("dream_live_admin_start_push_bak".$today);
$cache->del("dream_live_admin_start_push_bak".$lastday);
$cache->del("dream_live_admin_start_push_bak".$lastlastday);

$cache->del("dream_deal_replay_title_bak".$today);
$cache->del("dream_deal_replay_title_bak".$lastday);
$cache->del("dream_deal_replay_title_bak".$lastlastday);


$cache->del("dream_counter_change_bak".$today);
$cache->del("dream_counter_change_bak".$lastday);
$cache->del("dream_counter_change_bak".$lastlastday);


$cache->del("dream_kefu_message_woker_bak".$today);
$cache->del("dream_kefu_message_woker_bak".$lastday);
$cache->del("dream_kefu_message_woker_bak".$lastlastday);

$cache->del("dream_live_error_new_worker_bak".$today);
$cache->del("dream_live_error_new_worker_bak".$lastday);
$cache->del("dream_live_error_new_worker_bak".$lastlastday);

$cache->del("dream_live_receive_audience_online_bak".$today);
$cache->del("dream_live_receive_audience_online_bak".$lastday);
$cache->del("dream_live_receive_audience_online_bak".$lastlastday);


$cache->del("dream_live_receive_audience_online_delete_bak".$today);
$cache->del("dream_live_receive_audience_online_delete_bak".$lastday);
$cache->del("dream_live_receive_audience_online_delete_bak".$lastlastday);

$cache->del("dream_ive_start_broadcast_bak".$today);
$cache->del("dream_ive_start_broadcast_bak".$lastday);
$cache->del("dream_ive_start_broadcast_bak".$lastlastday);


$cache->del("dream_follower_robot_add_bak".$today);
$cache->del("dream_follower_robot_add_bak".$lastday);
$cache->del("dream_follower_robot_add_bak".$lastlastday);


$cache->del("dream_live_monitor_errormsg_bak".$today);
$cache->del("dream_live_monitor_errormsg_bak".$lastday);
$cache->del("dream_live_monitor_errormsg_bak".$lastlastday);

$cache->del("dream_followings_decrease_newsfeeds_bak".$today);
$cache->del("dream_followings_decrease_newsfeeds_bak".$lastday);
$cache->del("dream_followings_decrease_newsfeeds_bak".$lastlastday);


var_dump("ok");
