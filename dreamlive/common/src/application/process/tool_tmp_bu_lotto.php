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
$result=$daoLog->getAll("SELECT * from lotto_log WHERE date(addtime)='2017-11-15' and id>84 AND addtime<='2017-11-15 19:15:00'", '');
$daoBag=new DAOBag();
$daoJournal=new DAOJournal();

//$ids=array( '20204350',    '10016044',  ' 23519790',);
$ids=array('20204350',    '10016044',  ' 23519790');
foreach ($result as $i){
    $ext=json_decode($i['extends'], true);
    $ext=!$ext?[]:$ext;
    foreach ($ext as $j){
        switch ($j['type']){
        case DAOLottoPrize::TYPE_BAG_GIFT:
            break;
        case DAOLottoPrize::TYPE_BIG_HORN:
            LottoPrize::putInBag($i['uid'], $j);
            break;
        case DAOLottoPrize::TYPE_SMALL_HORN:

            break;
        case DAOLottoPrize::TYPE_DIAMOND:
            if (in_array($i['uid'], $ids)) {
                LottoPrize::putInBag($i['uid'], $j);
            }

            //$daoJournal->getRow("select * from journal_".($i['uid']%100)." where uid=? and type=? and direct='IN' and currency=? and amount and addtime and remark");

            break;
        case DAOLottoPrize::TYPE_STAR:
            break;
        case DAOLottoPrize::TYPE_RIDE:
            /*$r=$daoBag->getRow("select * from bag where uid=? and relateid=?",['uid'=>$i['uid'],'relateid'=>$j['relateid']]);
            if (!$r){

            }*/
            LottoPrize::putInBag($i['uid'], $j);
            break;
        case DAOLottoPrize::TYPE_ENTITY:
            break;
        case DAOLottoPrize::TYPE_EMPTY:
            break;
        default:
            break;
        }
    }
}


