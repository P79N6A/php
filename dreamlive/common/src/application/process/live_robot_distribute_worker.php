<?php
/**
 * php /home/dream/codebase/service/src/application/process/live_robot_distribute_worker.php start
 *
 * @author xubaoguo
 */
class LiveRobotDistributeWorker
{
    //是否是性感主播
    public static function isSex($uid)
    {
        $redis_cache = Cache::getInstance('REDIS_CONF_CACHE');
        
        try {
            $key_active = "big_liver_keys_set";
            $exist = $redis_cache->zScore($key_active, $uid);
            if (empty($exist)) {
                return false;
            } else {
                return true;
            }
        } catch (Exception $e) {
            
        }
        
        return false;
    }
    
    static public function execute($value)
    {
        if (empty($value)) {
            return true;
        }
        
        $liveid     = $value['params']['liveid'];
        $num         = $value['params']['num'];
        $uid         = $value['params']['uid'];//主播id
        
        if (empty($liveid)) {
            Logger::log(
                "distribute_praise", "no liveid", array("liveid"=>$liveid, "join_time"=>date("Y-m-d H:i:s", time()),
                "quit_time"=>date("Y-m-d H:i:s", time()), "userid"=>$liveid)
            );
            return false;
        }
        
        
        if (self::isSex($uid)) {//主播是否是性感主播
            return true;
        }
        
        $cache = Cache::getInstance("REDIS_CONF_USER");

        
        if (in_array($uid, array(21000196))) {
            $robot_num_min = 10000; //每次进入机器人最小数量
            $robot_num_max = 50000; //每次进入机器人最大数量
        } else {
            $robot_num_min = 10; //每次进入机器人最小数量
            $robot_num_max = 50; //每次进入机器人最大数量
        }
        
        $join_room_spilt = 40;//批次进房间

        if (!empty($num)) {
            $robot_num       = $num;
            if (in_array($uid, array(21000196))) {
                $join_room_min     = 1;  //进入最小时间间隔
                $join_room_max     = 1; //进入最大时间间隔
            } else {
                $join_room_min     = 3;  //进入最小时间间隔
                $join_room_max     = 10; //进入最大时间间隔
            }
            $quit_room_min     = 50; //退出聊天室最小时间
            $quit_room_max     = 1200; //退出聊天室最大时间
        } else {
            $robot_num       = rand($robot_num_min, $robot_num_max);
            
            $join_room_min     = 10;  //进入最小时间间隔
            $join_room_max     = 1000; //进入最大时间间隔
            
            $quit_room_min     = 30; //退出聊天室最小时间
            $quit_room_max     = 1200; //退出聊天室最大时间
            
        }
        
        $robot_total = $cache->zCard("robots");
        
        if (empty($robot_total)) {
            Logger::log(
                "distribute_praise", "no robots", array("liveid"=>$liveid, "join_time"=>date("Y-m-d H:i:s", time()),
                "quit_time"=>date("Y-m-d H:i:s", time()), "userid"=>$liveid)
            );
            return false;
        }
        
        $robots = array();
        for($i = 0; $i < $robot_num; $i++) {
            $offset = rand(0, $robot_total - 1);
            $array = $cache->zRevRange("robots", $offset, $offset+1);
            $robots[] = $array[rand(0, 1)];
        }
        
        array_unique($robots);
        
        Logger::log("distribute_praise", "robots", $robots);

        if (empty($robots)) {
            Logger::log(
                "distribute_praise", "no robots", array("liveid"=>$liveid, "join_time"=>date("Y-m-d H:i:s", time()),
                "quit_time"=>date("Y-m-d H:i:s", time()), "userid"=>$liveid)
            );
            return false;
        }
        
        $process = new ProcessClient("dream");
        
        foreach($robots as $index => $userid) {
            $join_time = ($index < 3 ? rand(0, 10) : rand($join_room_min, $join_room_max)) + time();
            $quit_time = rand($quit_room_min, $quit_room_max) + $join_time;
            //$praise_time = ($join_time + rand(10,20));

            
            $process->addTask("live_robot_action", array("liveid" => $liveid, "userid"=>$userid, "uid" => $uid), $join_time);
            //$process->addTask("live_robot_praise", array("liveid" => $liveid, "userid"=>$userid),  $praise_time);
            $process->addTask("live_robot_quit", array("liveid" => $liveid, "userid"=>$userid), $quit_time);

            Logger::log(
                "distribute_praise", "push succ", array("liveid"=>$liveid, "join_time"=>date("Y-m-d H:i:s", $join_time),
                "quit_time"=>date("Y-m-d H:i:s", $quit_time), "userid"=>$userid)
            );
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
    $process->addWorker("live_distribute_robot",  array("LiveRobotDistributeWorker", "execute"), 50, 20000);
    $process->run();
} catch (Exception $e) {
    ;
}
?>
