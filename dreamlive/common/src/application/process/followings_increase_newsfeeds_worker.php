<?php
class followingsIncreaseNewsfeeds
{
   
    const LOGIN  = 'login';   //用户登录
    const FOLLOW = 'follow';  //用户关注某人
    
    static public function execute($value)
    {
        $uid         = $value["params"]["uid"];
        $type         = $value["params"]["type"];
        $fid        = $value["params"]["fid"];
        
        switch ($type){
        case self::FOLLOW:
            $result = NewsFeeds::newsFeedsReceive($uid, $fid);
            break;
        case self::LOGIN:
               $result = NewsFeeds::followingsReceive($uid);
            break;
        }
        Logger::log("followings_add_newsfeeds", "add newsfeeds succ", array("uid" => $uid,"type"=>$type,"fid"=>$fid,"result" => $result,"line" => __LINE__));
    }
}

set_time_limit(0);
ini_set('memory_limit', '1G');
ini_set("display_errors", "On");
error_reporting(E_ALL & ~ E_NOTICE & ~ E_WARNING);
$ROOT_PATH = dirname(dirname(dirname(dirname(__FILE__))));
$LOAD_PATH = array(
    $ROOT_PATH . "/src/www",
    $ROOT_PATH . "/config",
    $ROOT_PATH . "/src/application/controllers",
    $ROOT_PATH . "/src/application/models",
    $ROOT_PATH . "/src/application/models/libs",
    $ROOT_PATH . "/src/application/models/dao"
);
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
    $process->addWorker("followings_increase_newsfeeds",  array("followingsIncreaseNewsfeeds", "execute"),  50, 20000);
    $process->run();
} catch (Exception $e) {
    ;
}