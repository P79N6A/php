<?php
/**
 * php /home/dream/codebase/service/src/application/process/live_robot_quit_chatroom_worker.php start
 *
 * @author xubaoguo
 */
class QuitChatroomWorker
{
    static public function execute($value)
    {
        if (empty($value)) {
            return true;
        }
        
        $liveid     = $value['params']['liveid'];
        $robot_uid     = $value['params']['userid'];
        
        //$userinfo = User::getUserInfo($robot_uid);
        $rank = new Rank();
        $rank->setRank('audience', "delete", $robot_uid, 1, $liveid);
        $rank->setRank('liverobots', "delete", $robot_uid, 1, $liveid);
        Logger::log("quit_chat_room_worker", "do nothing OK", array("liveid"=>$liveid, "uid"=>$robot_uid, "line"=>__LINE__));
        
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
    $process->addWorker("live_robot_quit",  array("QuitChatroomWorker", "execute"),  200, 20000);
    $process->run();
} catch (Exception $e) {
    ;
}
?>
