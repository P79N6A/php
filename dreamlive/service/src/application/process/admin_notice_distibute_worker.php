<?php
class adminDistributeWorker
{
    public static function getMicroTime()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

    static public function execute($value)
    {
        if (empty($value)) {
            return true;
        }

        $time_start = self::getMicrotime();

        $iamge  = $value["params"]["image"];
        $text   = $value["params"]["text"];
        $sender    = $value["params"]["sender"];
        $offset = $value["params"]["offset"];
        $step     = $value["params"]["step"];
        $type    = $value["params"]["type"];


        Logger::log("admin_push", " start distribute", array("offset" => $offset,"step"=>200,"image"=>$image,"text"=>text,"line" => __LINE__));

        if ($type == 'big') {
            $dao_follower = new DAOFollowSystemAccount();
            $followers = $dao_follower->getList($offset, $step);
        } elseif ($type == 'allzhubo') {
            $dao_follower = new DAOAllLive();
            $followers = $dao_follower->getList($offset, $step);
        }


        $time_end = self::getMicrotime();
        $consume = $time_end - $time_start;


        foreach ($followers as $follower) {
            $fid = $follower["fid"];

            $info = array(
            "image"  => $image,
            "text"     => $text,
            "fid"     => $fid,
            "sender" => $sender,
            "type"   => $type,
            "offset" => $offset,
            );

            $ret = ProcessClient::getInstance("dream")->addTask("admin_notice_push", $info);
        }
        Logger::log("admin_push", "end distribute", array("offset" => $offset,"step"=>200,"count"=>count($followers),"text"=>text,"line" => __LINE__));
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
    $process->addWorker("admin_notice_distribute",  array("adminDistributeWorker", "execute"),  20, 2000);
    $process->run();
} catch (Exception $e) {
    Logger::log("process_error", "admin_notice_distribute", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
}