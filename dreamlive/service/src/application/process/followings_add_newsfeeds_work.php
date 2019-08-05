<?php
/**
 * php /home/yangqing/work/dreamlive/service/src/application/process/followings_add_newsfeeds_work.php -d restart
 *
 * @author yangqing
 */
 
class FollowingsAddNewsfeedsWork
{

    public static function execute($value)
    {
        if (empty($value)) {
            return true;
        }
        
        $uid           = $value["params"]["uid"];
        $type          = $value["params"]["type"];
        $followingsId  = $value["params"]["followingsId"];
        
        
        //用户关注
        if($type=='followings') {
            $result = NewsFeeds::getPullFollowings($uid, $followingsId);
        }
        Logger::log("followings_add_newsfeeds", "add newsfeeds succ", array("uid" => $uid,"type"=>$type,"followingsId"=>$followingsId,"result" => $result,"line" => __LINE__));
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
    $process->addWorker("followings_add_newsfeeds", array("FollowingsAddNewsfeedsWork","execute"), 100, 20000);
    $process->run();
} catch (Exception $e) {
    ;
}
