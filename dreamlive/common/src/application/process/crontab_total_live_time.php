<?php
/**
 * 统计直播时长
 * User: User
 * Date: 2018/2/3
 * Time: 11:21
 */
if (substr(php_sapi_name(), 0, 3) !== 'cli') { die("cli mode only");
}

set_time_limit(0);
ini_set('memory_limit', '2G');
ini_set("display_errors", "On");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
$ROOT_PATH = dirname(dirname(dirname(dirname(__FILE__))));

$LOAD_PATH = array(
    $ROOT_PATH . "/src/www",
    $ROOT_PATH . "/config",
    $ROOT_PATH . "/src/application/controllers",
    $ROOT_PATH . "/src/application/models",
    $ROOT_PATH . "/src/application/models/libs",
    $ROOT_PATH . "/src/application/models/libs/stream_client",
    $ROOT_PATH . "/src/application/models/dao",
    $ROOT_PATH . "/../",
);
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $LOAD_PATH));

function __autoload($classname)
{
    $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    include_once "$classname.php";
}

require $ROOT_PATH . "/config/server_conf.php";

$cache      = Cache::getInstance("REDIS_CONF_CACHE");
$times      = json_decode($cache->get('live_time_active_time'), true);
$stime      = $times['stime'];
$etime      = $times['etime'];
//$endtime    = date("Y-m-d",strtotime("-1 day"))." 23:59:59";
//清空数据
$cache -> set("live_time_stream_uids", '');
$cache  -> set("live_time_rank", '');
if((strtotime($etime)+2*3600)<time()) { return true;
}

$sql    = "SELECT uid,(UNIX_TIMESTAMP(endtime)-UNIX_TIMESTAMP(`addtime`)) as livetime,DATE_FORMAT(ADDTIME,'%Y%m%d') as stime,DATE_FORMAT(endtime,'%Y%m%d') as etime,endtime,addtime FROM live 
WHERE UNIX_TIMESTAMP(endtime)-UNIX_TIMESTAMP(`addtime`)>=? and addtime<=? and endtime>=?";
$live       = new DAOLive();
$live_info  = $live->getAll($sql, [1800,$etime,$stime]);
$rank_info  = [];
$date_info  = [];
if($live_info) {
    foreach($live_info as &$val)
    {
        if($val['stime'] != $val['etime']) {
            if(strtotime($val['addtime'])>=strtotime($stime)&&strtotime($val['endtime'])<=strtotime($etime)) {
                $one_time        = strtotime(date("Y-m-d", strtotime($val['addtime'])).' 23:59:59') - strtotime($val['addtime']);
                $val['livetime']        = strtotime($val['endtime']) - strtotime(date("Y-m-d", strtotime($val['endtime'])).' 00:00:00');
            }elseif(strtotime($val['addtime'])<strtotime($stime)&&strtotime($val['endtime'])>strtotime($stime)) {
                $val['livetime']        = strtotime($val['endtime']) - strtotime(date("Y-m-d", strtotime($val['endtime'])).' 00:00:00');
            }elseif(strtotime($val['endtime'])>strtotime($etime)&&strtotime($val['addtime'])<=strtotime($etime)) {
                $one_time        = strtotime(date("Y-m-d", strtotime($val['addtime'])).' 23:59:59') - strtotime($val['addtime']);
                $val['livetime'] = 0;
            }
        }
        //$date_info[$val['etime']][]    =  $val['uid'];
        if($one_time) {
            if($one_time) {
                $m      = floor($one_time/60);
                $rank_info[$val['stime']][$val['uid']]     += ($m - ($m%30));
            }
        }

        $m      = floor($val['livetime']/60);
        $rank_info[$val['etime']][$val['uid']]     += ($m - ($m%30));

    }
}
//过滤直播时长小于3小时的用户
if($rank_info) {
    foreach($rank_info as $k=>$val)
    {
        foreach ($val as $key => &$v)
        {
            if($v>720) { $rank_info[$k][$key] = 720;
            }
            if($v<2*60) {
                unset($val[$key]);
                $date_info[$k][]    = '';
            }else{
                $date_info[$k][]    = $key;
            }
        }
    }
}
//取出从开始到现在符合上榜用户UID
if($date_info) {
    $date_user  = $date_info[date("Ymd", strtotime($stime))];
    foreach ($date_info as $k =>$v)
    {
        if($k == date("Ymd")) { break;
        }
        $date_user = array_intersect($date_user, $v);
    }
}
//排除警告用户
if($date_user) {
    $warning    = json_decode($cache->get('live_time_waring_uids'), true);
    foreach($date_user as $k=>$v)
    {
        $uids   = array_keys($warning);
        if(in_array($v, $uids)) {
            if(Counter::increase("live_time_total_warning", $v, 1)<2) { sendMessage($v, "由于在参赛时间内，直播违规(".$warning[$v].")，失去时长之冠争霸赛的资格");
            }
            unset($date_user[$k]);
        }
    }
}
//获取符合条件的用户
$rank_sroce = [];
if($rank_info) {
    foreach($rank_info as $k=>$v)
    {
        foreach($v as $key=>$val)
        {
            if(in_array($key, $date_user)) {
                $rank_sroce[$key] += (int)$val;
            }
            else
            {
                if(Counter::increase("live_time_total_warning", $v, 1)<2) { sendMessage($key, "由于在参赛时间内，当日直播上次为满足活动要求，失去时长之冠争霸赛的资格");
                }
            }
        }
    }
    $rank_sroce = array_flip($rank_sroce);
    krsort($rank_sroce);
    $rank_sroce = array_flip($rank_sroce);
    $cache -> set("live_time_stream_uids", json_encode(array_keys($rank_sroce)));
    $rank_sroce     = array_slice($rank_sroce, 0, 100, true);
}
//组合排序
if($rank_sroce) {
    $i = 1;
    foreach($rank_sroce as $k=>$val)
    {
        $rank_user_info[$k]    = array(
            'sroce'     => $val,
            'rank'      => $i
        );
        $i++;
    }
}
//添加缓存

$cache  -> set("live_time_rank", json_encode($rank_user_info));

function sendMessage($uid, $str)
{
    Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $uid, '主播时长榜单通告', $str);
}

