<?php
class answerCashSendWorker
{
    
    static public function execute($value)
    {
        if (empty($value)) {
            return true;
        }
        
        $uid                   = $value["params"]["uid"];
        $roundid            = $value["params"]["roundid"];
        $jsonstr            = $value["params"]["jsonstr"];
        
        if (empty($uid)) {
            return true;
        }
        
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
            
        $one_times_redis_key = "answer_cash_send_only_one_time_". $roundid. "_" . $uid;
        if ($cache->INCR($one_times_redis_key) > 1) {
            return true;
        }

        $cache->expire($one_times_redis_key, 172800);//两天有效期

        try {
            AccountAnswerInterface::cash($jsonstr);
            
            Logger::log("answer_sendcash_log", "winner cashSend :", array("uid"=>$uid, "roundid"=> $roundid,"params" => json_encode($value)));
            
        } catch (Exception $e) {
            $cache->delete($one_times_redis_key);//发奖失败删除key
            Logger::log("answer_sendcash_log", "send error :", array("uid"=>$uid, "roundid"=> $roundid,"params" => json_encode($value),"errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
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
    $process->addWorker("answer_cash_send",  array("answerCashSendWorker", "execute"),  20, 200000);
    $process->run();
} catch (Exception $e) {
    Logger::log("process_error", "answer_cash_send", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
}
?>