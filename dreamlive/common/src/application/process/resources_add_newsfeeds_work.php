<?php
/**
 * php /home/yangqing/work/dreamlive/service/src/application/process/follower_add_newsfeeds_work.php -d restart
 *
 * @author yangqing
 */
class ResourcesAddNewsfeedsWork
{
    private static $step = 200;

    public static function execute($value)
    {
        $uid      = $value["params"]["uid"];
        $type     = $value["params"]["type"];
        $relateid = $value["params"]["relateid"];
        $addtime  = $value["params"]["addtime"];
        
        // 查询用户粉丝个数 拆分任务 分发任务
        //$follow_num = Counter::get(Counter::COUNTER_TYPE_FOLLOWERS, $uid);
        $following = new DAOFollowing($uid);
        $follow_num = $following->countFollowings();
        if ($follow_num > 0) {
            $i = 0;
            while (($offset = $i * self::$step) < $follow_num) {
                 $info = array(
                    'relateid' => $relateid,
                    'author'   => $uid,
                    'type'     => $type,
                    'addtime'  => $addtime,
                    'offset'   => $offset,
                    'step'     => self::$step
                 );
                 include_once 'process_client/ProcessClient.php';
                 ProcessClient::getInstance("dream")->addTask("follower_add_newsfeeds", $info);
                 $i ++;
            }
        }
        Logger::log(
            "resources_add_newsfeeds", "params input", array(
            "relateid"   => $relateid,
            "author"     => $uid,
            "type"       => $type,
            "addtime"    => $addtime,
            "follow_num" => $follow_num,
            "line"       => __LINE__
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
    $process->addWorker("resources_add_newsfeeds", array("ResourcesAddNewsfeedsWork","execute"), 100, 20000);
    $process->run();
} catch (Exception $e) {
    ;
}
