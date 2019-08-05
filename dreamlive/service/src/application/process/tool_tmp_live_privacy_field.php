<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/5
 * Time: 19:01
 * 修复游戏土豪
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

// $track=new Track();
// $track->showTrack(21000394,10000243 ,3793,1,1314 );
print_r('starting ...'."\n");
$daoPrivacy=new DAOPrivacy();
$daoLive=new DAOLive();
$liveModule=new Live();

$all=$daoPrivacy->getAll("select * from privacy order by privacyid desc");
foreach ($all as $i){
    print_r($i);print_r("\n");
    $startime=$i['startime'];
    $endtime=$i['endtime'];
    $where=" and (
                     (addtime<='".$startime."' and endtime>='".$startime."') or 
                     (addtime<= '".$startime."' and endtime='0000-00-00 00:00:00') or 
                     (addtime>='".$startime."' and endtime<'".$endtime."') or
                     (addtime>='".$startime."' and endtime>='".$endtime."') or 
                     (addtime>='".$startime."' and endtime>='0000-00-00 00:00:00') or 
                     (addtime<='".$startime."' and endtime>='".$endtime."') or 
                     (addtime<='".$startime."' and endtime='0000-00-00 00:00:00')
                 ) ";
    $sql="select * from live where uid=? ".$where;
    $live=$daoLive->getAll($sql, ['uid'=>$i['uid']]);
    foreach ($live as $j){
        $privacy=@json_decode($j['privacy'], true);
        $tmp=[];
        if ($privacy&&!empty($privacy)) {
            $pid=array_column($privacy, 'privacyid');
            if (!in_array($i['privacyid'], $pid)) {
                $tmp[]=$i;
            }
        }else{
            $tmp[]=$i;
        }
        $json=json_encode($tmp);
        $r=$daoLive->update('live', ['privacy'=>$json], 'liveid=?', ['liveid'=>$j['liveid']]);
        if ($r) {
            $liveModule->_reload($j['liveid']);
        }
        if (!$r) {
            file_put_contents("/tmp/privacy.txt", json_encode($j)."\n", FILE_APPEND);
        }
    }
}

print_r("over ... \n");