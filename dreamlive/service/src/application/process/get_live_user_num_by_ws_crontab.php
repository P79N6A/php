<?php
//nohup php /home/yangqing/work/dreamlive/service/src/application/process/import_sendgift_to_redis.php > import_task_log.log 2>&1 &
//禁止测试环境、开发机执行

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

class WsLive extends DAOProxy
{

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setTableName("live");
    }

    public function isExist($sn,$partner)
    {
        $sql = "select uid from ".$this->getTableName()." where status in (".Live::ACTIVING.",".Live::PAUSED.") and sn=? and partner=? ";
        $result = $this->getRow($sql, array('sn'=>$sn,'partner'=>$partner));
        if(isset($result['uid']) && $result['uid']>0) {
            return $result['uid'];
        }
        return false;
    }
}

class watchLiveStatistic
{
    protected static $db = null;

    public static $_config=array(
        'dbhost'=>'10.10.10.156',
        'port'=>'3306',
        'dbuser'=>'dreamtv2',
        'dbpw'=>'dreamtv2455',
        'dbname'=>'datacentor_report',
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
        $sql = "insert into audiences_new_wangsu (redate, zid, num) values " . $str;echo $sql;echo "\n";
        return $db->query($sql);
    }
}


echo  "脚本执行开始:".date('Y-m-d H:i:s');echo "\n";
$list = array(
    'WS_1510732897_20025370_6702.7e6a',
    'WS_1516586801_20025370_6146.fc46',
    'WS_1516586869_20025370_9278.2025',
    'WS_1517471652_20025370_9717.2a81'
);
$str = "";
$WsLive   = new WsLive();
$result = Wcs::getOnlineLive();
$count  = $result['count'];
$watchLiveStatistic = new watchLiveStatistic();
print_r($result);
foreach($result['dataValue'] as $item){
    $path = str_replace(array('cn01.push.dreamlive.com','cn01.flv.dreamlive.com','cn01.hls.dreamlive.com','cn01.replay.dreamlive.com'), '', $item['prog']);
    $rtmp = 'rtmp://cn01.push.dreamlive.com' . $path;
    $sn = str_replace(array('cn01.push.dreamlive.com/live/','cn01.push.dreamlive.com/replay/','cn01.flv.dreamlive.com/replay/','cn01.flv.dreamlive.com/live/','cn01.hls.dreamlive.com/live/','cn01.hls.dreamlive.com/replay/','cn01.replay.dreamlive.com/replay/'), '', $item['prog']);

    $zid = $WsLive->isExist($sn, 'ws');



    if ((! $zid || $item['value'] > $count * 0.9) && !strstr($sn, 'BETA') && !in_array($sn, $list)) {
        $ret = Wcs::stopOnlineLive($rtmp);
        if($ret['code']=='00') {
            echo 'stop success!  sn='.$sn.'    rtmp='.$rtmp.'  uid='.$zid;echo "\n";
        }else{
            echo 'stop error!  sn='.$sn.'    rtmp='.$rtmp.'  uid='.$zid.'  result='.json_encode($ret);echo "\n";
        }
        continue;
    }
    if(!strstr($sn, 'BETA')) {
        $str .= "('" . date("Y-m-d H:i").":00" . "'," . $zid. "," . $item['value'] . "),";
    }
}
$str = substr($str, 0, strlen($str) - 1);
$watchLiveStatistic->addBatch($str);

echo  "脚本执行结束:".date('Y-m-d H:i:s');echo "\n\n\n\n\n\n\n";



class Wcs
{
    /**
     * 查询在线
     */
    public static function getOnlineLive()
    {
        $url  = 'http://qualiter.wscdns.com/api/playerCount.jsp?';
        $key  = '437243A64FC5B35';
        $user = "dianhuan";
        $r    = time();
        $k    = md5($r . $key);
        $u    = 'cn01.push.dreamlive.com,cn01.flv.dreamlive.com,cn01.hls.dreamlive.com';
        $option = array(
            'n' => $user,
            'r' => $r,
            'k' => $k,
            'u' => $u,
            'realtime' => true
        );
        $url .= http_build_query($option);echo $url;echo "\n";
        $result = self::curl($url);
        return json_decode($result, true);
    }

    /**
     * 停播
     *
     * @param string $channel
     */
    public static function stopOnlineLive($rtmp)
    {
        $url  = 'http://cm.chinanetcenter.com/CM/cm-command.do?';
        $time = time();
        $username = 'dianhuan';
        $password = "Dream123~!@#";
        $token    = md5($username.$password.$rtmp);
        $option = array(
            'username' => $username,
            'password' => $token,
            'cmd'      => 'channel_manager',
            'action'   => 'forbid',
            'channel'  => $rtmp,
            'type'     => 'publish',
            'reltime'  => 86400,
            'rt'       => 1,
            //'abstime_end' => $time,
        );

        $url .= http_build_query($option);
        $result = self::curl($url);
        return json_decode($result, true);
    }

    public static function curl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        $handles = curl_exec($ch);
        curl_close($ch);
        return $handles;
    }
}
