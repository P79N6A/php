<?php
// crontab 死循环 删除僵尸直播
// php /home/dream/codebase/service/src/application/process/clean_relict_live_worker.php
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
while (true) {
    $dao_live = new DAOLive();
    //     $livelist = $dao_live->getActiveAndPausedLives();
    $live = new Live();
    //     var_dump("live====>".count($livelist));
    //     foreach ($livelist AS $key => $row) {
    //         $userlist = $rongcloud_client->queryUsers($row['liveid'], 500, 1);
    //         $live_users = array_column($userlist['users'], "id");
    //         var_dump($row['liveid'] . "_user====>" . count($live_users));

    //         if (!in_array($row['uid'], $live_users) || empty($live_users)) {
    //             require_once('process_client/ProcessClient.php');
    //             $live_info = $live->getLiveInfo($row['liveid']);
    //             $live->reclict($row['liveid']);
    //             var_dump("deleted:". $row['liveid']);
    //             ProcessClient::getInstance("dream")->addTask("live_stop", $live_info);
    //         }
    //     }
    //     var_dump("database------------------------------------end");
    
    $cache = Cache::getInstance("REDIS_CONF_CACHE");
    $redis_cache_key = "heartbeat_live_cache";
    $total = $cache->zCard($redis_cache_key);
    
    $live_elements = $cache->zRevRange($redis_cache_key, 0, $total);
    
    $redis_cache_key_istudio= "istudio_live_cache";
    $total_istudio = $cache->zCard($redis_cache_key_istudio);
    
    $istudio_live_elements = $cache->zRevRange($redis_cache_key_istudio, 0, $total_istudio);
    $isdudio_uids = [];
    foreach ($istudio_live_elements as $ele) {
        $isdudio_uids[] = $ele;
    }
    
    
    $nowtime = time();
    foreach ($live_elements as $elements) {
        $last_heart_beat_time = $cache->zScore($redis_cache_key, $elements);
        
        $liveinfo = $dao_live->getLiveInfo($elements);
        if ($liveinfo['virtual'] == 'Y') {
            $cache->zRem($redis_cache_key, $elements);
            var_dump("deleted-cache:". $elements);
            continue;
        }
        
        if (!empty($isdudio_uids) && in_array($liveinfo['uid'], $isdudio_uids)) {
            continue;
        }
        
        
        
        if ($liveinfo['status'] == Live::ACTIVING) {
            if (($nowtime-$last_heart_beat_time) > 60) {//60秒没有心跳就杀死
                $live->reclict($elements);
                var_dump("deleted:". $elements);
                //ProcessClient::getInstance("dream")->addTask("live_stop", $liveinfo);
                $cache->zRem($redis_cache_key, $elements);
            }
        } elseif ($liveinfo['status'] == Live::PAUSED) {
            if (($nowtime-$last_heart_beat_time) > 120) {//120秒没心跳就杀死
                $live->reclict($elements);
                var_dump("deleted:". $elements);
                //ProcessClient::getInstance("dream")->addTask("live_stop", $liveinfo);
                $cache->zRem($redis_cache_key, $elements);
            }
        } else {
            $cache->zRem($redis_cache_key, $elements);
            var_dump("deleted-cache:". $elements);
        }
    }
    
    var_dump("cache------------------------------------end");
    
    sleep(rand(3, 7));
}

?>