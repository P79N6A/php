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

$daoLog=new DAOLottoLog();
$r=$daoLog->getAll("select * from lotto_log where addtime>'2017-12-28 11:40:00'", '');
foreach ($r as $i){
    $ps=json_decode($i['extends'], true);
    if ($ps) {
        foreach ($ps as $j){
            if ($j['type']==6&&$j['prizeid']==5&&$j['status']==2) {
                Bag::putGift($i['uid'], $j['relateid'], $j['num']);
            }else{continue;
            }
        }
    }
}


//修复直播间左上角星票数量，改成星钻数量
function repairIncome()
{
    //dreamlive_receive_gift_
    $daoGiftLog=new DAOGiftLog();
    $counter=Cache::getInstance("REDIS_CONF_COUNTER");
    $r=$counter->keys("dreamlive_receive_gift_*");
    foreach ($r as $k){
        $kinfo=explode("_", $k);
        if (isset($kinfo[3])) {
            $uid=$kinfo[3];
            //$income=$counter->get('reamlive_receive_gift_'.$uid);
            $income=Counter::get("receive_gift", $uid);
            if ($income>0) {
                $t=$daoGiftLog->getRow("select sum(num*price) as n,receiver  from giftlog where receiver=? and consume='DIAMOND'", array('uid'=>$uid));
                if ($t&&(int)$t['n']>0&&(int)$t['n']>=$income) {
                    //$counter->set('reamlive_receive_gift_'.$uid, $t['n']);
                    //Counter::set("receive_gift",$uid ,(int)$t['n'] );
                    //var_dump($income,(int)$t['n']);break;
                }
            }
        }
    }
}

repairIncome();






/*$loop=100000;
$count=2;

$result=[];
for($i=1;$i<$loop;$i++){
    $d=LottoPrize::_lottoAlg();
    if (empty($result[$d['prizeid']]['count'])){
        $result[$d['prizeid']]['count']=1;
    }else{
        $result[$d['prizeid']]['count']+=1;
    }

    if (empty($result[$d['prizeid']]['name'])){
        $result[$d['prizeid']]['name']=$d['name'];
    }
}

$re=array();
foreach ($result as $k=>$v){
    $re[$k]=array(
        'name'=>$v['name'],
        'count'=>$v['count'],
        'rate'=>bcdiv($v['count'],$loop,4 ),
    );
}
var_dump($re);*/


