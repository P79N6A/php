<?php
ini_set("display_errors", "On");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
class VipConsumeAddWorker
{
    public static function execute($value)
    {
 
        $uid     = $value["params"]["uid"];
        $amount  = $value["params"]["amount"]/10;
        $orderid = $value["params"]["orderid"];

        try {
            Vip::addUserConsumeCurrent($uid, $amount);
        } catch (Exception $e) {
            return false;
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

    $process->addWorker("vip_consume_add_worker",  array("VipConsumeAddWorker", "execute"),  10, 10000);

    $process->run();
} catch (Exception $e) {
    ;
}

