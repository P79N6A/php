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
    
    /**
     * 2017-11-13日
     */
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
    
    
    public function redisHmAction()
    {
        $chatroomId = $this->getParam('liveid');
        $num = $this->getParam('num');
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        //         $now = "2017-09-23 13:00:00";
        //         $days_seven = strtotime($now) + 24*3600*7;
        
        //         $a = 0.1;
        //         var_dump(round($a, 3));
        
        //         if (in_array(1, array())) {
        //             var_dump("qqqqqqqqqq");
        //         } else {
        //             var_dump("wwwwwwwwww");
        //         }
        //         if (substr("Hello.world",-6,1) == '.') {
        //             var_dump(substr("Hello.world",-3,1));
        //             var_dump(substr("Hello.world",-2,1));
        //             var_dump(substr("Hello.world",-1,1));
        //         }
        
        //         exit;
        //         for($i = 0; $i<= 10; $i++) {
        //             $time = $days_seven-time();
        //             $time = $time/1000;
        //             if ($i == 1) {
        //                 $time += 0.001;
        //             }
        //             if ($i == 2) {
        //                 $time += 0.01;
        //             }
        //             if ($i == 3) {
        //                 $time += 0.1;
        //             }
        //             $cache->zAdd("test_zadd", $time, 'aa' . $i);
        //             sleep(1);
        //         }
        $op = $cache->zRevRangeByScore("audience_" . $chatroomId, PHP_INT_MAX, 0, ['withscores' => true, 'limit' => [0, $num]]);
        //         $op = $cache->zRevRangeByScore("test_zadd", PHP_INT_MAX, 0, ['withscores' => true, 'limit' => [0, $num]]);
        //print_r($op);exit;
        
        
        //$a = $cache->hgetall("hmset_privacy_info_794");
        
        print_r($op);
        exit;
    }
    
    
    public function testAction()
    {
        $loginid         = Context::get("userid");
        $uid             = (int)$this->getParam("uid",  0);
        $receiver         = (int)$this->getParam("receiver",  0);
        $word             = $this->getParam("word",  0);
        
        $prison_day = 1;
        
        //Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $receiver, "由于您在pk中收入未达到标准，需要被关进小黑屋，有效期{$prison_day}天", "由于您在pk中收入未达到标准，需要被关进小黑屋，有效期{$prison_day}天", 0);
        
        
        $w = FilterKeyword::keyword_replace($word);
        
        print_r($w);
        var_dump($w['content']);
        exit;
        
        $content = "您被关闭小黑屋的记录被删除，你又可以开始和小伙伴pk啦！";
        $bopol = Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $receiver, $content, $content, 0);
        
        
        var_dump($bopol);
        
        $matchid= MatchMessage::getRedisMatchId('21000483');
        var_dump($matchid);
        $bool = Logger::log("pk_match_log", "sendMatchMemberScore", array("matchid" => $matchid, "sender" => 'xxx', "receiver" => 'xcccv'));

        var_dump($bool);
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $match_redis_key     = "hmset_match_info_" . $matchid;
        $living_user_key       = "User_living_cache";
        $devote_rank_key    = "devote_rank_" . $matchid . '_' . $uid;
        $match_info         = $cache->hgetall($match_redis_key);
        
        print_r($match_info);
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid($uid)");
        
        $user = new User();
        $userinfo = $user->getUserInfo($uid);
        
        $this->render($userinfo);
    }
    
    
    //     public function queryUserAction()
    //     {
    //         require_once 'message_client/RongCloudClient.php';
    //         $rongcloud_client = new RongCloudClient();
    //         $chatroomId = $this->getParam('liveid');
        
    //         $result = $rongcloud_client->queryUsers($chatroomId, 500, 2);
        
    //         $this->render($result);
    //     }

    //     public function addPriorityAction()
    //     {
    //         require_once 'message_client/RongCloudClient.php';
    //         $rongcloud_client = new RongCloudClient();
    //         $chatroomId = $this->getParam('liveid');
        
    //         $rongcloud_client->addPriority('RC:TxtMsg');
        
    //         $ao = $rongcloud_client->queryChatroom($chatroomId);
        
    //         var_dump($ao);

    //         $result = $rongcloud_client->queryPriority();

    //         $this->render($result);
    //     }
    
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
    
    //     public function removePriorityAction()
    //     {
    //         require_once 'message_client/RongCloudClient.php';
    //         $rongcloud_client = new RongCloudClient();

    //         $rongcloud_client->removePriority('RC:TxtMsg');

    //         $result = $rongcloud_client->queryPriority();

    //         $this->render($result);
    //     }

    public function sendAction()
    {
        /*{{{私信*/
        $receiver     = intval($this->getParam('receiver'));
        $content      = trim(strip_tags($this->getParam('content')));
        $deviceid     = trim(strip_tags($this->getParam("deviceid")));
        $user_ip     = Util::getIP();
        $sender       = Context::get('userid');

        Interceptor::ensureNotFalse(is_numeric($receiver) && $receiver > 0 && $receiver != $sender, ERROR_PARAM_INVALID_FORMAT, 'receiver');
        Interceptor::ensureNotFalse(strlen(trim($content)) > 0, ERROR_PARAM_INVALID_FORMAT, 'content');

        if(ForbiddenMsg::isForbidden($sender) || ForbiddenMsg::isForbidden($receiver)) {
            $this->render();
        }
        //检验是否包含屏蔽词
        $plus['receiver'] = $receiver;
        $plus['sender'] = $sender;

        $bool = FilterKeyword::check_shield($content, $plus);

        Interceptor::ensureNotFalse($bool, ERROR_KEYWORD_SHIELD, 'content');

        //替换内容
        $replace_keyword = array();
        $content = FilterKeyword::content_replace($content, $replace_keyword);
        $replace_keyword = !empty($replace_keyword) ? implode(',', $replace_keyword) : '';

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


   
    //     public function sendLevelAction()
    //     {
    //         $sender   = $this->getParam('sender');
    //         $liveid   = $this->getParam('liveid');
    //         $user = new User();
    //         $sender_info = $user->getUserInfo($sender);
    //         $bool = Messenger::sendUserLevelChange($liveid, $sender, '升级了', $sender, $sender_info['nickanme'], $sender_info['avatar']);
        
    //         var_dump($bool);
    //     }
    
    //     public function testAction()
    //     {/*{{{ 测试发送全局消息*/
    //         //ini_set("display_errors", "On");
    //         //error_reporting(E_ALL);
    //         $fid   = $this->getParam('fid');
    //         $sender = 22926591;
        
    //         //$fid = 22859939;
    //         $text = "速度围观";
    //         require_once 'message_client/RongCloudClient.php';
    //         $rongcloud_client = new RongCloudClient();
    //         $user = new User();
    //         $sender_info = $user->getUserInfo($sender);
        
    //         require_once 'message_client/RongCloudClient.php';
    //         $rongcloud_client = new RongCloudClient();
    //         $extends= array(
    //                 "userid"=>$sender,
    //                 "nickname"=>$sender_info['nickname'],
    //                 "avatar"=>$sender_info['avatar'],
    //                 "level"=>$sender_info['level'],
    //                 "relation"=>Follow::relation($fid, $sender)
    //         );
    //         $json = ['contentType' => 0, "content" => $text, "description" => ""];
    //         $wrappers = array(
    //                 "userid" => $fid,
    //                 "type" => 400,
    //                 "text" => json_encode($json),
    //                 "time" => time(),
    //                 "expire" => 180,
    //                 "extra" => $extends
    //         );
    //         $wrapper = array();
    //         $traceid = md5(serialize($wrappers));
        
    //         $wrappers["traceid"] = $traceid;
    //         $wrapper['content'] = $wrappers;
    //         $result = $rongcloud_client->sendBigNotice($fid, $sender, "$text", 'http://static.dreamlive.tv/images/c2159c431d826019f175f1ed0af9d94d.jpg', json_encode($wrapper));
    //         var_dump($result);
    //         exit;
    //         $sender = 30000000;
    //         $content = "新主播11111房间人数:111 请相关人员多多关注!";
    //         $yunying_uid = ['10899916'];
        
    //         $bool = Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, implode(',', $yunying_uid), $content, $content, 0);
    //         //$bool = Messenger::sendPrivateMessage($yunying_uid, $sender, '追梦客服', 'http://static.dreamlive.tv/images/097b9394eb56de7c3872f1e70f0ec695.png', 4, Follow::relation($yunying_uid, $sender), $content);
    //         var_dump($bool);
    //         exit;
    //         $receiver = $this->getParam('receiver');
    //         $content  = $this->getParam('content');
    //         $liveid   = $this->getParam('liveid');
    //         $id   = $this->getParam('id');
    //         $sender   = Context::get("userid");
    //         $content = "你好啊，[1]";
        
        
    //         function replace_emoji($matches)
    //         {
    //             print_r($matches);
    //             // 通常: $matches[0]是完成的匹配
    //             // $matches[1]是第一个捕获子组的匹配
    //             $matches[0] = str_replace('[', '', $matches[0]);
    //             $matches[0] = str_replace(']', '', $matches[0]);
    //             // 以此类推
    //             return Emoji::getEmoji($matches[0]);
    //         }
        
        
        
        
    //         $content = preg_replace_callback('#\[[1-9]\]#','replace_emoji',$content);
        
    //         var_dump($content);
    //         exit;
    //         //RobotChat::RandRobots('10873064', $content);
    //         for ($i =1; $i <= 5; $i++) {
    //             Emoji::getEmoji($i);
    //         }
    //         exit;
    //         /**
    //          * 1 鼓掌
    //          * 2 牛(竖大拇指)
    //          * 3 笑脸
    //          * 4 亲亲
    //          * 5 开心
    //          * @param int $id
    //          */
    //         $emoji_content = Emoji::getEmoji($id);
    //         $content = "你好[1],大白天";
    //         var_dump($content);
        
    //         $emoji_content = preg_replace_callback('|([1-9])|','replace_emoji',$content);
        
    //         var_dump($emoji_content);
        
    //         $cache = Cache::getInstance("REDIS_CONF_USER");
    //         $array = $cache->zRevRange("liverobots_25032", 0, -1);
    //         $rand_keys = array_rand($array, 1);
        
    //         $sender = $array[$rand_keys];
    //         RobotChat::sendChat(25032, $sender, $emoji_content);
        
    //         $h = date('h');
    //         var_dump($h);
    //         $content = RobotConfig::giveGift(3891);
    //         var_dump($content);
    //         $y= RobotConfig::publicChat('8888');
    //         $content = RobotConfig::getHome('天津');
    //         var_dump($content);
    //         $content = RobotConfig::getSex('F');
        
    //         var_dump($content);
    //         $content = RobotConfig::getTime();
        
    //         var_dump($content);exit;
    //         $aes = new AES;
    //         $iv     = substr(md5('11111111111'), 0, 32);

    //         $key     = substr(md5('sssssssssss'), 0, 32);
    //         $a = $aes->aes256cbcEncrypt("11122222", $iv, $key);

    //         $b = $aes->aes256cbcEncrypt("中文aes演示", $iv, $key);
    //           var_dump($a);
    //           var_dump($b);
    //           var_dump($iv);
    //           var_dump($key);
    //           $c = $aes->aes256cbcDecrypt($a, $iv, $key);
    //           $d = $aes->aes256cbcDecrypt($b, $iv, $key);
        
    //         var_dump($c);
    //         var_dump($d);
        
    //         $aes = new AES;
    //         $iv     = substr(md5('11111111111'), 0, 16);
        
    //         $key     = substr(md5('sssssssssss'), 0, 16);
    //         $a = $aes->aes128cbcEncrypt("11122222", $iv, $key);
        
    //         $b = $aes->aes128cbcEncrypt("中文aes演示", $iv, $key);
    //         var_dump($a);
    //         var_dump($b);
        
    //         var_dump('--------');
    //         var_dump($iv);
    //         var_dump($key);
    //         $c = $aes->aes128cbcHexDecrypt($a, $iv, $key);
    //         $d = $aes->aes128cbcHexDecrypt($b, $iv, $key);
        
    //         var_dump($c);
    //         var_dump($d);
        
    //         exit;
    //         //密钥
    //         $keyStr = '6D6A39C7078F6783E561B0D1A9EB2E68';
    //         //加密的字符串
    //         $plainText = 'http://59.110.145.201/upload/test';
        
    //         $aes = new CryptAES();
    //         $aes->set_key($keyStr);
    //         $aes->require_pkcs5();
    //         $encText = $aes->encrypt($plainText);
    //           var_dump($encText);
    //         $a = $aes->decrypt(strtolower($encText));
        
    //         var_dump($a);
    //         exit;
    //         //$userinfo = User::getUserInfo($sender);
    //         Messenger::sendSquareChatroomStop($liveid);//通知广场大厅直播结束
        
    //         var_dump('ddd');
    //         $stream = array(
    //                 'ws' => array(
    //                     'prefix' => 'chinanetcenter',
    //                     'url' => 'cn01.push.dreamlive.tv',
    //                     'clientIp' => array('124.163.204.100','43.226.162.65')
    //                 ),
    //                 'server' => array('http://59.110.145.201/upload/test')

    //         );

    //         $bool = Messenger::sendUserTestSpeed($receiver,$stream);
    //         var_dump($bool);
    //         exit;

    //         $this->render();
    //     }/*}}}*/

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
        $this->render();
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
        $this->render();
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
        $deviceid = $this->getParam('deviceid') ? trim($this->getParam('deviceid')) : '';
        Interceptor::ensureNotEmpty($deviceid, ERROR_PARAM_IS_EMPTY, "$deviceid");
        
        $anti_spam_forbidden = new AntiSpamForbidden();
        //新增垃圾消息设备
        if ($anti_spam_forbidden->addForbiddenDevice($deviceid)) {
            $this->render(array("ok"));
        } else {
            Interceptor::ensureNotFalse(false, ERROR_CUSTOM, 'ERROR');
        }
        $this->render();
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
        $sender   = Context::get('userid');
        
        $user = new User();
        $sender_info = $user->getUserInfo($sender);
        
        $is_can_send = Message::checkIsCanSend($sender, $receiver, $sender_info['level'], $sender_info['vip']);
        
        $this->render(array("bool" => $is_can_send));
    }
}
?>
