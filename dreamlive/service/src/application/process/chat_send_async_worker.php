<?php
class chatSendAsyncWorker
{
    
    static public function execute($value)
    {
        if (empty($value)) {
            return true;
        }
        
        $liveid               = $value["params"]["liveid"];
        $content               = $value["params"]["content"];
        $sender                = $value["params"]["sender"];
        $watches            = $value["params"]["watches"];
        $replace_keyword    = $value["params"]["replace_keyword"];
        $relateids            = $value["params"]["relateids"];
        $type                = $value["params"]["type"];
        $user_ip              = $value['params']["ip"];
        $is_multi              = $value['params']["is_multi"];
        $deviceid              = $value['params']["deviceid"];
        
        $chat = new Chat();
        try {
            if (in_array($liveid, [1111])) {
                $chat->multiSend($liveid, $sender, $type, $content, $watches, $replace_keyword, $relateids, $user_ip, $deviceid);
            } else {
                $chat->send($liveid, $sender, $type, $content, $watches, $replace_keyword, $relateids, $user_ip, $deviceid, $is_multi);
            }
        }catch (Exception $e) {
            Logger::log("process_error", "chat_send_async1", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
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
    $process->addWorker("chat_send_async",  array("chatSendAsyncWorker", "execute"),  100, 2000);
    $process->run();
} catch (Exception $e) {
    Logger::log("process_error", "chat_send_async", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
}
?>
