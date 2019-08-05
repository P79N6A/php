<?php
// crontab 每分钟检测
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
echo "start:" . date("Y-m-d H:i:s") . "\n";

$modtime = date('Y-m-d H:i:s', time() - 100);

$dao = new DAOExpress();
$sql = "select * from express where modtime>? and status = '0'";

$orders = $dao->getAll($sql, $modtime);

foreach($orders as $k=>$v){
    echo $v['orderid']."\n";
    try{
        ExpressSdk::eorder($v['source'], $v['company'], $v['orderid'], $v['recName'], $v['recPrintAddr'], $v['recMobile'], $v['recTel'], $v['sendName'], $v['sendPrintAddr'], $v['sendMobile'], $v['sendTel'], $v['weight'], $v['count']);
    }catch(Exception $e){

    }
}
echo "end:" . date("Y-m-d H:i:s") . "\n\n";
die;