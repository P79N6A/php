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
    public function __construct($i)
    {
        $this->setDBConf("MYSQL_CONF_MESSAGE");
        $this->setTableName("user_guard_detail");
    }
    
    public function getList()
    {
        $sql = "select * from ".$this->getTableName()." where status='N' ";
        return $this->getAll($sql);
    }
    
    public function updateData($id, $option)
    {
        return $this->update($this->getTableName(), $option, "id=?", $id);
    }

}


//$addTime = '2017-04-15 00:00:00';
$addTime = date('Y-m-d H:i:s', time()-10*60);

echo  "脚本执行开始:".date("Y-m-d H:i:s");echo "\n";

require_once dirname(__FILE__).DIRECTORY_SEPARATOR. "task". DIRECTORY_SEPARATOR. "VerifyTask.php";
require_once dirname(__FILE__).DIRECTORY_SEPARATOR. "task". DIRECTORY_SEPARATOR. "AwardTask.php";

$Task      = new Task();
$taskInfo  = $Task->getTaskInfo(VerifyTask::TASK_TIIMES_GUARD_ID);

$guard = new guard();
$list = $guard->getList();


foreach($list as $item){
    $award     = AwardTask::getTaskAward($item['uid'], VerifyTask::TASK_TIIMES_GUARD_ID, Task::TASK_TYPE_TIMES, 1, json_decode($taskInfo['extend'], true), array('price'=>$guard['price']));
    $result    = UserTaskAward::AddUserTaskAward($item['uid'], VerifyTask::TASK_TIIMES_GUARD_ID, $award);
    $Task->addUserExp($item['uid'], $award['exp'], $liveid = '');
    $guard->updateData($item['id'], array('status'=>'Y'));
}
      
echo  "脚本执行结束:".date("Y-m-d H:i:s");echo "\n";echo "\n";echo "\n";echo "\n";echo "\n";echo "\n";echo "\n";

