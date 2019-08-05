<?php
class liveReceiveAudienceOnline
{
    public static function execute($value)
    {
        if (empty($value)) {
            return true;
        }
        
        $type    = $value["params"]["type"];
        $uid     = $value["params"]["uid"];
        $liveid  = $value["params"]["liveid"];
        $addtime = $value["params"]["time"];

        if($type=='func:joinchatroommsgcontent') {
            //写user_liveid_string
            $cache  = Cache::getInstance("REDIS_CONF_COUNTER");
            $online_user_liveid_string_key    = "online_user_liveid_string_".$uid;
            $cache->SET($online_user_liveid_string_key, $liveid);
            $cache->expire($online_user_liveid_string_key, 7200);
            
            $online_user_key = "dreamlive_online_users_redis_key";
            $cache->ZADD($online_user_key, $liveid, $uid);
            
            //写online_liveid_sortedset有序集合
            $online_liveid_sortedset_key    = "online_liveid_sortedset_".$liveid;
            $score = time();
            $cache->ZADD($online_liveid_sortedset_key, $score, $uid);
            $cache->expire($online_liveid_sortedset_key, 18000);
            
            //写入数据表
            $option = array(
                'uid'      => $uid,
                'relateid' => $liveid,
                'addtime'  => $addtime
            );
            $userOnline = new UsersWatchLiveOnline();
            $result = $userOnline->addData($option);
        }
        
        if($type=='func:leavechatroommsgcontent') {
            //删除user_liveid_string
            $cache  = Cache::getInstance("REDIS_CONF_COUNTER");
            $online_user_liveid_string_key    = "online_user_liveid_string_".$uid;
            $cache->del($online_user_liveid_string_key);
        
            //删除online_liveid_sortedset有序集合
            $online_liveid_sortedset_key    = "online_liveid_sortedset_".$liveid;
            $cache->ZREM($online_liveid_sortedset_key, $uid);
            
            $online_user_key = "dreamlive_online_users_redis_key";
            $cache->ZREM($online_user_key, $uid);
            
            //删除数据表
            $userOnline = new UsersWatchLiveOnline();
            $userOnline->delete($uid, $liveid);
        }
        
        Logger::log("live_receive_audience_online", "receive", array("uid" => $uid,"liveid"=>$liveid,"time"=>$addtime, "type" => $type));
        
        return true;
    }
}


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

class UsersWatchLiveOnline extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_MESSAGE");
        $this->setTableName("user_watch_live_online");
    }
    
    public function addData($option)
    {
        return $this->replace($this->getTableName(), $option);
    }
    
    public function delete($uid,$liveid)
    {
        $sql = "delete from {$this->getTableName()} where uid=? and relateid=?";
        return $this->Execute($sql, array('uid'=>$uid,'relateid'=>$liveid)) ? true : false;
    }
}

function __autoload($classname)
{
    $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    include_once "$classname.php";
}

require $ROOT_PATH."/config/server_conf.php";
require_once 'process_client/ProcessClient.php';

try {
    $process = new ProcessClient("dream");
    $process->addWorker("live_receive_audience_online",  array("liveReceiveAudienceOnline", "execute"),  20, 20000);
    $process->run();
} catch (Exception $e) {
    ;
}
?>
