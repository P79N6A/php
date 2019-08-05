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



class LiveReplayurl extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setTableName("live");
    }
    
    public function getList()
    {
        $sql = "select * from ".$this->getTableName()." where replayurl !='' and deleted=? and endtime>'2017-09-14 04:15:21' order by addtime asc ";
        return $this->getAll($sql, array('deleted'=>'N'));
    }
}

class UserFeeds extends DAOProxy
{
    public function __construct($uid)
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setShardId($uid);
        $this->setTableName("userfeeds");
    }

    public function isExist($uid,$relateid,$type)
    {
        $sql = "select count(1) as cnt from  " . $this->getTableName() . " where uid=? and relateid=? and type=? ";
        $result = $this->getRow($sql, array($uid,$relateid,$type));
        if (isset($result['cnt']) && $result['cnt'] > 0) {
            return true;
        }
        return false;
    }
    
    public function delFeeds($uid, $relateid, $type)
    {
        $sql = "delete from {$this->getTableName()} where uid=? and relateid=? and type=?";
        return $this->Execute($sql, array($uid, $relateid, $type)) ? true : false;
    }
}


//$addTime = '2017-04-15 00:00:00';
$addTime = date('Y-m-d H:i:s', time()-86400);

echo  "脚本执行开始:".date("Y-m-d H:i:s");echo "\n";



$live = new Live();
$liveReplayurl = new LiveReplayurl();
$list = $liveReplayurl->getList($addTime);
foreach ($list as $item) {
    if($live->isPeplayPermissions($item['uid']) || Privacy::getPrivacy($item['uid'])) {
        continue;
    }else{
        $userFeeds = new UserFeeds($item['uid']);
        $userFeeds->delFeeds($item['uid'], $item['liveid'], Feeds::FEEDS_REPLAY);
        echo "uid=".$item['uid']."   liveid=".$item['liveid']."   type=".Feeds::FEEDS_REPLAY;echo "\n";
    }
}

echo  "脚本执行结束:".date("Y-m-d H:i:s");echo "\n";echo "\n";echo "\n";echo "\n";echo "\n";echo "\n";echo "\n";

