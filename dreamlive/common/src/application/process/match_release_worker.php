<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018/1/22
 * Time: 15:54
 */
class MatchReleaseWorker
{
    const GIFTID   = 3860;
    static public function execute($value)
    {
        $giftid            = $value["params"]['giftid'];
        $receiver          = $value["params"]['receiver'];
        if($giftid == MatchReleaseWorker::GIFTID) {
            Match::release(0, '送礼移除小黑屋', $receiver);
            $content = "您被关闭小黑屋的记录被删除，你又可以开始和小伙伴pk啦！";
            Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $receiver, $content, $content, 0);
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
    $process->addWorker("match_release_worker",  array("MatchReleaseWorker", "execute"),  50, 20000);
    $process->run();
} catch (Exception $e) {
    ;
}
