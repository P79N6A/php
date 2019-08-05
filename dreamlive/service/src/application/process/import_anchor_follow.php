<?php
//nohup php /home/yangqing/work/dreamlive/service/src/application/process/import_sendgift_to_redis.php > import_task_log.log 2>&1 &

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


class DAOJournal extends DAOProxy
{
    public function __construct($userid)
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setShardId($userid);
        $this->setTableName("journal");
    }
    
    public function getList($uid,$offset, $num)
    {
        $sql = "select id, orderid, type, direct, currency, amount, remark, extends as extend, addtime 
                from {$this->getTableName()} where uid=? and direct='IN' and currency=1 and type=3  order by id desc limit  ?,?";
        return  $this->getAll($sql, array($uid,$offset,$num));
    }
    
    public function getCount($uid)
    {
        $sql = "select count(1) as cnt from {$this->getTableName()} where uid=? and direct='IN' and currency=1 and type=3 ";
        $result = $this->getRow($sql, array($uid));
        return isset($result['cnt']) ? $result['cnt'] : 0;
    }
}


class FollowSystemAccount extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setTableName("follow_system_account");
    }

    public function isExist($fid)
    {
        $sql = "select count(1) as cnt from  " . $this->getTableName() . " where fid=? ";
        $result = $this->getRow($sql, array($fid));
        if (isset($result['cnt']) && $result['cnt'] > 0) {
            return true;
        }
        return false;
    }
    
    public function addData($data)
    {
        return $this->insert($this->getTableName(), $data);
    }
}



//$addTime = '2017-04-15 00:00:00';
$addTime = date('Y-m-d H:i:s', time()-86400);
$step    = 200;
$uid     = 999999;

echo  "脚本执行开始:".date("Y-m-d H:i:s");echo "\n";



$cache = Cache::getInstance("REDIS_CONF_CACHE");
$cache_info = $cache->get("big_liver_keys");
$userList = explode(',', $cache_info);
//$userList = array('21000345');
foreach($userList as $item){
    //1,获取我的粉丝
    $dao_following = new DAOFollower($item);
    $total = $dao_following->countFollowers();
    
    $offset = 0;
    while ($offset < $total) {
        $followsIds = getFollowsIds($item, $offset, $step);
        addFollowSystemAccount($followsIds);
        
        $offset += $step;
    }
    
    
    //2,收礼
    $DAOJournal = new DAOJournal($item);
    $total      = $DAOJournal->getCount($item);
    $offset = 0;
    while ($offset < $total) {
        $list = $DAOJournal->getList($item, $offset, $step);
        $followsIds = array();
        foreach($list as $it){
            $extends = json_decode($it['extend'], true);
            array_push($followsIds, $extends['sender']);
        }
        $followsIds = array_unique($followsIds);
        addFollowSystemAccount($followsIds);
        
        $offset += $step;
    }
    
    //3，看播记录
    $watchLiveStatistic = new watchLiveStatistic();
    $total = $watchLiveStatistic->getCount($item);
    $offset = 0;
    while ($offset < $total) {
        $list = $watchLiveStatistic->getList($item, $offset, $step);
        $followsIds = array();
        foreach($list as $it){
            array_push($followsIds, $it['uid']);
        }
        $followsIds = array_unique($followsIds);print_r($followsIds);
        addFollowSystemAccount($followsIds);
    
        $offset += $step;
    }
    
}


echo  "脚本执行结束:".date("Y-m-d H:i:s");echo "\n";echo "\n";echo "\n";echo "\n";echo "\n";echo "\n";echo "\n";



function getFollowsIds($uid, $offset, $num)
{
    $DAOFollower = new DAOFollower($uid);
    $userFollowers = $DAOFollower->getUserFollowers($offset, $num);
    $followIds = array();
    foreach ($userFollowers as $key => $follow) {
        array_push($followIds, $follow["fid"]);
    }
    return $followIds;
}


function addFollowSystemAccount($followsIds)
{
    if(empty($followsIds)) {
        return ;
    }
    $FollowSystemAccount = new FollowSystemAccount();
    foreach($followsIds as $fid){
        $isExist = $FollowSystemAccount->isExist($fid);
        if($isExist) {
            continue;
        }
        $option = array();
        $option['fid'] = $fid;
        $result = $FollowSystemAccount->addData($option);
        echo "fid=". $fid."\n";
    }
}

class watchLiveStatistic
{
    protected static $db = null;

    public static $_config=array(
        'dbhost'=>'10.10.10.156',
        'port'=>'3306',
        'dbuser'=>'readonly',
        'dbpw'=>'readonly@2017',
        'dbname'=>'dreamtv_datacenter',
        'dbcharset'=>'utf8mb4',
    );
    

    /**
     * 获取数据连接实例
     */
    public static function getInstance()
    {
        if (self::$db == null) {
            $_config = self::$_config;

            $dsn = 'mysql:dbname='. $_config['dbname']. ';host='. $_config['dbhost']. ($_config['port'] ? ';port='. $_config['port'] : '');
            $driver_options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES '. $_config['dbcharset']);
            try {
                self::$db = new PDO($dsn, $_config['dbuser'], $_config['dbpw'], $driver_options);
            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
        }

        return self::$db;
    }


    public static function getList($userid, $offset, $step)
    {
        $db = self::getInstance();
        $sql = "SELECT uid FROM audience_watch_duration_statisic where userid=".trim($userid)." order by id asc limit $offset,$step" ;
        $result = $db->query($sql)->fetchAll(2);
        return $result;
    }
    
    public function getCount($userid)
    {
        $db = self::getInstance();
        $sql = "select count(1) as cnt from audience_watch_duration_statisic where userid=".trim($userid)."  ";
        $result = $db->query($sql)->fetch(2);
        return isset($result['cnt']) ? $result['cnt'] : 0;
    }
}


