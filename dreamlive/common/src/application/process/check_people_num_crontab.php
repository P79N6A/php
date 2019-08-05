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

$live_user_real_num = "dreamlive_live_user_real_num";
$t = json_decode($cache->get($live_user_real_num), true);


$nowtime = time();
foreach ($livelist as $liveinfo) {
    $key = "dreamlive_live_user_real_num_".$liveinfo['liveid'];
    $result = json_decode($cache->get($key), true);
    $real_audience_num = $result['num'];
    //$real_audience_num = $t[$liveinfo['liveid']]['num'];
    
    $new_live_count = $dao_live->getUserLivesCount($liveinfo['uid']);
    
    //$uid_array_users = [22938458,22670661,10006699,10000001,11196354,20896110,10016044,11174936,20085301,10000195,10000194,10000190,10011002,10002000,11010375,22917390,10058492,10000009,10190370,20185824,11126729,10114376,10899808,20106579,10000022,20183977,10263689,10153473,10006699,10016075,10211564,20103845,11215424,22893043,22842753,11104667,10006520,10193752,20052263,10030992,10899916,10000025,10155027,10053518,10183864,10178284,10000068,10073011,20019227,20149307,10205989,10026652,10027095,20178553,10097254,10196673,10026521,22827356,22906862,22982466,11220158,20523051,22982472,10899808,22986709,10899808,22986709,20058394,23001315,23001309,22824394,23002819,23001306,22989843,22986963,10899916];
    $uid_array_users = Operation::getUidsArray();
    
    if (empty($uid_array_users) || !is_array($uid_array_users)) {
        $uid_array_users = [22938458,22670661,10006699,10000001,11196354,20896110,10016044,11174936,20085301,10000195,10000194,10000190,10011002,10002000,11010375,22917390,10058492,10000009,10190370,20185824,11126729,10114376,10899808,20106579,10000022,20183977,10263689,10153473,10006699,10016075,10211564,20103845,11215424,22893043,22842753,11104667,10006520,10193752,20052263,10030992,10899916,10000025,10155027,10053518,10183864,10178284,10000068,10073011,20019227,20149307,10205989,10026652,10027095,20178553,10097254,10196673,10026521,22827356,22906862,22982466,11220158,20523051,22982472,10899808,22986709,10899808,22986709,20058394,23001315,23001309,22824394,23002819,23001306,22989843,22986963,10899916];
    }
    if ($real_audience_num >= 30 && $new_live_count <= 3) {//
        
        $push_redis_key = "live_push_yunying_". $liveinfo['uid'];
        
        if ($cache->INCR($push_redis_key) > 3) {
            continue;
        }
        
        $user = new User();
        $user_info = $user->getUserInfo($liveinfo['uid']);
        
        
        $content = "新主播{$liveinfo['uid']}，昵称:{$user_info['nickname']}房间人数:" . $real_audience_num . ', 请相关人员多多关注!';
        Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, implode(',', $uid_array_users), $content, $content, 0);
    }
    
    var_dump($liveinfo['liveid']);
    
}


?>