<?php
/**
 * 把所有消息按聊天室区分放入队列，
 *
 * @author xubaoguo
 */
class CreateReplayTitleQueueWorker
{
    const DEAL_NUMBER_PER = 1000;
    const DEAL_REPLAY_TITLE_QUEUE_NAME     = 'live_replay_list_';
    const DEAL_REPLAY_TITLE_QUEUE_TOTAL = 'live_replay_total_';//字幕总数计数器
    
    public static function execute($value)
    {
        $liveid       = $value["params"]["liveid"];
        $sender     = $value["params"]["sender"];
        $content     = $value["params"]["content"];
        
        $live = new Live();
        $liveinfo = $live->getLiveInfo($liveid);
        
        if ($liveinfo['replay'] == 'N' || $liveinfo['deleted'] == 'Y') {//不在直播状态或没回访权限
            return true;
        }
        
        
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $message_array = json_decode($content, true);
        if (in_array($message_array['content']['type'], array(201, 204))) {
            if (!isset($message_array['addtime'])) {
                $message_array['addtime'] = date("Y-m-d H:i:s");
                $content = json_encode($message_array);
            }
            $total_title_all = $cache->INCR(self::DEAL_REPLAY_TITLE_QUEUE_TOTAL . $liveid);        
            $slice = ceil($total_title_all / self::DEAL_NUMBER_PER);
            $total = $cache->lPush(self::DEAL_REPLAY_TITLE_QUEUE_NAME . $liveid . "_{$slice}", $content);
            //file_put_contents('/tmp/1/create_title_' . date("Ymd") .'.log', json_encode($value)."\n", FILE_APPEND);
            $process = new ProcessClient("dream");
            $process->addTask("deal_replay_title", array("liveid" => $liveid, "live_add_time" => empty($liveinfo['addtime']) ? date("Y-m-d H:i:s") : $liveinfo['addtime'], 'slice' => $slice));
            return true;
        }
        
        if (!in_array($liveinfo['status'], array(Live::ACTIVING, Live::PAUSED))) {//不在直播状态或没回访权限
            return true;
        }
        
        $total_title_all = $cache->INCR(self::DEAL_REPLAY_TITLE_QUEUE_TOTAL . $liveid);        
        $slice = ceil($total_title_all / self::DEAL_NUMBER_PER);        
        $total = $cache->lPush(self::DEAL_REPLAY_TITLE_QUEUE_NAME . $liveid . "_{$slice}", $content);
        
        if ($total == self::DEAL_NUMBER_PER) {
            //file_put_contents('/tmp/1/create_title_' . date("Ymd") .'.log', json_encode($value)."\n", FILE_APPEND);
            $process = new ProcessClient("dream");
            $process->addTask("deal_replay_title", array("liveid" => $liveid, "live_add_time" => empty($liveinfo['addtime']) ? date("Y-m-d H:i:s") : $liveinfo['addtime'], 'slice' => $slice));
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
    $process->addWorker("push_replay_title",  array("CreateReplayTitleQueueWorker", "execute"),  50, 20000);
    $process->run();
} catch (Exception $e) {
    ;
}
?>