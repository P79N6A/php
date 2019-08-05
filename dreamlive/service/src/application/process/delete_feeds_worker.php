<?php
class DeleteFeedsWorker
{
    public static function execute($value)
    {
        $relateid   = $value["params"]["relateid"];
        $type         = $value["params"]["type"];
        $author        = $value["params"]["uid"];
        
        $userfeeds = new UserFeeds();
        $userfeeds->delUserFeeds($author, $relateid, $type);

        /*$L2_cache_key = "L2_cache_live_$relateid";
        $cache = Cache::getInstance("REDIS_CONF_CACHE", $relateid);
        $cache->delete($L2_cache_key);*/
        
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
    $process->addWorker("delete_feeds_worker",  array("DeleteFeedsWorker", "execute"),  4, 20000);
    $process->run();
} catch (Exception $e) {
    ;
}
?>