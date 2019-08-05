<?php
class liveStartPushWorker
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

        $author     = $value["params"]["author"];
        $text         = $value["params"]["text"];
        $liveid     = $value["params"]["liveid"];
        $extends     = $value["params"]["extends"];
        $fid         = $value["params"]["fid"];
        $offset     = $value["params"]["offset"];
        $delay        = $value["params"]["delay"];
        $type         = $value["params"]["type"];
        $type         = empty($type) ? 0 : $type;
        $pushid        = '';
        $reason        = '';
        
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $push_redis_key = "live_start_". $author["uid"]. "_" . $fid . "_" . $type. "_";
        
        if (empty($type)) {
            if ($cache->INCR($push_redis_key) > 1) {
                return true;
            }
            
            $cache->expire($push_redis_key, 600);
        }
        
        
        Logger::log("live_start_push_followers", "get task-" .$type, array("uid"=>$author["uid"], "text"=>$text,"liveid"=>$liveid, "fid"=>$fid, "delay"=>$delay, "line"=>__LINE__));

        if (!self::isSend($author["uid"], $fid, $liveid, $pushid, $reason)) {
            $ext = !empty($pushid) ? $pushid : "";
            $log_info = array(
            'liveid'    => $liveid,
            "userid"    => $author["uid"],
            "step"        => 2,
            "fid"        => $fid,
            "slice_start"=> $offset, 
            "ext"        => $ext . '--' . $type, 
            "line"        => __LINE__, 
            "result"    => 0, 
            "reason"    => $reason
            );
            ProcessClient::getInstance("dream")->addTask("live_admin_start_push", $log_info);
            Logger::log(
                "live_start_push_followers", "user not require" .$type, array("liveid"=>$liveid,
                "uid"=>$author["uid"], "fid"=>$fid, "pushid"=>$pushid, "text"=>$text, "nickname"=>$author["nickname"], "avatar"=>$author["avatar"], "delay"=>$delay, "line"=>__LINE__)
            );
            return true;
        }
        $ext = !empty($pushid) ? "pushid:".$pushid : "";
        $ext .= !empty($delay) ? "delay:$delay" :" ";
        
        if ($type == 1) {
            $follower_ret = Messenger::sendLiveNotice($fid, $author["uid"], $author["nickname"], $author["avatar"], $author["exp"], $author["level"], $text, $liveid);
        } else {
            $follower_ret = Messenger::sendLiveStart($fid, $text, $liveid, $author["uid"], $author["nickname"], $author["avatar"], $author["medal"], $extends);
        }
        
        //$follower_ret = Messenger::sendLiveStart($fid, $author["uid"], $author["nickname"], $author["avatar"], $author["medal"], Follow::relation($fid, $author["uid"]), $text);

        $time_end = self::getMicrotime();

        $consume = $time_end - $time_start;

        Logger::log(
            "live_start_push_followers", "OK" .$type, array("liveid"=>$liveid, "uid"=>$author["uid"],
            "fid"=>$fid, "pushid"=>$pushid, "text"=>$text, "nickname"=>$author["nickname"], "avatar"=>$author["avatar"],
            "vr"=>     json_encode($extends), "ret"=>$follower_ret, "delay"=>$delay, "line"=>__LINE__, "consume"=>$consume)
        );
        $log_info = array(
        'liveid'    => $liveid,
        "userid"    => $author["uid"],
        "step"        => 2,
        "fid"        => $fid,
        "slice_start"=> $offset,
        "ext"        => $ext. "--" . $type,
        "line"        => __LINE__,
        "result"    => $follower_ret ? 1 : 0,
        "reason"    => "OK"
        );
        ProcessClient::getInstance("dream")->addTask("live_admin_start_push", $log_info);
        return true;
    }

    public static function isSend($uid, $fid, $liveid, &$pushid, &$reason)
    {
        if(empty($uid)) {
            return false;
        }
        $dao_follower = new DAOFollower($uid);
        $is_follower = $dao_follower->isFollower(array($fid));
        if (!isset($is_follower[$fid])) {
            $reason = "not follow";
            Logger::log("live_start_push_followers", "not follow", array("liveid"=>$liveid, "uid"=>$uid, "fid"=>$fid, "line"=>__LINE__));
            return false;
        }

        if(self::isDisturbed($fid)) {
            $reason = "Disturbed";
            Logger::log("live_start_push_followers", "isDisturbed", array("liveid"=>$liveid, "uid"=>$uid, "fid"=>$fid, "line"=>__LINE__));
            return false;
        }

        /*
        $is_forbidden = false;
        $forbidden_ret = Forbidden::isForbidden($fid);
        $is_forbidden = $forbidden_ret["result"];

        if($is_forbidden){
        $reason = "forbidden";
        PLogger::log("live_start_broadcast_followers", "is_forbidden", array("liveid"=>$liveid, "uid"=>$uid, "fid"=>$fid, "line"=>__LINE__));
        return false;
        }*/ //注释 判断用户封禁状态

        $dao_profile = new DAOProfile($uid);
        $result = $dao_profile->getUserProfile("pushid");

        $pushid = empty($result["pushid"]) ? "" : trim($result["pushid"]);
        
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key = "start:push:".$liveid.":".$fid;
        if(($user_times = $cache->incr($key)) > 1) {
            
            $reason = "had sended $fid";
            Logger::log("live_start_push_followers", "had send", array("liveid"=>$liveid, "uid"=>$uid, "fid"=>$fid, "user_times"=>$user_times, "line"=>__LINE__));
            return false;
        }
        $cache->expire($key, 86400);
        if(!empty($pushid)) {
            $pushid_key = "start:push:".$liveid.":".$pushid;
            if(($token_times = $cache->incr($pushid_key)) > 1) {
                
                $reason = "pushid sented $pushid";
                Logger::log("live_start_push_followers", "pushid sent", array("liveid"=>$liveid, "uid"=>$uid, "fid"=>$fid, "pushid"=>$pushid, "token_times"=>$token_times, "line"=>__LINE__));
                return false;
            }
            $cache->expire($pushid_key, 86400);
        }

        return true;
    }

    public static function isDisturbed($userid)
    {
        $dao_profile = new DAOProfile($userid);
        $result = $dao_profile->getUserProfile("timezone");

        $timezone = empty($result["timezone"]) ? 8 : $result["timezone"];

        $time = (is_numeric($timezone) && $timezone != 8)
        ? (
        $timezone > 8
        ? strtotime("+ " . ($timezone - 8) . " hours")
        : strtotime("- " . ($timezone < 0 ? (abs($timezone) + 8) : (8 - $timezone)) . " hours")
        )
        : time();

        $h = date("H", $time);

        if($h < 22 && $h > 8) {
            return false;
        }

        $result = $dao_profile->getUserProfile("option_dnd");

        if (empty($result["option_dnd"]) || "Y" != $result["option_dnd"]) {
            return false;
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
    $ROOT_PATH."/src/application/models/dao",
    $ROOT_PATH."/src/application/models/libs/stream_client"
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
    $process->addWorker("live_start_broadcast_push",  array("liveStartPushWorker", "execute"),  100, 20000);
    $process->run();
} catch (Exception $e) {
    ;
}
