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

class guard extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_MESSAGE");
        $this->setTableName("user_guard");
    }
    
    public function getList()
    {
        $time = date('Y-m-d H:i:s', time()+60);
        $sql  = "select * from ".$this->getTableName()." where endtime>? ";
        return $this->getAll($sql, $time);
    }
}


//$addTime = '2017-04-15 00:00:00';
echo  "脚本执行开始:".date("Y-m-d H:i:s");echo "\n";

$guard = new guard();
$list = $guard->getList();
foreach($list as $item){
    $expires = strtotime($item['endtime'])-time();
    $result  = UserGuard::addRedisBySet($item['uid'], $item['relateid'], $item['type'], $expires);
    echo 'uid='.$item['uid'].'  relateid='.$item['relateid'].'  type='.$item['type'].'  expires='.$expires;echo "\n";
}
      
echo  "脚本执行结束:".date("Y-m-d H:i:s");echo "\n";echo "\n";echo "\n";echo "\n";echo "\n";echo "\n";echo "\n";

