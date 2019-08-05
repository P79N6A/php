<?php

/*****
 * 观众列表排序
 *
 * @author xubaoguo
 */
class AudienceSortWorker
{
    public static function execute($value)
    {
        if (empty($value)) {
            return true;
        }
        
        $userid       = $value["params"]["userid"];
        $liveid     = $value["params"]["liveid"];
        $liveinfo     = json_decode($value["params"]["liveinfo"], true);
        $userinfo      = json_decode($value["params"]["userinfo"], true);
        $user_guard = intval($value["params"]["user_guard"]);
        
        
        $rank = new Rank();
        /**
         * 新增排序维度(年守护、月守护、土豪、黄V、蓝V、红V、用户等级)
      *
         * @author chenxuezhi
         */
        $score_arr['year_guard']   = 900000000; //年守护
        $score_arr['month_guard']  = 80000000; //月守护
        $score_arr['vip']             = 7100000; //vip
        $score_arr['tuhao']        = 7000000; //土豪
        $score_arr['yellow']       = 600000; //黄V
        $score_arr['blue']         = 50000; //蓝V
        $score_arr['red']          = 4000; //红V
        
        $sort_score = (int) $userinfo['level'];
        
        $vip = (int) $userinfo['vip'];
        
        if (!empty($vip)) {
            $vip_score = ($vip * 1000);
            $sort_score += $score_arr['vip'];
            $sort_score += $vip_score;
        }
        
        //年守护
        if($user_guard == UserGuard::GUARD_TYPE_YEAR) {
            $sort_score += $score_arr['year_guard'];
        }
        
        //月守护
        if($user_guard == UserGuard::GUARD_TYPE_MONTH) {
            $sort_score += $score_arr['month_guard'];
        }
        
        
        if(!empty($userinfo['medal'])) {
            
            $item['medal'] = $userinfo['medal'][0];
            //土豪
            if($item['medal'] == 'tuhao') {
                $sort_score += $score_arr['tuhao'];
            }
            
            //黄V
            if($item['medal'] == 'yellow') {
                $sort_score += $score_arr['yellow'];
            }
            
            //蓝V
            if($item['medal'] == 'blue') {
                $sort_score += $score_arr['blue'];
            }
            
            //红V
            if($item['medal'] == 'red') {
                $sort_score += $score_arr['red'];
            }
        }
        
        
        
        //年守护
        if ($user_guard == UserGuard::GUARD_TYPE_YEAR) {
            $sort_score += 0.2;
            
        } elseif ($user_guard == UserGuard::GUARD_TYPE_MONTH) {//月守护
            $sort_score += 0.1;
        }
        
        $patroller = new Patroller();
        $is_patroller = $patroller->isPatroller($userid, $liveinfo['uid'], $liveid);
        //判断是否是场控
        if ($is_patroller) {
            $sort_score += 0.01;
        }
        
        //如果是私密直播则判断是否是预付费
        if (!empty($privacy)) {
            $is_can_watch = Privacy::checkIsCanWatchPrivateRoom($liveinfo['uid'], $userid);
            if ($is_can_watch) {
                $sort_score += 0.001;
            }
        }
        
        if (is_float($sort_score)) {
            Logger::log("audience_log", "get task", array("userid"=>$userid, "score"=>$sort_score,"liveid"=>$liveid, "line"=>__LINE__));
        }
        $rank->setRank('audience', "set", $userid, $sort_score, $liveid);

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
    $process->addWorker("audience_sort_worker",  array("AudienceSortWorker", "execute"),  100, 20000);
    $process->run();
} catch (Exception $e) {
    ;
}