<?php
/**
 * php /home/dream/codebase/service/src/application/process/live_robot_join_chatroom_worker.php start
 *
 * @author xubaoguo
 */
class AutoJoinPraiseWorker
{
    static $default_text = array(
    "praise"     => "%s赞了",
    "join"         => "%s来了",
    );
    
    //是否是性感主播
    public static function isSex($uid)
    {
        $redis_cache = Cache::getInstance('REDIS_CONF_CACHE');
        
        try {
            $key_active = "big_liver_keys_set";
            $exist = $redis_cache->zScore($key_active, $uid);
            if (empty($exist)) {
                return false;
            } else {
                return true;
            }
        } catch (Exception $e) {
            
        }
        
        return false;
    }
    
    static public function execute($value)
    {
        if (empty($value)) {
            return true;
        }
        
        $liveid     = $value['params']['liveid'];
        $robot_uid     = $value['params']['userid'];
        $uid         = $value['params']['uid'];//主播id
        
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        
        $join_redis_key = "join_robot_". $liveid . "_" . $robot_uid;
        if ($cache->INCR($join_redis_key) > 1) {
            return true;
        }
        
        $cache->expire($join_redis_key, 60);
        
        if (self::isSex($uid)) {//主播是否是性感主播
            return true;
        }
        
        Logger::log("auto_praise", 'start', array("liveid"=>$liveid, "uid"=>$robot_uid,"line"=>__LINE__));

        $live = new Live();
        $live_info = $live->getLiveInfo($liveid);
        
        $privacy = Privacy::getPrivacyInfoByLiveInfo($live_info['privacy']);
        
        if (!empty($privacy) && isset($privacy['privacyid'])) {
            return true;
        }
        
        if (!in_array($live_info['status'], array(Live::ACTIVING, Live::PAUSED))) {//不在直播状态退出
            return true;
        }
        
        if (empty($live_info)) {
            Logger::log("auto_praise", 'live not exist or offline ', array("liveid"=>$liveid, "uid"=>$robot_uid, "line"=>__LINE__));
            return true;
        }

        $userinfo = User::getUserInfo($robot_uid);
        //Logger::log("auto_praise", 'userinfo ', $userinfo);
        //加入直播
        $watche_num = Counter::increase(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid, rand(3, 15));
        Messenger::sendLiveJoinMessage($liveid, $robot_uid, sprintf(self::$default_text['join'], $userinfo['nickname']), Messenger::MESSAGE_TYPE_CHATROOM_JOIN, 0, array());
        Logger::log("auto_praise", "send hello success OK", array("liveid"=>$liveid, "uid"=>$robot_uid,  "addjoin"=>"succ", "line"=>__LINE__));
        $rank = new Rank();
        $rank->setRank('audience', "increase", $robot_uid, $userinfo['level'], $liveid);
        $rank->setRank('liverobots', "increase", $robot_uid, $userinfo['level'], $liveid);
        //RobotChat::sayHello($liveid);
        //赞直播
        //         $praise_num = rand(3,15);
        //         $total =  Counter::increase(Counter::COUNTER_TYPE_LIVE_PRAISES, $liveid, $praise_num);
        //         Messenger::sendPraise($liveid, $robot_uid, sprintf(self::$default_text['praise'], $userinfo['nickname']), $praise_num, $total, 1,$watche_num, $userinfo['nickname'], $userinfo['avatar'], $userinfo['level']);        
        //         Logger::log("auto_praise", "send praise success OK", array("liveid"=>strval($liveid), "uid"=>$robot_uid, "addpraise"=>"succ", "line"=>__LINE__));

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

function __autoload($classname)
{
    $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    include_once "$classname.php";
}

require $ROOT_PATH."/config/server_conf.php";
require_once 'process_client/ProcessClient.php';

try {
    $process = new ProcessClient("dream");
    $process->addWorker("live_robot_action",  array("AutoJoinPraiseWorker", "execute"),  100, 20000);
    $process->run();
} catch (Exception $e) {
    ;
}
?>
