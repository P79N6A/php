<?php

class UserChannelWorker
{
    public function execute($value)
    {
        $uid = $value["params"]["uid"];
        $channel = $value["params"]["channel"];
        $ip = $value["params"]["ip"];
        $active_time = $value["params"]["active_time"];
        $platform = $value["params"]["platform"];
        $brand = $value["params"]["brand"];
        $model = $value["params"]["model"];
        $deviceid = $value["params"]["deviceid"];

        ShortlinkNotice::processChannel($uid, $channel, $ip, $active_time, $platform, $brand, $model, $deviceid);

        return true;
    }
}

$ROOT_PATH = dirname(dirname(dirname(dirname(__FILE__))));
$LOAD_PATH = [
    $ROOT_PATH . "/src/www",
    $ROOT_PATH . "/config",
    $ROOT_PATH . "/src/application/controllers",
    $ROOT_PATH . "/src/application/models",
    $ROOT_PATH . "/src/application/models/libs",
    $ROOT_PATH . "/src/application/models/dao",
    $ROOT_PATH . "/src/application/models/task",
];
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $LOAD_PATH));

function __autoload($classname)
{
    $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    include_once "$classname.php";
}

require $ROOT_PATH . "/config/server_conf.php";

require_once 'process_client/ProcessClient.php';

try {
    $process = new ProcessClient("dream");

    $process->addWorker("channel_shortlink", ["UserChannelWorker", "execute"], 10, 2000);

    $process->run();
} catch (Exception $e) {
}

