<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/5
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

try{
    //13004666370孟旭，15810065888老板，18179185055老方，13141367985焦瑞琦，13691544388大叔
    $config=array(
        array('uid'=>7000,'name'=>'背包礼物','msg'=>'额度太低','min'=>50000,'mobiles'=>array('13004666370', '15810065888', '13141367985',  '13691544388', '18179185055')),
        //array('uid'=>7000,'name'=>'背包礼物','msg'=>'额度太低','min'=>200000,'mobiles'=>array(15010529915)),
    );
    foreach ($config as $i){
        $b=Account::getBalance($i['uid'], Account::CURRENCY_DIAMOND);
        if ($b<=$i['min']) {
            ALISMS::alarm($i['mobiles'], $i['uid'], $i['name']);
        }
    }
}catch (Exception $e){
    throw  $e;
}


