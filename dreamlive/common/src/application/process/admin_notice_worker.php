<?php
class AdminNoticeWorker
{
    const EACH_STEP         = 200;

    static public function execute($value)
    {
        if (empty($value)) {
            return true;
        }
        
        $text       = $value["params"]["text"];
        $iamge       = $value["params"]["image"];
        $sender        = $value["params"]["sender"];
        $type        = $value["params"]["type"];

        if ($type == 'big') {
            $dao_follower = new DAOFollowSystemAccount();
            $follow_num = $dao_follower->getCount();
        } else if ($type == 'allzhubo') {
            $dao_follower = new DAOAllLive();
            $follow_num = $dao_follower->getCount();
        }
        
        
        $task_num = ceil($follow_num/self::EACH_STEP);
        for ($i = 1; $i <= $task_num; $i++) {
            $offset = ($i!=1) ? (($i-1)*self::EACH_STEP - 1) : 0;
            $info = array(
            "text"     => $text,
            "image"     => $image,
            "offset" => $offset,
            "sender" => $sender,
            "type"   => $type,
            "step"     => self::EACH_STEP,
            );
            $ret = ProcessClient::getInstance("dream")->addTask("admin_notice_distribute", $info);
        }
        Logger::log("admin_push", "add start", array("offset" => $offset,"step"=>200,"image"=>$image,"text"=>$text, "task_num"=>$task_num, "floower_num" => $follow_num,"line" => __LINE__));
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
    $process->addWorker("admin_notice_worker",  array("AdminNoticeWorker", "execute"),  2, 2000);
    $process->run();
} catch (Exception $e) {
    ;
}
?>
