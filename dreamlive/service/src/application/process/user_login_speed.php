<?php
class UserLoginSpeed
{
    public static function execute($value)
    {
        $region = $value["params"]["region"];
        $uid    = $value["params"]["uid"];
        $ip     = $value["params"]["ip"];
        $lng    = $value["params"]["lng"];
        $lat    = $value["params"]["lat"];
        
        
        $obj     = new Stream();
        $partner = $obj->getPartner($uid);
        $isSpeed = StreamDispatch::isSpeed($uid, $lng, $lat, $partner);
        if($isSpeed) {
            /**
            $stream = StreamDispatch::getDispatch($region, $uid, $ip);
            Logger::log("user_login_speed", "add end", array("uid" => $uid,"region"=>$region,"ip"=>$ip,"result" => json_encode($stream),"line" => __LINE__));
            Messenger::sendUserTestSpeed($uid,$stream);
            */
            
            $stream  = StreamDispatch::getDispatch($region, $uid, $ip, $partner);
            Logger::log("user_login_speed", "add end", array("uid" => $uid,"region"=>$region,"ip"=>$ip,"result" => json_encode($stream),"line" => __LINE__));
            Messenger::sendUserTestSpeedNews($uid, $stream);
        } else {
            Logger::log("user_login_speed", "send fail", array("uid" => $uid,"region"=>$region,"ip"=>$ip));
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
    $ROOT_PATH . "/src/www",
    $ROOT_PATH . "/config",
    $ROOT_PATH . "/src/application/controllers",
    $ROOT_PATH . "/src/application/models",
    $ROOT_PATH . "/src/application/models/libs",
    $ROOT_PATH . "/src/application/models/dao",
    $ROOT_PATH . "/src/application/models/libs/stream_client"
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
    $process->addWorker("user_login_speed",  array("UserLoginSpeed", "execute"),  20, 20000);
    $process->run();
} catch (Exception $e) {
    ;
}
?>
