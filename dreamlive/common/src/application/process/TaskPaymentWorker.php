<?php
ini_set("display_errors", "On");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
class TaskPaymentWorker
{
    public static function execute($value)
    {
        $uid     = $value["params"]["uid"];
        $amount  = $value["params"]["amount"];
        $orderid = $value["params"]["orderid"];

        try {
            $task = new Task();
            $task->depositExecute($uid, $amount);
        } catch (Exception $e) {
            return ;
        }
        $DAODepositTaskLog = new DAODepositTaskLog();
        $DAODepositTaskLog->modifyDepositTaskLog($orderid);
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

    $process->addWorker("task_payment_worker",  array("TaskPaymentWorker", "execute"),  20, 200000);

    $process->run();
} catch (Exception $e) {
    ;
}

