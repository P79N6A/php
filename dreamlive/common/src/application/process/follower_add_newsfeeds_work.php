<?php
/**
 * php /home/yangqing/work/dreamlive/service/src/application/process/follower_add_newsfeeds_work.php -d restart
 *
 * @author yangqing
 */
class FollowerAddNewsfeedsWork
{

    public static function execute($value)
    {
        $relateid = $value["params"]["relateid"];
        $author   = $value["params"]["author"];
        $type     = $value["params"]["type"];
        $addtime  = $value["params"]["addtime"];
        $offset   = $value["params"]["offset"];
        $step     = $value["params"]["step"];
        // 七天活跃用户
        $key_active = "dream_activeuser_day_prewrite_" . date("Ymd", time());
        $redis = Cache::getInstance('REDIS_CONF_CACHE');
        
        $follower = new DAOFollower($author);
        $follower_list = $follower->getFollowersList($offset, $step);
        foreach ($follower_list as $item) {
            $active = $redis->SISMEMBER($key_active, $item['uid']);
            if ($active) {
                $newsFeeds = new DAONewsFeeds($item['fid']);
                $newsFeeds->addNewsFeeds($item['fid'], $type, $relateid, $author);
            }
        }
        Logger::log(
            "followor_add_newsfeeds", "add newsfeeds succ", array(
            "relateid" => $relateid,
            "author"   => $author,
            "type"     => $type,
            "addtime"  => $addtime,
            "offset"   => $offset,
            "step"     => $step,
            "line"     => __LINE__
            )
        );
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
    $process->addWorker("follower_add_newsfeeds", array("FollowerAddNewsfeedsWork","execute"), 100, 20000);
    $process->run();
} catch (Exception $e) {
    ;
}
