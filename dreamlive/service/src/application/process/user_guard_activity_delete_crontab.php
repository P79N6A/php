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


class userGuardActivity extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_MESSAGE");
        $this->setTableName("user_guard_activity");
        $this->setDebug(true);
    }

    public function addData($option)
    {
        return $this->replace($this->getTableName(), $option);
    }

    public function getList($starTime, $endTime)
    {
        $sql = "SELECT * FROM " . $this->getTableName() . " where endtime>=? and  endtime<? " ;
        return $this->getAll($sql, array($starTime,$endTime));
    }
}

$date     = date("Y-m-d H:i:s");
$starTime = date("Y-m-d H:i:s", strtotime($date . " -30 Minute"));
$endTime  = date("Y-m-d H:i:s", strtotime($date . " +1 Minute"));;

$userGuardActivity = new userGuardActivity();
$list = $userGuardActivity->getList($starTime, $endTime);
foreach($list as $item){
    if($item['type'] == 1) {
        $kind = UserMedal::KIND_SKIN_COVER;
    }
    if($item['type'] == 2) {
        $kind = UserMedal::KIND_SKIN_FACEU;
    }
    if($item['type'] == 3) {
        $kind = UserMedal::KIND_SKIN_FOLLOW;
    }
    UserMedal::delUserMedal($item['uid'], $kind);
    User::reload($item['uid']);
}






