<?php
//nohup php /home/yangqing/work/dreamlive/service/src/application/process/import_sendgift_to_redis.php > import_sendgift_to_redis_log.log 2>&1 &

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

class live extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setTableName("live");
    }
    
    public function getMaxUid()
    {
        $sql    = "select uid from ".$this->getTableName()."  order by uid desc limit 1";
        $result = $this->getRow($sql);
        if(isset($result['uid']) && $result['uid']>0) {
            return $result['uid'];
        }
        return 0;
    }

    public function getList($i,$num)
    {
        $sql = "select DISTINCT uid from {$this->getTableName()} where uid>? order by uid asc limit ".$num." ";
        return $this->getAll($sql, $i);
    }
}

class giftlog extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("giftlog");
    }
    
    public function getList($receiver)
    {
        $sql = "select t1.sender,t1.receiver,sum(t1.num*t1.price) as sum
                from (select DISTINCT orderid,sender,receiver,num,price from giftlog where receiver=? and consume='DIAMOND') as t1 
                GROUP BY t1.sender";
        //$sql = "select sender,receiver,sum(num*price) as sum from ".$this->getTableName()." where receiver=? and consume='DIAMOND' group by sender";
        return $this->getAll($sql, $receiver);
    }
}


$i       = 0;
$num     = 10000;
$live    = new live();
$maxUid  = $live->getMaxUid();
$giftlog = new giftlog();
$cache = Cache::getInstance("REDIS_CONF_CACHE");

while ($i < $maxUid){
    $list = $live->getList($i, $num);
    foreach($list as $item){
        $receive_gift = 0;
        $list  = $giftlog->getList($item['uid']);
        if(!empty($list)) {
            foreach($list as $it){
                $cache->zAdd('protect_'.$it['receiver'], $it['sum'], $it['sender']);
                echo '---------------protect_'.$it['receiver']."    member=".$it['sender']."  score=".$it['sum'];echo "\n";
                $receive_gift += $it['sum'];
            }
        }
        $key = getKey('receive_gift', $item['uid']);
        $redis = getRedis($key);
        echo $key."=".$receive_gift;echo "\n";
        $redis->SET($key, $receive_gift);
        $i = $item['uid'];
    }
}

function getKey($type, $relateid)
{
    return 'dreamlive' . '_' . $type . '_' . $relateid;
}

function getRedis($key)
{
    $hash = getRedisHash($key);
    return Cache::getInstance("REDIS_CONF_COUNTER", $hash);
}

function getRedisHash($key)
{
    return abs(crc32($key));
}


