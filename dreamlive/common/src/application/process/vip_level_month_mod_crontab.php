<?php
// crontab 每月1号 0点执行
set_time_limit(0);
ini_set('memory_limit', '2G');
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
require $ROOT_PATH . "/config/server_conf.php";
const KEEPLEVEL = 1;
const DOWNLEVEL = 2;

$i = 100000;
$month1st = strtotime(date('Y-m'));
$lastmonth1st = strtotime('-1 month', strtotime(date('Y-m')));

while(--$i>0) {
    $dao = new DAOUserVip();
    $sql = "select uid, consume_current,consume_keep,uptime,addtime,modtime,recounttime from user_vip where recounttime < ? order by uid limit 1";
    $data = $dao->getRow($sql, $month1st);
    if(empty($data)) {
        break;
    }
    $uid = $data['uid'];
    $userinfo = User::getUserInfo($uid);
    $level = intval($userinfo['vip']);

    $flag = DOWNLEVEL;//降级
    if(strtotime($data['uptime']) >= $lastmonth1st) {
        //1 上月升级  自动保级 
        $flag = KEEPLEVEL;
    }else if($data['consume_current'] >= $data['consume_keep']) {
        //2 未升级 达到 保级要求 保级
        $flag = KEEPLEVEL;
    }
    $info = array(
        'recounttime' => time(),
        'consume_current' => 0,
    );
    if($flag == DOWNLEVEL) {
        $newlevel = $level < 1 ? 0 : $level-1;
        $info['consume_keep'] = $newlevel == 0 ? 0 : Vip::getLevelKeepConsume($newlevel);
        
        $dao->modUserVip($uid, $info);

        $daoVipLog = new DAOUserVipLog($uid);
        $daoVipLog->addLog($uid, $level, $newlevel);
        if($newlevel>0) {
            UserMedal::addUserMedal($uid, UserMedal::KIND_VIP, $newlevel);
        }else{
            UserMedal::delUserMedal($uid, UserMedal::KIND_VIP);
        }

        User::reload($uid);
        Logger::log("vip_level_month", "DOWNLEVEL", array("uid"=>$data['uid'], "level"=>$level, "newlevel"=>$newlevel, "old_consume_current"=>$data['consume_current'], "old_consume_keep"=>$data['consume_keep'], 'addtime'=>time()));

    }else{
        $info['consume_keep'] = $level==0 ? 0 :Vip::getLevelKeepConsume($level);
        
        $dao->modUserVip($uid, $info);
        Logger::log("vip_level_month", "KEEPLEVEL", array("uid"=>$data['uid'], "level"=>$level, "newlevel"=>$level, "old_consume_current"=>$data['consume_current'], "old_consume_keep"=>$data['consume_keep'], 'addtime'=>time()));
    }
}

?>
