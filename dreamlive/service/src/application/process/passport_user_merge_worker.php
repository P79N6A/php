<?php
class UserMerge
{
    public static function execute($value)
    {
        $newuid = $value["params"]["newuid"];
        $olduid = $value["params"]["olduid"];

        $logDao = new DAOUserMergeLog();
        $logDao->addLog($newuid, $olduid);

        self::mergeFollow($newuid, $olduid);
        self::mergeTicket($newuid, $olduid);
        // self::mergeMedals($newuid, $olduid);
        self::mergeProtect($newuid, $olduid);
        self::mergeLive($newuid, $olduid);

        User::reload($newuid);
        User::reload($olduid);

        return true;
    }
    public static function mergeFollow($newuid, $olduid)
    {
        /*{{{*/
        // 1 粉丝 
        $dao = new DAOFollower($newuid);
        $newfollowers = $dao->getFollowers();

        $dao = new DAOFollower($olduid);
        $oldfollowers = $dao->getFollowers();

        $followers = self::diff2($newfollowers, $oldfollowers);

        // 关注
        $dao = new DAOFollowing($newuid);
        $newfollowings = $dao->getFollowings();

        $dao = new DAOFollowing($olduid);
        $oldfollowings = $dao->getFollowings();

        $followings = self::diff2($newfollowings, $oldfollowings);

        foreach($followers as $k=>$v){
            $result['followers'] .=  $v.',';
            Follow::addFollow($v, $olduid, 0);
        }
        $logDao = new DAOUserMergeLog();
        $logDao->addLogByLogid($newuid, $result);

        foreach($followings as $k=>$v){
            $result['followings'] .=  $v.',';
            Follow::addFollow($olduid, $v, 0);
        }

        $logDao->addLogByLogid($newuid, $result);

    }/*}}}*/
    public static function mergeTicket($newuid, $olduid)
    {
        $new_amount = Counter::get(Counter::COUNTER_TYPE_PAYMENT_RECEIVE_GIFT, $newuid);

        $result['ticket'] = $new_amount;

        Counter::increase(Counter::COUNTER_TYPE_PAYMENT_RECEIVE_GIFT, $olduid, $new_amount);
        
        $logDao = new DAOUserMergeLog();
        $logDao->addLogByLogid($newuid, $result);
    }
    public static function mergeMedals($newuid, $olduid)
    {
        $medals    = UserMedal::getUserMedals($newuid);
        $oldmedals = UserMedal::getUserMedals($olduid);

        if(count($medals)>0) {
            foreach($medals as $k=>$v){
                $flag = false;
                foreach($oldmedals as $k2=>$v2){
                    if($v['kind'] == $v2['kind'] && $v['medal'] == $v2['medal']) {
                        $flag = true;
                        break;
                    }
                }
                if(!$flag) {
                    $result['medals'] .= $v['kind'].','.$v['medal'].'|';
                    $medals = UserMedal::addUserMedal($olduid, $v['kind'], $v['medal']);
                }
            }
        }
        
        $logDao = new DAOUserMergeLog();
        $logDao->addLogByLogid($newuid, $result);
    }
    public static function mergeProtect($newuid, $olduid)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $total = $cache->zRevRange("protect_".$newuid, 0, -1);

        foreach($total as $k=>$v){
            $score = $cache->zScore("protect_".$newuid, $v);
            $cache->zIncrBy("protect_".$olduid, $score, $v);
            $result['protect'] .= $score.','.$v.'|';
        }

        $logDao = new DAOUserMergeLog();
        $logDao->addLogByLogid($newuid, $result);
    }
    public static function mergeLive($newuid, $olduid)
    {
        var_dump($newuid, $olduid);
        $sql = "select liveid from live where uid=? ";
        $dao = new DAOLive();
        $dao->setDebug(true);
        $res = $dao->getAll($sql, array($newuid), false);

        foreach($res as $k=>$v){
            $liveid[] = $v['liveid'];
        }

        $result['live'] = implode(',', $liveid);
        if($result['live']) {
            $sql2 = "update live set uid = {$olduid} ,modtime = '".date("Y-m-d H:i:s")."' where liveid in({$result['live']}) ";

            $dao->execute($sql2, '', false);

            include_once 'process_client/ProcessClient.php';
            foreach($liveid as $k=>$v){
                ProcessClient::getInstance("dream")->addTask("live_sync_control", array('liveid'=>$v,'uid'=>$olduid));
            }

            $logDao = new DAOUserMergeLog();
            $logDao->addLogByLogid($newuid, $result);
        }
        
    }
   
    private static function diff2($array,$array2)
    {
        $temp1 = array();
        $temp2 = array();

        if(count($array) > 0) {
            foreach($array as $k=>$v){
                array_push($temp1, $v['fid']);

            }
        }
        if(count($array2) > 0) {
            foreach($array2 as $k=>$v){
                array_push($temp2, $v['fid']);

            }
        }

        return array_diff($temp1, $temp2);
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
require_once "process_client/ProcessClient.php";
require_once "dream_client/DreamClient.php";

try{
    $process = new ProcessClient("dream");

    $process->addWorker("passport_merge_user",  array("UserMerge", "execute"),  1, 2000);

    $process->run();
} catch (Exception $e) {
    ;
}
?>
