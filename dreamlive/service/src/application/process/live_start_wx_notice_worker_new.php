<?php

class LiveStartWxNoticeWorker
{
    static public function execute($value)
    {
        var_dump($value);
        $userid         = $value["params"]["uid"];
        $text           = $value["params"]["text"];
        $liveid         = $value["params"]["liveid"];
        $extends         = $value["params"]["extends"];
        $type             = $value["params"]["type"];
        $type             = empty($type) ? 0 : $type;
        $text             = empty($text) ? '' : $text;

        try{
            $userinfo = User::getUserInfo($userid);

            $users=WxNotice::getAllNoticeUsers();
            foreach ($users as $i){
                $flag = Follow::isFollower($userid, $i['uid']);
                if ($flag[$i['uid']]) {
                    WxNotice::sendLiveStart($i['uid'], $liveid, $userinfo["nickname"], $userinfo["signature"]);
                }
            }
        }catch (Exception $e){
            // todo log
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
        $ROOT_PATH."/src/application/models/dao",
        $ROOT_PATH."/src/application/models/libs/payment_client",
        $ROOT_PATH."/src/application/models/libs/stream_client",
        $ROOT_PATH."/src/application/models/libs/oauth_client",
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
    $process->addWorker("live_start_wx_new",  array("LiveStartWxNoticeWorker", "execute"),  10, 20000);
    $process->run();
} catch (Exception $e) {
    Logger::log("process_error", "live_start_wx_notice", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
}
?>