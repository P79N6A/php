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
    public function getAmountReceive($uid)
    {
        $sql = "SELECT sum(amount) as cnt from ".$this->getTableName()." where uid=? and direct='IN' and currency=1 and type!=2 ";
        $result = $this->getRow($sql, array($uid));
        return isset($result['cnt']) ? $result['cnt'] : 0;
    }
    
    //门票收入50%
    public function getAmountPrivacy($uid)
    {
        $sql = "SELECT sum(amount) as cnt from ".$this->getTableName()." where uid=? and direct='IN' and currency=1 and type in(30, 31) ";
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

    public function getList()
    {
        $sql = "select DISTINCT uid from live ".$this->getTableName()." order by uid desc limit 200 ";
        return $this->getAll($sql);
    }
}

//计数器
class DAOCounters extends DAOProxy
{
    const TABLE_NAME_BASE = 'counter';
    const TABLE_SHARDINGS = 100;

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_COUNTER");
    }

    public function setCounter($product, $type, $relateid, $value, $microtime)
    {
        $tablename = $this->getTableName($product, $type, $relateid);
        $modtime = date('Y-m-d H:i:s');
        $info = array(
            'product'  => $product,
            'type'     => $type,
            'relateid' => $relateid,
            'value'    => $value,
            'modtime'  => $modtime,
            'version'  => $microtime
        );
        return $this->replace($this->getTableName(), $info);
    }
    
    public function getCounter($product, $type, $relateid)
    {
        $tablename = $this->getTableName($product, $type, $relateid);
        $sql = "select value from ".$tablename." where product=? and type=? and relateid=? ";
        return  $this->getOne($sql, array('product' => $product,'type'=>$type,'relateid'=>$relateid));
    }

    protected function getTableName($product, $type, $relateid)
    {
        $md5 = md5($product . '_' . $type . '_' . $relateid);
        return self::TABLE_NAME_BASE . '_' . abs(crc32($md5)) % self::TABLE_SHARDINGS;
    }
}


$DAOLive    = new DAOLive();
$DAOCounter  = new DAOCounters();
$uids = $DAOLive->getList();
foreach($uids as $item){
    $DAOJournal = new DAOJournal($item['uid']);
    $amountReceive = $DAOJournal->getAmountReceive($item['uid']);
    $amountPrivacy = $DAOJournal->getAmountPrivacy($item['uid']);
    $amount = intval($amountReceive) + intval($amountPrivacy);
    
    //修复数据库counterDb
    /**
    $product = 'dreamlive';
    $type    = 'receive_gift';
    $oldAmount = $DAOCounter->getCounter($product, $type, $item['uid']);
    if ($oldAmount != $amount) {
        //$result = $DAOCounter->setCounter($product, $type, $uid, $amount, round(microtime(true) * 1000));
        file_put_contents('/tmp/import_receivegift_ranking_'.date('Y-m').'.log', 'counterDb:  product='.$product.'  type='.$type.'  uid='.$item['uid'].'  old='.$oldAmount.'  value='.$amount."\n", FILE_APPEND);
    }
    */
    
    $product = 'dreamlive';
    $type    = 'receive_gift';
    if($amount>0) {
        //修复计数器counterRedis
        addCounterRedis($type, $item['uid'], $amount);
        
        //修复排行榜ranking
        addRankReceiveGiftRedis($amount, $item['uid']);
    }
}


function addCounterRedis($type,$relateid, $value)
{
    $key   = 'dreamlive_'.$type.'_' . $relateid;
    $hash  = abs(crc32($key));
    $cache = Cache::getInstance("REDIS_CONF_COUNTER", $hash);
    $oldValue = $cache->get($key);
    if ($oldValue != $value) {
        $cache->set($key, $value);
        file_put_contents('/tmp/import_receivegift_ranking_'.date('Y-m').'.log', 'counterRedis:  key='.$key.'  hash='.$hash.'  old='.$oldValue.'  value='.$value."\n", FILE_APPEND);
    }
    return true;
}

function addRankReceiveGiftRedis($score,$member)
{
    $key   = 'receivegift_ranking';
    $cache = Cache::getInstance("REDIS_CONF_CACHE");
    $old = $cache->ZSCORE($key, $member); 
    if($old != $member) {
        $cache->zAdd($key, $score, $member);
        file_put_contents('/tmp/import_receivegift_ranking_'.date('Y-m').'.log', 'rankingRedis:  key='.$key.'   old='.$old.'  value='.$score."\n", FILE_APPEND);
    }
}












