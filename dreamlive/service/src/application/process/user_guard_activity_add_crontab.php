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


class userGuardDetailActivicy extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_MESSAGE");
        $this->setTableName("user_guard_detail");
    }

    public function getList($starTime, $endTime)
    {
        $sql = "SELECT * FROM " . $this->getTableName() . " where addtime>=? and  addtime<? order by addtime asc " ;
        return $this->getAll($sql, array($starTime,$endTime));
    }
}

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

    public function del($uid)
    {
        return $this->delete($this->getTableName(), 'uid = ?', $uid);
    }
}

$time = time();
$starTime = '2017-10-10 00:00:00';
$endTime  = '2017-10-17 00:00:00';

$userGuardDetailActivicy = new userGuardDetailActivicy();
$list = $userGuardDetailActivicy->getList($starTime, $endTime);
$arrTemp = array();
foreach($list as $item){
    $arrTemp[$item['relateid']][] = $item;
}

$arrTempActivity = array();
foreach($arrTemp as $key=>$item){
    $startime = $item[0]['addtime'];
    $expires = 0;
    foreach($item as $it){
        if($it['type'] == 5) {
            $expires += 1;
        }
        if($it['type'] == 10) {
            $expires += 12;
        }
    }
    //活动专属封面装饰处理
    $option = array(
        'uid'      => $key,
        'type'     => 1,
        'modtime'  => date("Y-m-d H:i:s"),
        'startime' => $startime,
        'endtime'  => date("Y-m-d H:i:s", strtotime($startime . " +$expires day"))
    );
    $arrTempActivity[] = $option;
    //FaceU特效
    if($expires>=3) {
        $option = array(
            'uid'      => $key,
            'type'     => 2,
            'modtime'  => date("Y-m-d H:i:s"),
            'startime' => $startime,
            'endtime'  => date("Y-m-d H:i:s", strtotime($startime . " +30 day"))
        );
        $arrTempActivity[] = $option;
    }
    //活动专属关注弹窗和按钮样式
    if($expires>=10) {
        $option = array(
            'uid'      => $key,
            'type'     => 3,
            'modtime'  => date("Y-m-d H:i:s"),
            'startime' => $startime,
            'endtime'  => date("Y-m-d H:i:s", strtotime($startime . " +30 day"))
        );
    }
}

$userGuardActivity = new userGuardActivity();
foreach($arrTempActivity as $item){
    $result = $userGuardActivity->addData($item);
    if($result) {
        if($item['type'] == 1) {
            $kind = UserMedal::KIND_SKIN_COVER;
        }
        if($item['type'] == 2) {
            $kind = UserMedal::KIND_SKIN_FACEU;
        }
        if($item['type'] == 3) {
            $kind = UserMedal::KIND_SKIN_FOLLOW;
        }
        UserMedal::addUserMedal($item['uid'], $kind, 'user_guard');
    }
}




