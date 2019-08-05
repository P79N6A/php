<?php
class MessageController extends BaseController
{
    public function getTokenAction()
    {
        /*{{{获取融云token*/
        $uid = Context::get("userid");

        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, "uid");

        $user = new User();
        $userinfo = $user->getUserInfo($uid);

        Interceptor::ensureNotEmpty($userinfo, ERROR_LOGINUSER_NOT_EXIST);        
        Interceptor::ensureNotEmpty($userinfo["nickname"], ERROR_PARAM_IS_EMPTY, "nickname");
        Interceptor::ensureNotEmpty($userinfo["avatar"], ERROR_PARAM_IS_EMPTY, "avatar");
        
        include_once 'message_client/RongCloudClient.php';
        $rongcloud_client = new RongCloudClient();
        
        try {
            //取缓存
            $cache = new Cache("REDIS_CONF_CACHE");
            $rongyun_token_redis_key = "dreamlive_rongcloud_token_" . $uid;
            
            $token = $cache->get($rongyun_token_redis_key);
        } catch (Exception $e) {
            $token = $rongcloud_client->getToken($uid, $userinfo["nickname"], $userinfo["avatar"]);
            Logger::log("rongcloud_gettoken_error", "dreamlive_cache_error", array("uid" => $uid, "errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
        }
        
        
        //缓存为空
        if (empty($token)) {
            $i = 0;
            while ($i < 3) {
                try {
                    $token = $rongcloud_client->getToken($uid, $userinfo["nickname"], $userinfo["avatar"]);
                } catch (Exception $e) {
                    Logger::log("rongcloud_gettoken_error", "retry{$i}", array("uid" => $uid, "errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
                }
                
                if (!empty($token)) {
                    break;
                }
                
                $i++;
            }
        }
        
        if (!empty($token)) {
            $cache->set($rongyun_token_redis_key, $token);
            $cache->expire($rongyun_token_redis_key, 3600*24);
        } else {
            $token = 'notokenretry_empty_token';
            Logger::log("rongcloud_gettoken_error", "retry3 empty", array("uid" => $uid, "errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
        }

        $this->render(array("token"=>$token));
    }/*}}}*/
    
    public function followerNoticeAction()
    {
        /*{{{粉丝通知*/
        $liveid     = $this->getParam('liveid');
        $text          = $this->getParam('text');
        $relateid    = $this->getParam('relateid');
        
        $sender       = Context::get('userid');
        
        $live = new Live();
        $live_info = $live->getLiveInfo($liveid);
        //扣星星逻辑
        $message = new Message();
        $bool = $message->notice($sender, $liveid, $text);
        
        if ($bool) {
            $live_info = array(
            'liveid'       => !empty($live_info['liveid']) ? $live_info['liveid'] : $liveid,
            'uid'          => !empty($live_info['uid']) ? $live_info['uid']: $sender,
            'type'        => 1,
            'text'        => $text,
            );
            include_once 'process_client/ProcessClient.php';
            ProcessClient::getInstance("dream")->addTask("live_start_broadcast", $live_info);
        }
        
        
        $this->render();
    }/*}}}*/
    
    
    public function checkAction()
    {
        $author = $this->getParam('author');
        $userid = $this->getParam('uid');
        $cache = new Cache("REDIS_CONF_CACHE");
        $white_live_uid_key = "private_room_white_users_keys";
        
        $white_user_info = json_decode($cache->get($white_live_uid_key), true);
        
        foreach ($white_user_info as &$val) {
            $ids = [];
            $ids = explode(',', $val['relateids']);
            
            $count = count($ids);
            $val['count'] = $count;
            $val['relateids'] = '';
        }
        print_r($white_user_info);
        var_dump($author);
        var_dump($userid);
        $a = Privacy::checkIsCanWatchPrivateRoom($author, $userid);
        
        var_dump($a);exit;
        
    }
    
    
    
    public function queryPriorityAction()
    {
        include_once 'message_client/RongCloudClient.php';
        $rongcloud_client = new RongCloudClient();
        $chatroomId = $this->getParam('liveid');
        //$ao = $rongcloud_client->queryChatroom($chatroomId);
    
        //var_dump($ao);
    
        $result = $rongcloud_client->queryPriority();
        var_dump($result);
        $this->render($result);
    }
    
    public function queryHistoryAction()
    {
        include_once 'message_client/RongCloudClient.php';
        $rongcloud_client = new RongCloudClient();
        $datetime = $this->getParam('datetime');

        $result = $rongcloud_client->queryMessgaeHistory($datetime);
        var_dump($result);
        $config = new Config();
        $results = $config->getConfig("china", "app_config", "ios", '1000000000000');
        
        $json_value = json_decode($results['value'], true);
        
        $price = isset($json_value['live_horn_price']) && !empty($json_value['live_horn_price']) ? $json_value['live_horn_price'] : 300;
        //var_dump($price);
        $result['price'] = $price;
        
        $this->render($result);
    }
    
    public function addWhiteListAction()
    {
        ini_set("display_errors", "On");
        error_reporting(E_ALL);
        include_once 'message_client/RongCloudClient.php';
        $rongcloud_client = new RongCloudClient();
        $chatroomId = $this->getParam('liveid');
        $userid = $this->getParam('userid');
        
        $result = $rongcloud_client->addChatroomWhiteList($chatroomId, $userid);
        var_dump($result);
        $this->render($result);
    }
    

    public function sendAction()
    {
        /*{{{私信*/
        $receiver     = intval($this->getParam('receiver'));
        $content      = trim(strip_tags($this->getParam('content')));
        $deviceid     = trim(strip_tags($this->getParam("deviceid")));
        $user_ip     = Util::getIP();
        $sender       = Context::get('userid');
        
        $appversion = trim(Context::get("version"));
        $platform    = trim(Context::get("platform"));
        
        //兼容android一个两次decode的bug2018-01-21
        if ($platform == "android" && $appversion == "3.0.0") {
            $content = urldecode($content);
        }

        Interceptor::ensureNotFalse(is_numeric($receiver) && $receiver > 0 && $receiver != $sender, ERROR_PARAM_INVALID_FORMAT, 'receiver');
        Interceptor::ensureNotFalse(strlen(trim($content)) > 0, ERROR_PARAM_INVALID_FORMAT, 'content');
        
        if(ForbiddenMsg::isForbidden($sender) || ForbiddenMsg::isForbidden($receiver)) {
            $this->render();
        }

        //检验是否包含屏蔽词
        $plus['receiver'] = $receiver;
        $plus['sender'] = $sender;
        if ($receiver != Messenger::KEFUID && $sender != Messenger::KEFUID) {
            $bool = FilterKeyword::check_shield($content, $plus);
            
            Interceptor::ensureNotFalse($bool, ERROR_KEYWORD_SHIELD, 'content');
        }
        

        //替换内容
        $replace_keyword = '';
        if ($receiver != Messenger::KEFUID && $sender != Messenger::KEFUID) {
            $replace_keyword = array();
            $content = FilterKeyword::content_replace($content, $replace_keyword);
            $replace_keyword = !empty($replace_keyword) ? implode(',', $replace_keyword) : '';
        }
        $type = !empty($replace_keyword) ? FilterKeyword::REPLACE : FilterKeyword::NORMAL;

        $message = new Message();
        $message->send($receiver, $sender, $type, $content, $replace_keyword, $user_ip, $deviceid);
        
        //--------------后台发送--------------
        include_once 'process_client/ProcessClient.php';
        $info = array(
                "receiver"     => $receiver,
                "content"     => $content,
                "sender"    => $sender,
                "sendtime"  => date("Y-m-d H:i:s"),
        );
        ProcessClient::getInstance("dream")->addTask("message_create_control", $info);
        //--------------后台发送--------------

        $this->render();
    }/*}}}*/


    public function setForbiddenWordAction()
    {
        $keyword = $this->getParam('keyword') ? trim($this->getParam('keyword')) : '';
        Interceptor::ensureNotEmpty($keyword, ERROR_PARAM_IS_EMPTY, "$keyword");

        $anti_spam_forbidden = new AntiSpamForbidden();
        //新增关键字
        if ($anti_spam_forbidden->addForbiddenWord($keyword)) {
            $this->render(array("ok"));
        } else {
            Interceptor::ensureNotFalse(false, ERROR_CUSTOM, 'ERROR');
        }
    }
    
    public function setForbiddenIpAction()
    {
        $ip = $this->getParam('ip') ? trim($this->getParam('ip')) : '';
        Interceptor::ensureNotEmpty($ip, ERROR_PARAM_IS_EMPTY, "$ip");
        
        $anti_spam_forbidden = new AntiSpamForbidden();
        //新增垃圾消息ip
        if ($anti_spam_forbidden->addForbiddenIp($ip)) {
            $this->render(array("ok"));
        } else {
            Interceptor::ensureNotFalse(false, ERROR_CUSTOM, 'ERROR');
        }
    }
    
    public function remForbiddenIpAction()
    {
        $ip = $this->getParam('ip') ? trim($this->getParam('ip')) : '';
        Interceptor::ensureNotEmpty($ip, ERROR_PARAM_IS_EMPTY, "$ip");
        
        $anti_spam_forbidden = new AntiSpamForbidden();
        //删除垃圾消息ip
        if ($anti_spam_forbidden->delForbiddenIp($ip)) {
            $this->render(array("ok"));
        } else {
            Interceptor::ensureNotFalse(false, ERROR_CUSTOM, 'ERROR');
        }
        $this->render();
    }
    
    public function remForbiddenDeviceAction()
    {
        $deviceid = $this->getParam('deviceid') ? trim($this->getParam('deviceid')) : '';
        Interceptor::ensureNotEmpty($deviceid, ERROR_PARAM_IS_EMPTY, "$deviceid");
        
        $anti_spam_forbidden = new AntiSpamForbidden();
        //删除垃圾消息device
        if ($anti_spam_forbidden->delForbiddenDevice($deviceid)) {
            $this->render(array("ok"));
        } else {
            Interceptor::ensureNotFalse(false, ERROR_CUSTOM, 'ERROR');
        }
        $this->render();
    }
    
    public function setForbiddenDeviceAction()
    {
        $deviceid= $this->getParam('deviceid') ? trim($this->getParam('deviceid')) : '';
        Interceptor::ensureNotEmpty($deviceid, ERROR_PARAM_IS_EMPTY, "$deviceid");
        
        $anti_spam_forbidden = new AntiSpamForbidden();
        //新增垃圾消息设备
        if ($anti_spam_forbidden->addForbiddenDevice($deviceid)) {
            $this->render(array("ok"));
        } else {
            Interceptor::ensureNotFalse(false, ERROR_CUSTOM, 'ERROR');
        }
    }
    
    public function getRongUsersAction()
    {
        $liveid = $this->getParam('liveid') ? trim($this->getParam('liveid')) : '1';
        try {
            include_once 'message_client/RongCloudClient.php';
            $rongcloud_client = new RongCloudClient();
            
            $result = $rongcloud_client->queryUsers($liveid, 1, 2);
            if ($result['code'] == 200) {
                $dating_num = $result['total'];
            }
        } catch (Exception $e) {
            
        }
        
        $this->render($result);
    }
    
    /**
     * 游戏竞猜消息
     */
    public function guessingAction()
    {
        $uid     = $this->getParam('uid');
        $title   = $this->getParam('title');
        $content = $this->getParam('content');
        $date    = $this->getParam('date');
        $md5     = $this->getParam('md5');
        
        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, "uid");
        Interceptor::ensureNotEmpty($title, ERROR_PARAM_IS_EMPTY, "title");
        Interceptor::ensureNotEmpty($content, ERROR_PARAM_IS_EMPTY, "content");
        
        $redis = Cache::getInstance("REDIS_CONF_CACHE");
        $key = "message_guessing_".md5($uid."_".$title."_".$content."_".$date);
        $num = $redis->incrBy($key, 1);
        Interceptor::ensureFalse($num > 1, ERROR_PARAM_FLOOD_REQUEST, "requst");
        
        $result = Messenger::sendCollectLog(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $uid,  $title, $content, $relateid = 0, $extends = array('date'=>$date));
        Interceptor::ensureNotFalse($result, ERROR_BIZ_PAYMENT_PRODUCT_DEFAULT, 'ERROR');

        $this->render();
    }
    
    /**
     * 检查私信权限
     */
    public function checkPrivateStatusAction()
    {
        $receiver = $this->getParam('receiver');
        $content  = $this->getParam('content');
        $uid        = $this->getParam('uid');
        $sender   = Context::get('userid');
        if (empty($sender)) {
            $sender = $uid;
        }
        $user = new User();
        $sender_info = $user->getUserInfo($sender);
        
        $is_can_send = Message::checkIsCanSend($sender, $receiver, $sender_info['level'], $sender_info['vip']);
        
        $this->render(array("bool" => $is_can_send));
    }
}
?>
