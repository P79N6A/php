<?php
// crontab 监测房间用户列表 不在房间内就删除 5分钟执行一次
// */5 * * * * php /home/dream/codebase/service/src/application/process/check_user_exist_chatroom_crontab.php
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

$cache = Cache::getInstance("REDIS_CONF_CACHE");
$key   = "heartbeat_live_cache";
$dao_live = new DAOLive();
$rank = new Rank();
$list  = $cache->zRevRange($key, 0, -1);
$total = 0;
$i = 0;
$j = 0;
foreach ($list as $item) {
    try {
        
        //$liveinfo = $dao_live->getLiveInfo($item);
        $chatroomId = $item;
        //var_dump($chatroomId);
        $op = $cache->zRevRangeByScore("audience_" . $chatroomId, PHP_INT_MAX, 0, ['withscores' => true, 'limit' => [0, 100]]);
        
        if (empty($op)) {
            continue;
        }

        $userids = array_keys($op);

        if (empty($userids)) {
            continue;
        }
        $result = $rongcloud_client->batchQueryChatroomUserExist($chatroomId, $userids);

        if (empty($result)) {
            continue;
        }
        
        //print_r($result);exit;
        var_dump(count($userids));

        foreach ($result['result'] as $row) {
            
            if ($row['isInChrm'] == 0) {
                //var_dump("1:");
                //print_r($row);
                $j++;
                $rank->setRank('audience', "delete", $row['userId'], '1', $chatroomId);
            } else {
                //var_dump("2:");
                //var_dump($row);
                $i++;
            }
        }
        $total++;
        //exit;

    } catch (Exception $e) {
        print_r($e);
    }
}

var_dump($total."--done ok! out:" . $j .",in:" . $i);