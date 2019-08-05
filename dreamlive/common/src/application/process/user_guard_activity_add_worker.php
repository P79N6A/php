<?php
/**
 * php /home/dream/codebase/dreamlive/service/src/application/process/user_guard_activity_add_worker.php -d restart
 *
 * @author yangqing
 */
 
class userGuardActivityAddWorker
{
    public static function execute($value)
    {
        $uid      = $value["params"]["uid"];
        $relateid = $value["params"]["relateid"];
        $type     = $value["params"]["type"];
        $liveid   = $value["params"]["liveid"];
        
        $starTime = '2017-10-20 21:00:00';
        $endTime  = '2017-11-01 00:00:00';
        
        $date = date('Y-m-d H:i:s');
        if($date<$starTime || $date>$endTime) {
            Logger::log("user_guard_activity_add_worker", "not start", array("uid" => $uid,"relateid"=>$relateid,"type"=>$type,"starTime"=>$starTime,"endTime"=>$endTime,"time"=>$date,"line" => __LINE__));
            return;
        }
        $userGuardDetailActivicy = new UserGuardDetailActivicy();
        $list = $userGuardDetailActivicy->getList($relateid, $starTime, $endTime);
        if(empty($list)) {
            return;
        }
        $startime = $list[0]['addtime'];
        $expires  = 0;
        foreach($list as $item){
            if($item['type'] == 5) {
                $expires += 1;
            }
            if($item['type'] == 10) {
                $expires += 12;
            }
        }
        
        $arrTemp  = array();
        //活动专属封面装饰处理
        $option = array(
            'uid'      => $relateid,
            'type'     => 1,
            'modtime'  => date("Y-m-d H:i:s"),
            'startime' => $startime,
            'endtime'  => date("Y-m-d H:i:s", strtotime($startime . " +$expires day"))
        );
        $arrTemp[] = $option;
        $num = $type == 10 ? 12 : 1;
        $cache  = Cache::getInstance("REDIS_CONF_COUNTER");
        $key    = "user_guard_activity_message_".UserMedal::KIND_SKIN_COVER."_".$relateid;
        $result = $cache->get($key);
        if(!$result) {
            $content = "恭喜你完成守护之战第一阶段，你的直播间专属封面会自动加成时间（".($num*24)."小时），下个阶段礼物更加精彩，再接再厉，感谢你的支持，希望你在追梦有个愉快的直播之旅";
        }else{
            $content = "恭喜你直播间特效自动累积时间（".($num*24)."小时），祝您有个愉快的直播之旅！";
        }
        Logger::log("user_guard_activity_add_worker", "skin_cover", array("uid" => $uid,"relateid"=>$relateid,"type"=>$type,"time"=>$date,"expires"=>$expires,"redis_num"=>$result,"line" => __LINE__));
        $cache->INCRBY($key, 1);
        Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $relateid, '守护活动奖励通知', $content, 0, $extends = array());
        
        //FaceU特效
        if($expires>=3) {
            $option = array(
                'uid'      => $relateid,
                'type'     => 2,
                'modtime'  => date("Y-m-d H:i:s"),
                'startime' => $startime,
                'endtime'  => date("Y-m-d H:i:s", strtotime($startime . " +30 day"))
            );
            $arrTemp[] = $option;
            
            $cache  = Cache::getInstance("REDIS_CONF_COUNTER");
            $key    = "user_guard_activity_message_".UserMedal::KIND_SKIN_FACEU."_".$relateid;
            $result = $cache->get($key);
            if(!$result) {
                try {
                    $putFaceu = Bag::putFaceu($relateid, 38, 30*86400);
                } catch (Exception $e) {
                
                }
                $content = "恭喜你完成守护之战第二阶段，Face U（天使之爱）专属脸萌已经启动，下个阶段礼物更加精彩，再接再厉。";
                Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $relateid, '守护活动奖励通知', $content, 0, $extends = array());
            }
            Logger::log("user_guard_activity_add_worker", "skin_faceu", array("uid" => $uid,"relateid"=>$relateid,"type"=>$type,"time"=>$date,"expires"=>$expires,"redis_num"=>$result,"putFaceu"=>$putFaceu,"line" => __LINE__));
            $cache->INCRBY($key, 1);
        }
        
        //活动专属关注弹窗和按钮样式
        if($expires>=10) {
            $option = array(
                'uid'      => $relateid,
                'type'     => 3,
                'modtime'  => date("Y-m-d H:i:s"),
                'startime' => $startime,
                'endtime'  => date("Y-m-d H:i:s", strtotime($startime . " +30 day"))
            );
            $arrTemp[] = $option;
            
            $cache  = Cache::getInstance("REDIS_CONF_COUNTER");
            $key    = "user_guard_activity_message_".UserMedal::KIND_SKIN_FOLLOW."_".$relateid;
            $result = $cache->get($key);
            if(!$result) {
                $content = "恭喜你完成守护之战终极阶段，你的专属荣耀特效已经全部启动，感谢你的支持，希望你在追梦有个愉快的直播之旅。";
                Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $relateid, '守护活动奖励通知', $content, 0, $extends = array());
            }
            Logger::log("user_guard_activity_add_worker", "skin_follow", array("uid" => $uid,"relateid"=>$relateid,"type"=>$type,"time"=>$date,"expires"=>$expires,"redis_num"=>$result,"line" => __LINE__));
            $cache->INCRBY($key, 1);
        }

        //写数据库
        $userGuardActivity = new userGuardActivity();
        foreach($arrTemp as $item){
            $result = $userGuardActivity->addData($item);
            if($result) {
                if($item['type'] == 1) {
                    $kind = UserMedal::KIND_SKIN_COVER;
                }
                if($item['type'] == 2) {
                    $kind = UserMedal::KIND_SKIN_FACEU;
                }
                if($item['type'] == 3) {
                    $kind = UserMedal::KIND_SKIN_FOLLOW;
                }
                $ret = UserMedal::addUserMedal($item['uid'], $kind, 1);
                User::reload($item['uid']);
            }
        }

        $cache  = Cache::getInstance("REDIS_CONF_COUNTER");
        $key    = "user_guard_activity_message_user_".$uid;
        $result = $cache->get($key);
        if(!$result) {
            try {
                $putRide = Bag::putRide($uid, 39, 30*86400);
            } catch (Exception $e) {
            
            }
            $content = "恭喜你成为守护骑士，您的坐骑（七色彩云）启动，为期一个月，希望你在追梦有个愉快的直播之旅。";
            Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $uid, '守护活动奖励通知', $content, 0, $extends = array());
        }
        Logger::log("user_guard_activity_add_worker", "user_guard", array("uid" => $uid,"relateid"=>$relateid,"type"=>$type,"time"=>$date,"expires"=>$expires,"redis_num"=>$result,"putRide"=>$putRide,"line" => __LINE__));
        $cache->INCRBY($key, 1);
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

class UserGuardDetailActivicy extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_MESSAGE");
        $this->setTableName("user_guard_detail");
    }

    public function getList($relateid,$starTime, $endTime)
    {
        $sql = "SELECT * FROM " . $this->getTableName() . " where relateid=? and addtime>=?  and addtime<? order by addtime asc " ;
        return $this->getAll($sql, array($relateid,$starTime,$endTime));
    }
}

class userGuardActivity extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_MESSAGE");
        $this->setTableName("user_guard_activity");
    }

    public function addData($option)
    {
        return $this->replace($this->getTableName(), $option);
    }
}

try {
    $process = new ProcessClient("dream");
    $process->addWorker("user_guard_activity_add_worker", array("userGuardActivityAddWorker","execute"), 50, 20000);
    $process->run();
} catch (Exception $e) {
    ;
}
