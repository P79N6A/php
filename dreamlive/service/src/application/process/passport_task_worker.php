<?php
ini_set("display_errors", "On");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
class TaskWorker
{
    public static function execute($value)
    {
        $userid = $value["params"]["uid"];
        $taskid = $value["params"]["taskid"];
        $num    = $value["params"]["num"];
        $ext    = $value["params"]["ext"];

        try{
            $task = new Task();
            $task->execute($userid, $taskid, $num, $ext);
        }catch (Exception $e) {
            Logger::log("task_err", "worker", array("uid" => $userid,"taskid"=>$taskid,"num"=>$num,"ext"=>$ext,"errno" => $e->getCode(),"errmsg" => $e->getMessage()));
        }

        return true;
    }
}

$ROOT_PATH = dirname(dirname(dirname(dirname(__FILE__))));
$LOAD_PATH = array(
    $ROOT_PATH . "/src/www",
    $ROOT_PATH."/config",
    $ROOT_PATH."/src/application/controllers",
    $ROOT_PATH."/src/application/models",
    $ROOT_PATH."/src/application/models/libs",
    $ROOT_PATH."/src/application/models/dao",
    $ROOT_PATH."/src/application/models/task"
);
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $LOAD_PATH));

function __autoload($classname)
{
    $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    include_once "$classname.php";
}
require $ROOT_PATH."/config/server_conf.php";

require_once 'process_client/ProcessClient.php';

try{
    $process = new ProcessClient("dream");

    $process->addWorker("passport_task_execute",  array("TaskWorker", "execute"),  20, 200000);

    $process->run();
} catch (Exception $e) {
    ;
}

