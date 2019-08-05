<?php
// crontab 监测直播人数
// php /home/dream/codebase/service/src/application/process/get_live_user_num_crontab.php
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

require_once 'message_client/RongCloudClient.php';
require_once 'process_client/ProcessClient.php';

class watchLiveStatistic
{
    protected static $db = null;

    public static $_config=array(
        'dbhost'=>'rm-2ze7ast86e7r56j6a.mysql.rds.aliyuncs.com',
        'port'=>'3306',
        'dbuser'=>'dlreport',
        'dbpw'=>'We*Jzo8)uOsi&6-R',
        'dbname'=>'dream_report',
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

    /**
     * 批量插入数据
     *
     * @param string $str
     */
    public function addBatch($str)
    {
        $db = self::getInstance();
        $sql = "insert into live_online_statistics_rongyun (`uid`,`nickname`,`liveid`,`num`,`addtime`) values " . $str;
        return $db->query($sql);
    }
}


$rongcloud_client = new RongCloudClient();

$dao_live = new DAOLive();
$live = new Live();

$cache = Cache::getInstance("REDIS_CONF_CACHE");
$redis_cache_key = "heartbeat_live_cache";
$total = $cache->zCard($redis_cache_key);

$live_elements = $cache->zRevRange($redis_cache_key, 0, $total);
$livelist = $dao_live->getActiveAndPausedLives();

echo  "脚本执行开始:".date("Y-m-d H:i:s");echo "\n";

$arr = [];
$nowtime = time();
$dao_live = new DAOLive();
$str = "";
foreach ($live_elements as $elem) {
    $liveinfo = $dao_live->getLiveInfo($elem);
    try {
        Consume::start();
        $result = $rongcloud_client->queryUsers($elem, 1, 2);
        $time = Consume::getTime();
        
        $userinfo = User::getUserInfo($liveinfo['uid']);
        if($result['code'] == '200') {
            $userinfo = User::getUserInfo($liveinfo['uid']);
            $key = "dreamlive_live_user_real_num_".$liveinfo['liveid'];
            $temp = array(
                'num' => $result['total'],
                'uid' => $liveinfo['uid'],
                'nickanme' => $userinfo['nickname']
            );
            $cache->set($key, json_encode($temp));
            $cache->expire($key, 86400);
            $t = json_decode($cache->get($key), true);
            $str .= "(".$liveinfo['uid'].",'".addslashes($userinfo['nickname'])."',".$liveinfo['liveid'].",".$result['total'].",'" . date("Y-m-d H:i").":00" . "'),";
            
            echo "成功！uid=".$liveinfo['uid']."  liveid=".$liveinfo['liveid']."  nickanme=".$userinfo['nickname']."  num=".$result['total']."   time=".$time;echo "\n";
            
        }else{
            echo "失败！uid=".$liveinfo['uid']."  liveid=".$liveinfo['liveid']."  nickanme=".$userinfo['nickname']."  num=".$result['total']."   result=".print_r(json_encode($result), true)."   time=".$time;echo "\n";
            Consume::start();
            $result = $rongcloud_client->queryUsers($elem, 1, 2);
            $time = Consume::getTime();
            
            if($result['code'] == '200') {
                $key = "dreamlive_live_user_real_num_".$liveinfo['liveid'];
                $temp = array(
                    'num' => $result['total'],
                    'uid' => $liveinfo['uid'],
                    'nickanme' => $userinfo['nickname']
                );
                $cache->set($key, json_encode($temp));
                $cache->expire($key, 86400);
                $t = json_decode($cache->get($key), true);
                $str .= "(".$liveinfo['uid'].",'".addslashes($userinfo['nickname'])."',".$liveinfo['liveid'].",".$result['total'].",'" . date("Y-m-d H:i").":00" . "'),";
                echo "重试成功！uid=".$liveinfo['uid']."  liveid=".$liveinfo['liveid']."  nickanme=".$userinfo['nickname']."  num=".$result['total']."   time=".$time;echo "\n";
            }else{
                echo "重试失败！uid=".$liveinfo['uid']."  liveid=".$liveinfo['liveid']."  nickanme=".$userinfo['nickname']."  num=".$result['total']."   result=".print_r(json_encode($result), true)."   time=".$time;echo "\n";
            }
        }
        
    }catch (exception $e) {
        echo $e->getMessage();echo "\n";
    }
}
$str = substr($str, 0, strlen($str) - 1);
$watchLiveStatistic = new watchLiveStatistic();
$watchLiveStatistic->addBatch($str);

echo  "脚本执行结束:".date("Y-m-d H:i:s");echo "\n";echo "\n";echo "\n";echo "\n";echo "\n";echo "\n";echo "\n";
?>
