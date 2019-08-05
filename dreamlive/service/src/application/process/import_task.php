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

class award extends DAOProxy
{
    public function __construct($i)
    {
        $this->setDBConf("MYSQL_CONF_TASK");
        $this->setTableName("user_task_award_".$i);
    }
    
    public function getList($addTime)
    {
        $sql = "select * from ".$this->getTableName()." where taskid not in (1,2,3,4,5,6) and result='N' and addtime>'".$addTime."' ";
        return $this->getAll($sql);
    }
}


//$addTime = '2017-04-15 00:00:00';
$addTime = date('Y-m-d H:i:s', time()-10*60);

echo  "脚本执行开始:".date("Y-m-d H:i:s");echo "\n";

for ($i = 0; $i < 100; $i ++) {
    $award = new award($i);
    $list = $award->getList($addTime);
    foreach($list as $item){
        if($item['uid']==10097254) {
            continue;
        }
        $awardExt = json_decode($item['award'], true);
        if (isset($awardExt['starlight']) && $awardExt['starlight']>0 || isset($awardExt['diamonds']) && $awardExt['diamonds']>0) {
            echo "uid=".$item['uid']."  taskid=".$item['taskid']."   award=".$item['award']."  id=".$item['id'];echo "\n";
            $result = StarTask::increase($item['uid'], $item['taskid'], $awardExt, $item['id']);
            if($result) {
                UserTaskAward::update($item['uid'], $item['id'], 'Y');
                echo "result=Y";echo "\n";
            }else{
                echo "result=N";echo "\n";
            }
        }
    }
}
echo  "脚本执行结束:".date("Y-m-d H:i:s");echo "\n";echo "\n";echo "\n";echo "\n";echo "\n";echo "\n";echo "\n";

