<?php
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


require $ROOT_PATH."/config/server_conf.php";
require_once 'process_client/ProcessClient.php';



require $ROOT_PATH . "/config/server_conf.php";

$dao_user = new DAOUser();

$sql = "select uid, nickname from user where modtime >= ? ";


$last_five_days = strtotime("-5 day");
$date_times = date("Y-m-d H:i:s", $last_five_days);
//$date_times = 0;
$user_list = $dao_user->getAll($sql, array($date_times));

$i = 0;
var_dump(count($user_list));

foreach ($user_list as $row) {
    var_dump($i . '----=----' . $row['uid']);
    $option = array('nickname' => $row['nickname'],'uid' => $row['uid']);
    ProcessClient::getInstance("dream")->addTask("filter_nickname_worker", $option);
    
    $i++;
    
}