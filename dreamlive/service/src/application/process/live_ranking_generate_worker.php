<?php
class RankingGenerateWorker
{
    public static function execute($value)
    {
        if (empty($value)) {
            return true;
        }
        
        $type       = $value["params"]["type"];
        $action     = $value["params"]["action"];
        $userid     = $value["params"]["userid"];
        $score      = $value["params"]["score"];
        $relateid      = $value["params"]["relateid"];
        $sender      = $value["params"]["sender"];
        $liveid      = $value["params"]["liveid"];
        
        $rank = new Rank();
        
        try{
            $rank->setRank($type, $action, $userid, $score, $relateid, $sender, $liveid);
        } catch (Exception $e) {
            Logger::log("process_error", "live_rank_generate1", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
        }
        return true;
    }
}

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
require_once 'process_client/ProcessClient.php';

try {
    $process = new ProcessClient("dream");
    $process->addWorker("live_rank_generate",  array("RankingGenerateWorker", "execute"),  50, 20000);
    $process->run();
} catch (Exception $e) {
    Logger::log("process_error", "live_rank_generate", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
}
