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

class UsersWatchLiveOnline extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_MESSAGE");
        $this->setTableName("user_watch_live_online");
    }

    public function getList($liveid, $startime, $offset, $step)
    {
        $sql = "SELECT * FROM " . $this->getTableName() . " where relateid=? and addtime>'" . $startime . "' order by addtime asc limit $offset,$step" ;
        return $this->getAll($sql, array($liveid));
    }

    public function getCount($liveid, $startime)
    {
        $sql = "select count(0) from {$this->getTableName()} where relateid=? and addtime>'" . $startime . "'";
        return (int) $this->getOne($sql, array($liveid));
    }
    
    public function getAllList($startime, $offset, $step)
    {
        $sql = "SELECT * FROM " . $this->getTableName() . "  order by addtime asc limit $step" ;
        return $this->getAll($sql);
    }
    
    public function getAllCount($startime)
    {
        $sql = "select count(0) from {$this->getTableName()} where addtime>'" . $startime . "'";
        return (int) $this->getOne($sql);
    }
    
}

function __autoload($classname)
{
    $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    include_once "$classname.php";
}


require $ROOT_PATH."/config/server_conf.php";
require_once 'process_client/ProcessClient.php';



require $ROOT_PATH . "/config/server_conf.php";




$step     = 100;//步长
$startime_half = date('Y-m-d H:i:s', time()-1800);//半小时之前
$startime  = date('Y-m-d H:i:s', time()-120);//两分钟之前



$cache = Cache::getInstance("REDIS_CONF_CACHE");
$key   = "heartbeat_live_cache";
$dao_live = new DAOLive();
//$total = $cache->zCard($key);
$list  = $cache->zRevRange($key, 0, -1);
foreach ($list as $item) {
    try {
        
        $liveinfo = $dao_live->getLiveInfo($item);
        
        if ($liveinfo['status'] == Live::ACTIVING) {
            //用户看播列表
            $option = array(
            'uid'    => $liveinfo['uid'],
            'liveid' => $item,
            'time'   => date('Y-m-d H:i:s'),
            'type'   => "func:joinchatroommsgcontent"
            );
            ProcessClient::getInstance("dream")->addTask("live_receive_audience_online", $option);
        }
        if ($liveinfo['status'] != Live::ACTIVING||$liveinfo['status'] != Live::PAUSED) {
            //用户看播列表
            $option = array(
            'uid'    => $liveinfo['uid'],
            'liveid' => $item,
            'time'   => date('Y-m-d H:i:s'),
            'type'   => "func:leavechatroommsgcontent"
            );
            ProcessClient::getInstance("dream")->addTask("live_receive_audience_online", $option);
        }
        
        $usersWatchLiveOnline = new UsersWatchLiveOnline();
        $total = $usersWatchLiveOnline->getCount($item, $startime);
        
        if (empty($total)) {
            continue;    
        }
        
        $offset = 0;
        while ($offset < $total) {
            $usersWatchList = $usersWatchLiveOnline->getList($item, $startime, $offset, $step);
            foreach ($usersWatchList as $key => $val) {
                var_dump($val['relateid'] .'--del1--' .$val['uid']);
                $option = array('relateid' => intval($val['relateid']),'uid' => intval($val['uid']));
                ProcessClient::getInstance("dream")->addTask("live_receive_audience_online_delete", $option);
            }
            
            $offset += $step;
        }
        
    } catch (Exception $e) {
        print_r($e);
    }
}

$counter_cache  = Cache::getInstance("REDIS_CONF_COUNTER");
try {
    $usersWatchLiveOnline = new UsersWatchLiveOnline();
    $all_list = $usersWatchLiveOnline->getAllList($startime_half, 0, 30000);
    var_dump(count($all_list));
    $i = 1;
    $online_live = [];
    foreach ($list as $liveid) {
        $liveinfo = $dao_live->getLiveInfo($liveid);
        
        if ($liveinfo['status'] == Live::ACTIVING || $liveinfo['status'] == Live::PAUSED) {
            $online_live[] = $liveid; 
        } else {
            $cache->zRem('heartbeat_live_cache', $liveid);
            $counter_cache->zRem("dreamlive_online_users_redis_key", $liveinfo['uid']);
            var_dump("deleted-cache:". $liveid);
        }
    }
    var_dump(count($online_live));
    foreach ($all_list as $row) {
        $has_bool = in_array($row['relateid'], $online_live);
        if (!$has_bool) {
            var_dump($row['relateid'] .'--del2--' .$row['uid'] . '---'.$i);
            $option = array('relateid' => $row['relateid'],'uid' => $row['uid'], "type" => 'force');
            ProcessClient::getInstance("dream")->addTask("live_receive_audience_online_delete", $option);
        }
        $i++;
    }
    
} catch (Exception $e) {
    print_r($e);
}

var_dump("work done!");
exit;
