<?php
class ChatController extends BaseController
{
    public function sendAction()
    {
        $liveid      = $this->getParam("liveid")  ? intval($this->getParam('liveid')) : 0;
        $content     = $this->getParam("content");
        $relateids     = $this->getParam("relateids");
        $deviceid     = trim(strip_tags($this->getParam("deviceid")));
        
        $sender  = Context::get("userid");
        include_once 'process_client/ProcessClient.php';
        
        Interceptor::ensureNotFalse($liveid > 0, ERROR_PARAM_INVALID_FORMAT, "liveid");
        $anti_spam_forbidden = new AntiSpamForbidden();
        $user_ip = Util::getIP();
        
        if ($anti_spam_forbidden->checkForbiddenIp($user_ip) || in_array($user_ip, array('183.197.247.204','61.186.15.122','171.42.158.106', '183.197.247.0'))) {
            $this->render();
        }
        
        $userinfo = User::getUserInfo($sender);
        
        if ($userinfo['level'] < 5) {
            include_once 'message_client/RongCloudClient.php';
            $rongcloud_client = new RongCloudClient();
            $result = $rongcloud_client->queryChatroomUserExist($liveid, $sender);
            if(!($result['code'] == '200' && $result['isInChrm'])) {
                $this->render();
            }
        }
        
        $live = new Live();
        $live_info = $live->getLiveInfo($liveid);
        Interceptor::ensureNotEmpty($live_info, ERROR_BIZ_LIVE_NOT_EXIST);
        Interceptor::ensureNotFalse($live_info["status"] == Live::ACTIVING, ERROR_BIZ_LIVE_NOT_ACTIVE);
        
        if (!ChatWhiteList::checkIsCanChat($liveid, $sender)) {//验证是否设置了禁用聊天，只有白名单可以聊天
            $this->render();
        }

        if($anti_spam_forbidden->checkForbidden($content)) {
            ProcessClient::getInstance("dream")->addTask(
                "forbidden_control", array(
                "seconds"     => 3600*24*3,
                "uid"         => $sender,
                "reason"     => '发送后台设置的垃圾消息',
                "sender"    => 10000003
                )
            );
            
            $this->render();
        }
        
        // 是否被播主拉黑
        $author = $live_info["author"]["uid"];
        
        
        //---------------------公聊字符数限制------------------//
        $cache = new Cache("REDIS_CONF_CACHE");
        $version = str_replace('.', '', Context::get("version"));
        $version = intval($version);
        
        if ($version >= 2910 || $version >= 300) {
            $config = new Config();
            $results = $config->getConfig("china", "chat_message_config", "server", '1000000000000');
            $json_value = json_decode($results['value'], true);
            
            $separatorlevel = !empty($json_value['separatorlevel']) ? intval($json_value['separatorlevel']) : 11;
            $mb_len = mb_strlen($content, 'utf8');
            $len    = strlen($content);
            $chat_content_num = ($mb_len + $len) / 2;
            $common_chat_max_num = 10;
            $user_guard = UserGuard::getUserGuardRedis($sender, $author);
            if (empty($userinfo['vip']) && empty($user_guard)) {
                if ($userinfo['level'] < $separatorlevel) {
                    
                    $common_chat_max_num     = !empty($json_value['levelbelow']['maxwords']) ? intval($json_value['levelbelow']['maxwords']) : 10;
                    $common_chat_frequency    = !empty($json_value['levelbelow']['frequency']) ? intval($json_value['levelbelow']['frequency']) : 0;
                } else {
                    $common_chat_max_num     = !empty($json_value['levelabove']['num']) ? intval($json_value['levelabove']['num']) : 30;
                    $common_chat_frequency    = !empty($json_value['levelabove']['frequency']) ? intval($json_value['levelabove']['frequency']) : 0;
                }
            } else {
    
                $common_chat_max_num     = !empty($json_value['vip']['num']) ? intval($json_value['vip']['num']) : 30;
                $common_chat_frequency    = !empty($json_value['vip']['frequency']) ? intval($json_value['vip']['frequency']) : 0;
            }
            //Logger::log("follow_notice_log", "step2", array("ok" => 'done',"sender"=>$sender,"score" => json_encode($json_value)));
            
            if (!empty($common_chat_frequency)) {
                $frequency_redis_key = "chat_frequency_" . $sender;
                if ($cache->INCR($frequency_redis_key) > 1) {
                    Interceptor::ensureNotFalse(false, ERROR_CHAT_MESSAGE_OVER_FREQUENCY, "$common_chat_frequency");
                }
                $cache->expire($frequency_redis_key, $common_chat_frequency);
            }
            $common_chat_max_num = $common_chat_max_num * 2;
            Interceptor::ensureNotFalse(! ($chat_content_num > $common_chat_max_num), ERROR_CHAT_MESSAGE_NUM_OVERRUN, "num:{$chat_content_num}-vip:{$userinfo['vip']} - num:$common_chat_max_num - guard:$user_guard - level:{$userinfo['level']} ");
            //--------------------公聊字符数限制--------------------//
        }
        
        include_once 'antispam_client/policy/Policy.php';
        if (Policy::isDeny($content)) {
            $dao = new DAOChat($liveid);
            $dao->addMessage($liveid, $sender, 3, $content, ' ');
            $this->render();
        }
        
        //检验是否包含屏蔽词
        $plus['liveid'] = $liveid;
        $plus['sender'] = $sender;
        
        //Interceptor::ensureNotFalse(FilterKeyword::check_shield($content, $plus), ERROR_KEYWORD_SHIELD, 'content');
        if (!FilterKeyword::check_shield($content, $plus)) {
            $this->render();
        }
        //替换内容
        $replace_keyword = array();
        $content = FilterKeyword::content_replace($content, $replace_keyword);
        $replace_keyword = !empty($replace_keyword) ? implode(',', $replace_keyword) : '';

        $type = !empty($replace_keyword) ? FilterKeyword::REPLACE : FilterKeyword::NORMAL;

        
        $chat = new Chat();

        // 禁言检查
        Interceptor::ensureNotFalse(!$chat->isSilence($liveid, $sender), ERROR_BIZ_CHATROOM_USER_HAS_SILENCED);

        Interceptor::ensureNotFalse(!Blocked::exists($author, $sender), ERROR_USER_IS_BLOCKED);
        
        if (!in_array($author, Context::getConfig("SUPER_POPULAR_USERS"))) {
            Interceptor::ensureNotFalse(!Blocked::exists($author, $sender), ERROR_USER_IS_BLOCKED);
        }

        
        //每人每天发五次消息就升一级
        $key = "user_level_send_times_{$sender}";
        $sendtimes = 0;
        if ($cache->exists($key)) {
            $sendtimes = $cache->incr($key);
        } else {
            $cache->sAdd($key, 1);
            $now = time();
            $endtime = strtotime(date("Y-m-d", strtotime("+1 day")));
            $cache->expire($key, ($endtime-$now));
            $sendtimes = 1;
        }
    
        
        if ($sendtimes <= 5) {//发送给处理等级变化的任务
            
            ProcessClient::getInstance("dream")->addTask("passport_task_execute", array("uid" => $sender, "taskid" => Task::TASK_ID_COMMENT, "num" => 1, "ext"=>json_encode(array('liveid'=>$liveid))));
        }

        //$chat->send($liveid, $sender, $type, $content, $live_info['watches'], $replace_keyword, $relateids);
        //聊天异步
        $info = array(
                "liveid"     => $liveid,
                "content"     => $content,
                "sender"    => $sender,
                "watches"     => $live_info['watches'],
                "replace_keyword" => $replace_keyword,
                "ip"        => $user_ip,
                "deviceid"    => $deviceid,
                "relateids"    => $relateids
        );
        ProcessClient::getInstance("dream")->addTask("chat_send_async", $info);
        
        Counter::increase(Counter::COUNTER_TYPE_LIVE_CHATS, $liveid, 1);
        
        //--------------机器人--------------
        if ($sender != $live_info['uid']) {
            
            
            //--------------后台发送--------------
            $info = array(
            "liveid"     => $liveid,
            "content"     => $content,
            "uid"        => $sender,
            "ip"        => $user_ip,
            "deviceid"    => $deviceid,
            "chat_time" => date("Y-m-d H:i:s"),
            );
            ProcessClient::getInstance("dream")->addTask("chat_create_control", $info);
            //--------------后台发送--------------
            
            //--------------istudio合作发送--------------
            //             $info = array(
            //                     "liveid"     => $liveid,
            //                     "content"     => $content,
            //                     "uid"        => $sender,
            //                     "time"         => time(),
            //             );
            //             ProcessClient::getInstance("dream")->addTask("istudio_receive_msg", $info);
            //--------------istudio合作发送--------------
        }
        //RobotChat::recordLastChat($liveid);
        //--------------机器人--------------
        
        
        $send_gift_total          = (int) Counter::get(Counter::COUNTER_TYPE_PAYMENT_SEND_GIFT, $sender);
        $receive_gift_total       = (int) Counter::get(Counter::COUNTER_TYPE_PAYMENT_RECEIVE_GIFT, $sender);
        
        if ($send_gift_total <= 50 && $receive_gift_total <= 10 ) {//送礼大于等于100钻,或收入大于10钻
            
            $config = new Config();
            $results = $config->getConfig("china", "public_chat", "server", '1000000000000');
            $public_chat_array = json_decode($results['value'], true);
            $md5_array = [];
            foreach ($public_chat_array as $val) {
                $md5_array[] = md5($val['content']);
            }
            
            $md5content = md5($content);
            //排除公聊便捷语
            if (!in_array($md5content, $md5_array)) {
                $slience_redis_key = "chat_slience_" . $sender . "_" . $md5content;
                
                if ($cache->INCR($slience_redis_key) >= 5) {
                    ProcessClient::getInstance("dream")->addTask(
                        "forbidden_control", array(
                        "seconds"     => 3600*24,
                        "uid"         => $sender,
                        "reason"     => '1分钟发送5次重复内容',
                        "sender"    => 10000001
                        )
                    );
                }
                
                $cache->expire($slience_redis_key, 60);
            }
        }
        $this->render();
    }
    
    /**
     * 测试新方法
     */
    public function getWatcheListAction()
    {
        $liveid  = $this->getParam("liveid")  ? intval($this->getParam('liveid'))            : 0;
        $sender  = Context::get("userid");
        
        Interceptor::ensureNotFalse($liveid > 0, ERROR_PARAM_INVALID_FORMAT, "liveid");
        
        $rank = new Rank();
        $audience = $rank->getAudienceList('audience_' . $liveid, 50);
        
        $live = new Live();
        $live_info = $live->getLiveInfo($liveid);
        $privacy = Privacy::getPrivacyInfoByLiveInfo($live_info['privacy']);
        
        if (!empty($privacy) && isset($privacy['privacyid'])) {
            $watches = Counter::get(Counter::COUNTER_TYPE_LIVE_PRIVACY_WATCHES, $privacy['privacyid']);
        } else {
            $watches = Counter::get(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid);
        }

        $this->render(array("audience" => $audience, "watches" => $watches));
    }
    
    
    //观众列表 优化以后的
    public function getAudienceAction()
    {
        $liveid  = $this->getParam("liveid")  ? intval($this->getParam('liveid'))            : 0;
        $sender  = Context::get("userid");
        
        Interceptor::ensureNotFalse($liveid > 0, ERROR_PARAM_INVALID_FORMAT, "liveid");
        
        $rank = new Rank();
        $audience = $rank->getAudienceList('audience_' . $liveid, 50);
        
        $this->render(array("audience" => $audience, "watches" => Counter::get(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid)));
    }
    
    // 添加禁言
    public function addSilenceAction()
    {
        $liveid   = intval($this->getParam("liveid"));
        $relateid = intval($this->getParam('uid'));
        $operator = intval(Context::get('userid'));

        $chat = new Chat();
        Interceptor::ensureNotFalse(!$chat->isSilence($liveid, $relateid), ERROR_BIZ_CHATROOM_USER_HAS_SILENCED);

        $result = $chat->addSilence($liveid, $relateid);

        $this->render($result);
    }

    // 取消禁言
    public function delSilenceAction()
    {
        $liveid   = intval($this->getParam("liveid"));
        $relateid = intval($this->getParam('uid'));
        $operator = intval(Context::get('userid'));

        $chat = new Chat();
        $chat->delSilence($liveid, $relateid);

        $this->render();
    }

    public function kickAction()
    {
        $liveid   = intval($this->getParam("liveid"));
        $relateid = intval($this->getParam('uid'));
        $operator = intval(Context::get('userid'));

        $chat = new Chat();
        $result = $chat->kick($liveid, $relateid, $operator);

        $this->render($result);
    }

    // 场控列表
    public function getPatrollersAction()
    {
        $author = Context::get("userid");
        $relateid = trim($this->getParam('relateid'));
        
        $userid = !empty($relateid) ? $relateid : $author;
        $patroller = new Patroller();
        
        $patrollers = $patroller->getPatrollers($userid);

        $this->render(array("patrollers"=>$patrollers));
    }

    // 添加场控
    public function addPatrollerAction()
    {
        $author   = intval(Context::get("userid"));      // 主播userid
        $relateid = intval($this->getParam("relateid")); // 场控userid
        $liveid   = intval($this->getParam("liveid"));

        $patroller = new Patroller();
        $patroller->addPatroller($author, $relateid, $liveid);

        $this->render();
    }

    // 取消场控
    public function delPatrollerAction()
    {
        $author   = intval(Context::get("userid"));      // 主播userid
        $relateid = intval($this->getParam("relateid")); // 场控userid
        $liveid   = intval($this->getParam("liveid"));

        $patroller = new Patroller();
        $patroller->delPatroller($author, $relateid, $liveid);

        $this->render();
    }

    // 是否是主播场控
    public function isPatrollerAction()
    {
        $author   = intval(Context::get("userid"));      // 登录用户userid
        $relateid = intval($this->getParam("relateid")); // 主播userid
        $liveid   = intval($this->getParam("liveid"));

        $patroller = new Patroller();
        $data = $patroller->isPatroller($author, $relateid, $liveid);

        $this->render(array('bool' => $data));
    }

    // 是否被禁言
    public function isSilenceAction()
    {
        $author   = intval(Context::get("userid"));      // 登录用户userid
        $relateid = intval($this->getParam("relateid")); // 主播userid
        $liveid   = intval($this->getParam("liveid"));

        $chat = new Chat();
        $data = $chat->isSilence($liveid, $relateid);

        $this->render(array('bool' => $data));
    }

    // 给主播投票
    public function voteAction()
    {
        $author   = intval(Context::get("userid"));      // 主播userid
        $relateid = intval($this->getParam("relateid")); // 主播userid
        $liveid   = intval($this->getParam("liveid")); // 直播id
        $activity_id   = trim($this->getParam("activity_id")); // 直播id

        $chat = new Chat();
        $chat->voteMessage($author, $relateid, $liveid, $activity_id);

        $this->render();
    }

    // 是否被踢出房间
    public function isKickedAction()
    {
        $author   = intval(Context::get("userid"));      // 主播userid
        $liveid   = intval($this->getParam("liveid")); // 直播id

        $chat = new Chat();
        $bool = $chat->isKicked($liveid, $author);

        $this->render(array('bool' => $bool));
    }
    
    // 加入聊天室
    public function joinRoomAction()
    {
        $author   = intval(Context::get("userid"));      // 用户id 踩人id
        $liveid   = intval($this->getParam("liveid")); // 直播id
        $userid   = intval($this->getParam("op_userid")); //在线用户 被踩人id
        
        $userinfo = User::getUserInfo($author);
        $op_userinfo = User::getUserInfo($userid);
        
        $live = new Live();
        $liveinfo = $live->getLiveInfo($liveid);
        
        if ($liveinfo['uid'] == $userid) {
            $this->render(array('bool' => true));
        }
        
        $privacy = Privacy::getPrivacyInfoByLiveInfo($liveinfo['privacy']);
        $cache   = Cache::getInstance("REDIS_CONF_CACHE");
        
        $join_redis_key = "join_room_from_online_". $liveid . "_" . $author;
        if ($cache->INCR($join_redis_key) > 1) {
            return true;
        }
        
        $cache->expire($join_redis_key, 5);
        $user_guard = UserGuard::getUserGuardRedis($author, $liveinfo['uid']);
        $bool = Messenger::sendLiveJoinMessageFromOther($liveid, $userinfo, $userinfo['nickname'] . '踩着' . $op_userinfo['nickname'] . '的小尾巴过来啦', Messenger::MESSAGE_TYPE_ONLINE_JOIN_ROOM, intval($user_guard), $privacy, $op_userinfo);
        //Logger::log("pk_match_log", "joinRoom", array("author" => $author, 'liveid' => $liveid, 'op_userid' => $userid, 'zhubo' => $liveinfo['uid']));
        
        $this->render(array('bool' => $bool));
    }
}
?>
