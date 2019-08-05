<?php
set_time_limit(0);
ini_set('memory_limit', '1G');
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

require $ROOT_PATH."/config/server_conf.php";

//交易流水
class DAOJournal extends DAOProxy
{
    public function __construct($uid)
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setShardId($uid);
        $this->setTableName("journal");
    }
    
    //收礼收入50%
    public function getAmountReceive($uid,$time)
    {
        $sql = "SELECT sum(amount) as cnt from ".$this->getTableName()." where uid=? and direct='IN' and currency=1 and type!=2 and addtime>'".$time."'";
        $result = $this->getRow($sql, array($uid));
        return isset($result['cnt']) ? $result['cnt'] : 0;
    }
    
    //门票收入50%
    public function getAmountPrivacy($uid,$time)
    {
        $sql = "SELECT sum(amount) as cnt from ".$this->getTableName()." where uid=? and direct='IN' and currency=1 and type in(30, 31)  and addtime>'".$time."' ";
        $result = $this->getRow($sql, array($uid));
        return isset($result['cnt']) ? $result['cnt'] : 0;
    }
}

//直播
class DAOLive extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setTableName("live");
    }

    public function getList($time)
    {
        $sql = "select DISTINCT uid from ".$this->getTableName()." WHERE addtime>'".$time."'  ";
        return $this->getAll($sql);
    }
}

$time   = date('Y-m-d H:i:s', strtotime('-10 days'));
$h=date('G');

$cache = Cache::getInstance("REDIS_CONF_CACHE");
$DAOLive    = new DAOLive();
$uids = $DAOLive->getList($time);
foreach($uids as $item){
    $DAOJournal = new DAOJournal($item['uid']);
    $amountReceive = $DAOJournal->getAmountReceive($item['uid'], $time);
    $amountPrivacy = $DAOJournal->getAmountPrivacy($item['uid'], $time);
    $amount = intval($amountReceive) + intval($amountPrivacy);

    if($amount>10) {
        $key = "user_login_speed";
        $cache->SADD($key, $item['uid']);
        echo $key."  cnt=".$item['uid']."   amount=".$amount;echo "\n";
    }
}
