<?php
class liveStartDistributeWorker
{
    public static function getMicroTime()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);        
    }
    
    public static function isActive($fid)
    {
        $redis_cache = Cache::getInstance('REDIS_CONF_CACHE');
        
        try {
            $key_active = "zset_stats_dream_liveuser_" . date("Ymd");
            $exist = $redis_cache->zScore($key_active, $fid);
            if (empty($exist)) {
                return false;
            } else {
                return true;
            }
        } catch (Exception $e) {
            Logger::log("redis_error", "live_start_distribute", array("fid" => $fid, "errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
        }
        
        return false;
    }

    static public function execute($value)
    {
        if (empty($value)) {
            return true;
        }
        
        $time_start = self::getMicrotime();

        $author     = $value["params"]["author"];
        $text       = $value["params"]["text"];
        $userid     = $value["params"]["userid"];
        $liveid     = $value["params"]["liveid"];
        $extends     = $value["params"]["extends"];
        $offset     = $value["params"]["offset"];
        $step         = $value["params"]["step"];
        $type         = $value["params"]["type"];
        $type         = empty($type) ? 0 : $type;
        
        Logger::log("live_start_distribute", "get task", array_merge($value["params"], array("uid"=>$author["uid"], "line"=>__LINE__)));
        if (empty($userid) || empty($author)) {
            Logger::log("live_start_distribute", "param error", array_merge($value["params"], array("uid"=>$author["uid"], "line"=>__LINE__)));
            return true;
        }
        $dao_follower = new DAOFollower($userid);
        //$followers = $dao_follower->getFollowersList($offset, $step);
        //$followers = $dao_follower->getUserFollowers($offset, $step);
        $followers = $dao_follower->getFollowers($offset, $step, true);
        
        Logger::log("live_start_distribute", "get follower num succ", array("liveid"=>$liveid, "uid"=>$author["uid"], "follow_num"=>count($followers), "line"=>__LINE__));
        
        $time_end = self::getMicrotime();
        $consume = $time_end - $time_start;

        if (!$followers) {
            $log_info = array(
            'liveid'    => $liveid,
            "userid"    => $userid,
            "step"        => 1,
            "fid"        => 0,
            "slice_start"=> $offset,
            "ext"        => "",
            "line"        => __LINE__,
            "result"    => 0,
            "reason"    => "follower empty"
            );
            ProcessClient::getInstance("dream")->addTask("live_admin_start_push", $log_info);
            Logger::log("live_start_distribute", "follower empty", array("liveid"=>$liveid, "uid"=>$author["uid"], "follower"=>count($followers), "line"=>__LINE__, "consume"=>$consume));
        }

        foreach ($followers as $follower) {
            $fid = $follower["fid"];
            if ($follower['notice'] == 'N') {
                $log_info = array(
                'liveid'    => $liveid,
                "userid"    => $userid,
                "step"        => 1,
                "fid"        => $fid,
                "slice_start"=> $offset,
                "ext"        => "",
                "line"        => __LINE__,
                "result"    => 1,
                "reason"    => "用户设置了关闭提醒"
                );
                ProcessClient::getInstance("dream")->addTask("live_admin_start_push", $log_info);
                return false;
            }
            //if (! self::isActive($fid)) {
            if (false) { //临时性处理， by yjj 替宝国， 宝国回来后自已处理..
                //$dao_push_log  = new DAOLiveStartPush(date("Ymd"));
                
                $log_info = array(
                'liveid'    => $liveid,
                "userid"    => $userid,
                "step"        => 1,
                "fid"        => $fid,
                "slice_start"=> $offset,
                "ext"        => "",
                "line"        => __LINE__,
                "result"    => 0,
                "type"        => $type,
                "reason"    => "user not active"
                );
                
                ProcessClient::getInstance("dream")->addTask("live_admin_start_push", $log_info);
                Logger::log(
                    "live_start_distribute", "user not active", array("liveid"=>$liveid,
                    "uid"=>$author["uid"], "fid"=>$fid, "text"=>$text, "nickname"=>$author["nickname"], "avatar"=>$author["avatar"], "line"=>__LINE__)
                );
                   continue;
            }

            $info = array(
            "author" => $author,
            "text"     => $text,
            "liveid" => $liveid,
            "extends"=> $extends,
            "fid"     => $fid,
            "type"     => $type,
            "offset" => $offset,
            );

            $ret = ProcessClient::getInstance("dream")->addTask("live_start_broadcast_push", $info);


            Logger::log(
                "live_start_distribute", "OK", array("liveid"=>$liveid, "uid"=>$author["uid"],"fid"=>$fid, "text"=>$text,
                "nickname"=>$author["nickname"], "avatar"=>$author["avatar"],"vr"=> json_encode($extends), "delay"=>'', "ret"=>$ret, "line"=>__LINE__)
            );
            $log_info = array(
            'liveid'    => $liveid,
            "userid"    => $userid,
            "step"        => 1,
            "fid"        => $fid,
            "slice_start"=> $offset,
            "ext"        => "",
            "line"        => __LINE__,
            "result"    => $ret?1:0,
            "reason"    => "OK"
            );
            ProcessClient::getInstance("dream")->addTask("live_admin_start_push", $log_info);
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
    $process->addWorker("live_start_broadcast_distribute",  array("liveStartDistributeWorker", "execute"),  50, 2000);
    $process->run();
} catch (Exception $e) {
    ;
}
