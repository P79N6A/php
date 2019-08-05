<?php

class UsedRivivalLogWork
{
    
    public static function execute($value)
    {
        $userid        = $value["params"]["userid"];
        $roundid    = $value["params"]["roundid"];
        $questionid    = $value["params"]["questionid"];
        $addtime      = $value["params"]["addtime"];
        $answer        = $value["params"]["answer"];
        $correct_answer    = $value["params"]["correct_answer"];
        $order        = $value["params"]["order"];
        
        $model_rivival = new DAORivivalUsedLog();
        $model_rivival->add($userid, $questionid, $roundid, $answer, $correct_answer, $addtime, $order);
        
        $model_card = new DAOActivityHeaderCard();
        $model_card->minusCard($userid);
        
    }
}

set_time_limit(0);
ini_set('memory_limit', '1G');
ini_set("display_errors", "On");
error_reporting(E_ALL & ~ E_NOTICE & ~ E_WARNING);
$ROOT_PATH = dirname(dirname(dirname(dirname(__FILE__))));
$LOAD_PATH = array(
        $ROOT_PATH . "/src/www",
        $ROOT_PATH . "/config",
        $ROOT_PATH . "/src/application/controllers",
        $ROOT_PATH . "/src/application/models",
        $ROOT_PATH . "/src/application/models/libs",
        $ROOT_PATH . "/src/application/models/dao"
);
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $LOAD_PATH));

function __autoload($classname)
{
    $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    include_once "$classname.php";
}

require $ROOT_PATH . "/config/server_conf.php";
require_once 'process_client/ProcessClient.php';

try {
    $process = new ProcessClient("dream");
    $process->addWorker("rivival_used_log", array("UsedRivivalLogWork","execute"), 1, 20000);
    $process->run();
} catch (Exception $e) {
    ;
}
