<?php
class adminNoticeStartPushWorker
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

        $image         = $value["params"]["image"];
        $text         = $value["params"]["text"];
        $fid         = $value["params"]["fid"];
        $sender        = $value["params"]["sender"];
        $type        = $value["params"]["type"];

        //return true;
        try {
            $cache = Cache::getInstance("REDIS_CONF_CACHE");
            $elements = $cache->zRevRange("zset_push_white_list", 0, -1);
            //print_r($elements);exit;
        } catch (Exception $e) {

        }

        if (!empty($elements)) {
            if (in_array($fid, $elements)) {
                return true;
            }
        } else {
            if (in_array($fid, array(20756145,10899916,20366498,22938588,20557626,10929176))) {
                return true;
            }
        }


        $user = new User();
        $sender_info = $user->getUserInfo($sender);

        include_once 'message_client/RongCloudClient.php';
        $rongcloud_client = new RongCloudClient();
        $extends= array(
        "userid"=>$sender,
        "nickname"=>!empty($sender_info['nickname']) ? $sender_info['nickname'] : '未知',
        "avatar"=>!empty($sender_info['avatar']) ? $sender_info['avatar'] : '',
        "level"=>!empty($sender_info['level']) ? $sender_info['level'] : 0,
        "relation"=>Follow::relation($fid, $sender)
        );
        $json = ['contentType' => 0, "content" => $text, "description" => ""];
        $wrappers = array(
        "userid" => $fid,
        "type" => 400,
        "text" => json_encode($json),
        "time" => time(),
        "expire" => 180,
        "extra" => $extends
        );

        $wrapper = array();
        $traceid = md5(serialize($wrappers));

        $wrappers["traceid"] = $traceid;
        $wrapper['content'] = $wrappers;

        if ($type == 'big') {
            $result = $rongcloud_client->sendBigNotice($fid, $sender, $text, $image, json_encode($wrapper));
        } elseif ($type == 'allzhubo') {
            $result = Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $fid, $text, $text, 0);
        }


        $log_info = array(
        'liveid'    => 2222222,
        "userid"    => $sender,
        "step"        => 3,
        "fid"        => $fid,
        "slice_start"=> 0,
        "ext"        => $text,
        "line"        => __LINE__,
        "result"    => $result? 1 : 0,
        "reason"    => "OK--{$type}"
        );

        ProcessClient::getInstance("dream")->addTask("live_admin_start_push", $log_info);


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
    $process->addWorker("admin_notice_push",  array("adminNoticeStartPushWorker", "execute"),  300, 20000);
    $process->run();
} catch (Exception $e) {
    Logger::log("process_error", "admin_notice_push", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
}
//$value = array('params' => array('fid' => 10899916));
//adminNoticeStartPushWorker::execute($value);