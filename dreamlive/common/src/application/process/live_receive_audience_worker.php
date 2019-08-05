<?php
//维护一个直播间真实在线人数的排行榜
//*/1 * * * * php /home/dream/codebase/service/src/application/process/live_receive_audience_worker.php
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


function createHttpHeader($config)
{
    $nonce = mt_rand();
    $timeStamp = time();
    $sign = sha1($config['secret'].$nonce.$timeStamp);
    return array(
    'App-Key:'.$config['appkey'],
    'Nonce:'.$nonce,
    'Timestamp:'.$timeStamp,
    'Signature:'.$sign,
    );
}


function curlData($action, $config)
{
    $httpHeader = createHttpHeader($config);
    $ch = curl_init();
    $httpMethod = "GET";
    $httpHeader[] = 'Content-Type:Application/json';

    curl_setopt($ch, CURLOPT_URL, $action);
    curl_setopt($ch, CURLOPT_POST, $httpMethod=='POST');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //处理http证书问题
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $ret = curl_exec($ch);
    if (false === $ret) {
        $ret =  curl_errno($ch);
    }
    curl_close($ch);
    return $ret;
}

function addRedisLiveJoin($toUserId,$fromUserId)
{
    $cache   = Cache::getInstance("REDIS_CONF_CACHE");
    $key     = "string_join_live_".$toUserId."_".$fromUserId;
    $time    = time();
    $seconds = 14400;
    return $cache->add($key, $time, $seconds);
}

function delRedisLiveJoin($toUserId,$fromUserId)
{
    $cache   = Cache::getInstance("REDIS_CONF_CACHE");
    $key     = "string_join_live_".$toUserId."_".$fromUserId;
    $cache->delete($key);
}

while(true) {
    $action = Context::getConfig("RONGCLOUD_KAFKA_URL");//"http://kafkamsgapi.cn.ronghub.com/rtmsg/chatroom/qrymsg.json";
    $config = Context::getConfig("RONGCLOUD_CONF");
    
    $json_content = curlData($action, $config);
    
    $json_array = json_decode($json_content, true);
    
    if (!empty($json_array['results'])) {
        
        $date=  date("Ymd");
        file_put_contents("/usr/local/nginx/logs/kafka{$date}.log", $json_content . "\n", FILE_APPEND);
        //Logger::log("live_receive1", "users2", $json_array['results']);
        $rank = new Rank();
        foreach ($json_array['results'] AS $key => $value) {
            
            switch ($value['objectName']) {
            case "func:joinchatroommsgcontent"://加入直播间
                    
                    
                if (in_array($value['toUserId'], array(10749841,10851235,1))) {
                    //Messenger::sendLiveJoinMessage($value['toUserId'], $value['fromUserId'], '加入了直播间', Messenger::MESSAGE_TYPE_CHATROOM_JOIN);
                    break;
                }
                    
                Logger::log("kafka_join", "join", $value);
                    
                $userinfo = User::getUserInfo($value['fromUserId']);
                $cache   = Cache::getInstance("REDIS_CONF_CACHE");
                    
                    
                $only_join_one_room_key = "person_only_join_one_room_" . $value['fromUserId'];
                    
                $has_join_room = $cache->get($only_join_one_room_key);
                if ($has_join_room && $has_join_room != $value['toUserId']) {
                    $rank->setRank('audience', "delete", $value['fromUserId'], '1', $has_join_room);
                    $cache->delete($only_join_one_room_key);
                    //用户看播列表
                    $option = array(
                    'uid'    => $value['fromUserId'],
                    'liveid' => $has_join_room,
                    'time'   => date('Y-m-d H:i:s'),
                    'type'   => "func:leavechatroommsgcontent"
                    );
                    ProcessClient::getInstance("dream")->addTask("live_receive_audience_online", $option);
                }
                    
                $cache->add($only_join_one_room_key, $value['toUserId'], 3600);
                    
                $live = new Live();
                $liveinfo = $live->getLiveInfo($value['toUserId']);
                    
                //用户看播列表
                $option = array(
                            'uid'    => $value['fromUserId'],
                            'liveid' => $value['toUserId'],
                            'time'   => date('Y-m-d H:i:s'),
                            'type'   => $value['objectName']
                );
                ProcessClient::getInstance("dream")->addTask("live_receive_audience_online", $option);
                ProcessClient::getInstance("dream")->addTask("live_kafka_worker", array('liveid'=>$value['toUserId'], 'userid' => $value['fromUserId'], 'action' => 'JOIN'));
                    
                if ($liveinfo['status'] == Live::ACTIVING && $liveinfo['uid'] != $value['fromUserId']) {

                    $privacy = Privacy::getPrivacyInfoByLiveInfo($liveinfo['privacy']);
                        
                    if (!empty($privacy) && isset($privacy['privacyid'])) {
                        //$watchs_total      = Counter::increase(Counter::COUNTER_TYPE_LIVE_WATCHES, $value['toUserId'], 1);
                    } else {
                        $key = "dreamlive_live_user_real_num_".$value['toUserId'];
                        $result = json_decode($cache->get($key), true);
                        $live_num = $result['num']? $result['num'] : 0;
                        $sex_key_active = "big_liver_keys_set";
                        $sex_zhubo = $cache->zScore($sex_key_active, $liveinfo['uid']);//性感主播
                            
                        if ($live_num < 30 && empty($sex_zhubo)) {
                             $watchs_total = Counter::increase(Counter::COUNTER_TYPE_LIVE_WATCHES, $value['toUserId'], rand(1, 3));
                             ProcessClient::getInstance("dream")->addTask("live_distribute_robot", array('liveid'=>$value['toUserId'], 'num' => rand(1, 3)));
                        } else {
                               $watchs_total = Counter::increase(Counter::COUNTER_TYPE_LIVE_WATCHES, $value['toUserId'], rand(1, 3));
                        }
                            
                    }
                        
                        $user_guard = UserGuard::getUserGuardRedis($value['fromUserId'], $liveinfo['uid']);
                        
                        $join_redis_key = "join_room_from_online_". $value['toUserId']. "_" . $value['fromUserId'];
                        $from_online_join = $cache->get($join_redis_key);
                        
                        //ProcessClient::getInstance("dream")->addTask("live_kafka_worker", array('liveid'=>$value['toUserId'], 'userid' => $value['fromUserId'], 'action' => 'JOIN'));
                    if (empty($from_online_join)) {
                                 $chat = new Chat();
                                 $iskicked = $chat->isKicked($value['toUserId'], $value['fromUserId']);
                                 Logger::log("kafka_join", "join_send2", $iskicked);
                        if (!$iskicked) {
                            Logger::log("kafka_join", "join_send3", $value);
                            Messenger::sendLiveJoinMessage($value['toUserId'], $value['fromUserId'], '加入了直播间', Messenger::MESSAGE_TYPE_CHATROOM_JOIN, intval($user_guard), $privacy);
                        }
                    }
                        
                        $info = array(
                                "liveid"     => $value['toUserId'],
                                "userinfo"     => json_encode($userinfo),
                                "liveinfo"     => json_encode($liveinfo),
                                "userid"    => $value['fromUserId'],
                                "user_guard"=> $user_guard,
                                "time"         => time(),
                        );
                        ProcessClient::getInstance("dream")->addTask("audience_sort_worker", $info);                        
                        
                        Logger::log("kafka_join", "privacy", $privacy);
                        Logger::log("kafka_join", "join1", $value);
                }
            
                break;
            case "func:leavechatroommsgcontent"://退出直播间
                    
                if (in_array($value['toUserId'], array(10749841,10851235,1))) {
                    //Messenger::sendLiveJoinMessage($value['toUserId'], $value['fromUserId'], '退出了直播间', Messenger::MESSAGE_TYPE_CHATROOM_QUIT);
                    break;
                }
                    
                //用户看播列表
                $option = array(
                'uid'    => $value['fromUserId'],
                'liveid' => $value['toUserId'],
                'time'   => date('Y-m-d H:i:s'),
                'type'   => $value['objectName']
                );
                ProcessClient::getInstance("dream")->addTask("live_receive_audience_online", $option);
                    
                    
                Logger::log("kafka_quit", "quit", $value);
                $cache   = Cache::getInstance("REDIS_CONF_CACHE");
                $only_join_one_room_key = "person_only_join_one_room_" . $value['fromUserId'];
                    
                $cache->delete($only_join_one_room_key);
                    
                    
                $rank->setRank('audience', "delete", $value['fromUserId'], '1', $value['toUserId']);
                delRedisLiveJoin($value['toUserId'], $value['fromUserId']);
                ProcessClient::getInstance("dream")->addTask("live_kafka_worker", array('liveid'=>$value['toUserId'], 'userid' => $value['fromUserId'], 'action' => 'QUIT'));
                Messenger::sendLiveJoinMessage($value['toUserId'], $value['fromUserId'], '退出了直播间', Messenger::MESSAGE_TYPE_CHATROOM_QUIT);
                break;
            default:
                break;
            }
        }
    } else {
        Logger::log("kafka_empty", "getnull", array('time' => date("Y-m-d H:i:s")));
    }
    usleep(500);
}
?>