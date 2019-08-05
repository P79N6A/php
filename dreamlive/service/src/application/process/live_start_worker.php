<?php
class LiveStartBroadCast
{
    const EACH_STEP         = 2000;
    //     const TEXT_MESSAGE         = array(
    //             "死鬼，你关注的%s叫你了，快来！",
    //             "你关注的%s在直播中提到了你",
    //             "%s想和你聊天了，回应下呗~",
    //             "大惊喜啊，你关注的%s今天想直播点别的~",
    //             "好热闹，%s的直播又出什么幺蛾子了~",
    //             "%s说想你了，哎，说你呢~",
    //             "%s问你约不约？",
    //             "%s说你是不是劈腿了，都开播了还不来看~",
    //             "叮！你最想看的%s直播了，赶快进来吧~",
    //             "%s开始直播了，不来看？",
    //     );
    
    
    const TEXT_MESSAGE = array(
    "%s想你了，快去叙叙旧吧！",
    "%s无聊了，你怎么还不去陪她！",
    "%s正在high呢！赶快去围观吧！",
    "亲爱的，你认识%s吗，你们好像很有缘，快去看看吧！",
    "%s正在呼唤你呢，你忍心不搭理她吗?",
    "还记得%s吗？她正在直播呢，去看看吧！",
    "%s在跟人说悄悄话呢，你要不要去偷听下？",
    "听说%s在弄些好玩的玩意，去偷窥下去？",
    "快去直播间，%s在跟别人谈论你呢！",
    );
    
    static public function execute($value)
    {
        if (empty($value)) {
            return true;
        }
        
        $userid         = $value["params"]["uid"];
        $text           = $value["params"]["text"];
        $liveid         = $value["params"]["liveid"];
        $extends         = $value["params"]["extends"];
        $type             = $value["params"]["type"];
        $type             = empty($type) ? 0 : $type;
        $text             = empty($text) ? '' : $text;
        $text_message     = self::TEXT_MESSAGE;
        
        $userinfo = User::getUserInfo($userid);
        Logger::log("live_start_broadcast_room", "get task" . $type, array("liveid"=>$liveid, "uid"=>$userid, "nickname"=>$userinfo["nickname"], "line"=>__LINE__));

        //$follow_num = Counter::get(Counter::COUNTER_TYPE_FOLLOWERS, $userid);
        $dao_follower = new DAOFollower($userid);
        $follow_num = $dao_follower->countFollowers();
        Logger::log("live_start_broadcast_room", "get follower num succ" . $type, array("liveid"=>$liveid, "uid"=>$userid, "follow_num"=>count($follow_num), "line"=>__LINE__));
        // 查询 主播粉丝个数 拆分任务 分发任务
        if (!$follow_num) {
            $log_info = array(
            'liveid'    => $liveid,
            "userid"    => $userid,
            "followers"    => 0,
            "slice"        => 0,
            "text"        => $text,
            "ext"        => "",
            "line"        => __LINE__,
            "result"    => 0,
            "reason"    => "follower empty"
            );
            ProcessClient::getInstance("dream")->addTask("live_admin_start_broadcast", $log_info);
            Logger::log("live_start_broadcast_room", "follower empty" . $type, array("liveid"=>$liveid, "uid"=>$userid, "follow_num"=>$follow_num, "line"=>__LINE__));
        } else {

            $task_num = ceil($follow_num/self::EACH_STEP);
            for ($i = 1; $i <= $task_num; $i++) {
                $offset = ($i!=1) ? (($i-1)*self::EACH_STEP - 1) : 0;
                $info = array(
                "author" => $userinfo,
                "text"     => !empty($text) ? $text : sprintf($text_message[rand(0, 8)], $userinfo['nickname']),
                'userid' => $userid,
                "liveid" => $liveid,
                "type"      => $type,
                "extends"=> $extends,
                "offset" => $offset,
                "step"     => self::EACH_STEP,
                );
                $ret = ProcessClient::getInstance("dream")->addTask("live_start_broadcast_distribute", $info);//todo  live_start_broadcast_distribute 加入process config 中

                Logger::log("live_start_broadcast_room", "OK" . $type, array("liveid"=>$liveid, "uid"=>$userid,"offset"=>$offset, "step"=>self::EACH_STEP, "text"=>$text, "nickname"=>$userinfo["nickname"],"vr"=> json_encode($extends) . json_encode($userinfo), "ret"=>$ret, "line"=>__LINE__));
            }
            $log_info = array(
            'liveid'    => $liveid,
            "userid"    => $userid,
            "followers"    => $follow_num,
            "slice"        => $task_num,
            "text"        => $text,
            "ext"        => "",
            "line"        => __LINE__,
            "result"    => 1,
            "reason"    => ""
            );
            ProcessClient::getInstance("dream")->addTask("live_admin_start_broadcast", $log_info);
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
    $process->addWorker("live_start_broadcast",  array("LiveStartBroadCast", "execute"),  50, 2000);
    $process->run();
} catch (Exception $e) {
    Logger::log("process_error", "live_start_broadcast", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
}
?>
