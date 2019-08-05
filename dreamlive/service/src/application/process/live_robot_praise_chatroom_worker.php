<?php
/**
 * php /home/dream/codebase/service/src/application/process/live_robot_join_chatroom_worker.php start
 *
 * @author xubaoguo
 */
class AutoPraiseWorker
{
    static $default_text = array(
    "praise"     => "%s赞了",
    "join"         => "%s来了",
    );
    
    static public function execute($value)
    {
        if (empty($value)) {
            return true;
        }
        
        $liveid     = $value['params']['liveid'];
        $robot_uid     = $value['params']['userid'];
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        
        $praise_redis_key = "praise_robot_". $liveid . "_" . $robot_uid;
        if ($cache->INCR($praise_redis_key) > 1) {
            return true;
        }
        $cache->expire($praise_redis_key, 60);
        
        Logger::log("auto_praise", 'start', array("liveid"=>$liveid, "uid"=>$robot_uid,"line"=>__LINE__));

        $live = new Live();
        $live_info = $live->getLiveInfo($liveid);
        
        $privacy = $live_info['privacy'];
        //$privacy   = Privacy::hasPrivacyLive($live_info["uid"], $live_info["addtime"], $live_info["endtime"], $liveid);
        
        if (!empty($privacy)) {
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
        $watche_num = Counter::increase(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid, 1);
        
        //赞直播
        $praise_num = rand(1, 2);
        $total =  Counter::increase(Counter::COUNTER_TYPE_LIVE_PRAISES, $liveid, $praise_num);
        Messenger::sendPraise($liveid, $robot_uid, sprintf(self::$default_text['praise'], $userinfo['nickname']), $praise_num, $total, 1, $watche_num, $userinfo['nickname'], $userinfo['avatar'], $userinfo['level']);        
        Logger::log("auto_praise", "send praise success OK", array("liveid"=>strval($liveid), "uid"=>$robot_uid, "addpraise"=>"succ", "line"=>__LINE__));

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
    $process->addWorker("live_robot_praise",  array("AutoPraiseWorker", "execute"),  50, 20000);
    $process->run();
} catch (Exception $e) {
    ;
}
?>
