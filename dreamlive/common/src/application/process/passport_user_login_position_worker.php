<?php
class UserLoginPositionWorker
{
    public static function execute($value)
    {
        $uid = $value["params"]["uid"];
        $ip  = $value["params"]["ip"];
        $lng = $value["params"]["lng"];
        $lat = $value["params"]["lat"];
        $platform = $value["params"]["platform"];

        if(!in_array($platform, array("ios", "android", "obs"))) {
            return true;
        }
        list($province, $city, $district) = Util::ip2Location($ip);

        if(!$province) {
            Logger::log(
                "passport_user_login_position_err", "error", array(
                "platform" => $platform,
                "uid"      => $uid,
                "ip"       => $ip,
                "lng"      => $lng,
                "lat"      => $lat,
                "province" => $province,
                )
            );
            $province = '其他';
        }
        $result = array(
            "uid"      => $uid,
            "province" => $province,
            "lng"      => $lng,
            "lat"      => $lat,
        );

        $cache = Cache::getInstance('REDIS_CONF_USER');
        $key = 'user_login_position_'.$uid;

        $cache->delete($key);
        $cache->setex($key, 172800, json_encode($result));

        Logger::log(
            "passport_user_login_position", "ok", array(
            "platform" => $platform,
            "uid"      => $uid,
            "ip"       => $ip,
            "lng"      => $lng,
            "lat"      => $lat,
            "province" => $province,
            )
        );

        return true;
    }
}

$ROOT_PATH = dirname(dirname(dirname(dirname(__FILE__))));
$LOAD_PATH = array(
    $ROOT_PATH . "/src/www",
    $ROOT_PATH."/config",
    $ROOT_PATH."/src/application/controllers",
    $ROOT_PATH."/src/application/models",
    $ROOT_PATH."/src/application/models/libs",
    $ROOT_PATH."/src/application/models/dao",
    $ROOT_PATH."/src/application/models/task"
);
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $LOAD_PATH));

function __autoload($classname)
{
    $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    include_once "$classname.php";
}
require $ROOT_PATH."/config/server_conf.php";

require_once 'process_client/ProcessClient.php';

try{
    $process = new ProcessClient("dream");

    $process->addWorker("passport_user_login_position",  array("UserLoginPositionWorker", "execute"),  10, 2000);

    $process->run();
} catch (Exception $e) {
}

