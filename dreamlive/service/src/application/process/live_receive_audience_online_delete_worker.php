<?php
class liveReceiveAudienceOnlineDelete
{
    public static function execute($value)
    {
        if (empty($value)) {
            return true;
        }
        
        $uid      = $value["params"]["uid"];
        $relateid = $value["params"]["relateid"];
        $type       = $value["params"]["type"];
        
        if ($type != 'force') {
            $rongcloud_client = new RongCloudClient();
            $result = $rongcloud_client->queryChatroomUserExist($relateid, $uid);
            Logger::log("live_receive_audience_online", "rongcloud", array("uid" => $uid,"liveid"=>$relateid, 'result' => json_encode($result)));
            if($result['code'] == '200' && $result['isInChrm']) {
                return true;
            }
        }
        
        
        //删除user_liveid_string
        $cache  = Cache::getInstance("REDIS_CONF_COUNTER");
        $online_user_liveid_string_key    = "online_user_liveid_string_".$uid;
        $result = $cache->del($online_user_liveid_string_key);
        
        //删除online_liveid_sortedset有序集合
        $online_liveid_sortedset_key    = "online_liveid_sortedset_".$relateid;
        $result = $cache->ZREM($online_liveid_sortedset_key, $uid);
        
        $online_user_key = "dreamlive_online_users_redis_key";
        $result = $cache->ZREM($online_user_key, $uid);
        
        //删除数据库
        $usersWatchLiveOnline = new UsersWatchLiveOnline();
        $result = $usersWatchLiveOnline->delete($uid, $relateid);
        
        $rank = new Rank();
        $rank->setRank('audience', "delete", $uid, '1', $relateid);
        
        //Logger::log("live_receive_audience_online_delete", "delete", array("uid" => $uid,"liveid"=>$relateid));
        
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
require_once 'message_client/RongCloudClient.php';

try {
    $process = new ProcessClient("dream");
    $process->addWorker("live_receive_audience_online_delete",  array("liveReceiveAudienceOnlineDelete", "execute"),  20, 20000);
    $process->run();
} catch (Exception $e) {
    Logger::log("process_error", "live_receive_audience_online_delete", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
}



?>
