<?php
/**
 * 机器人陪聊worker
 */
class robotChatWorker
{
    static public function execute($value)
    {
        if (empty($value)) {
            return true;
        }
        
        $content    = $value["params"]["content"];
        $liveid     = $value["params"]["liveid"];
        $sender     = $value["params"]["sender"];
        $source     = $value["params"]["source"];
        $cache         = Cache::getInstance("REDIS_CONF_CACHE");
        $nowtime     = time();
        $key = "dreamlive_live_user_real_num_".$liveid;
        $result = json_decode($cache->get($key), true);
        $live_num = $result['num']? $result['num'] : 0;
        
        if (empty($content)) {
            return true;
        }
        
        $md5con = md5($content);
        $join_redis_key = "robot_chat_". $liveid . "_" . $sender. '_' . $source . '_' . $md5con;
        if ($cache->INCR($join_redis_key) > 1) {
            return true;
        }
        
        $cache->expire($join_redis_key, 120);
        
        try {
            if ($live_num < 10) {
                if ($source == 1) {
                    //$last_chat_time = $cache->zScore(RobotChat::LAST_CHATING_KEY, $liveid);
                
                    // if (($nowtime-$last_chat_time) > 120) {
                     RobotChat::sendChat($liveid, $sender, $content);
                     //RobotChat::recordLastChat($liveid);
                    // }
                } else {
                    RobotChat::sendChat($liveid, $sender, $content);
                }
            }
            
        } catch (Exception $e) {
            Logger::log("process_error", "robot_chat1", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
        }
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
    $process->addWorker("robot_chat",  array("robotChatWorker", "execute"),  50, 20000);
    $process->run();
} catch (Exception $e) {
    Logger::log("process_error", "robot_chat", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
}
