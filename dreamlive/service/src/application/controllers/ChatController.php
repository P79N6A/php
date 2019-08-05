<?php
class ChatController extends BaseController
{
    public function sendAction()
    {
        $liveid      = $this->getParam("liveid")  ? intval($this->getParam('liveid')) : 0;
        $content     = $this->getParam("content");
        $relateids     = $this->getParam("relateids");
        $deviceid     = trim(strip_tags($this->getParam("deviceid")));
        $appversion = trim(Context::get("version"));
        $platform    = trim(Context::get("platform"));
        $sender      = Context::get("userid");
        $user_ip = Util::getIP();


        include_once 'process_client/ProcessClient.php';
        $multi_array = json_decode($content, true);

        $chat = new Chat();


        $multi_content = '';

        $userinfo = User::getUserInfo($sender);

        //1111世界聊天是id
        if (in_array($liveid, array(1111))) {
            if ($userinfo['level'] < 10) {
                $this->render();
            }
        }

        if (!empty($multi_array)) {
            $multi_content = $content;
            if ($multi_array['contentType'] != 0) {//1图片
                //                 //聊天异步
                //                 $info = array(
                //                         "liveid"     => $liveid,
                //                         "content"     => $content,
                //                         "ip"        => $user_ip,
                //                         "deviceid"    => $deviceid,
                //                         "sender"    => $sender,
                //                         "is_multi"  => 1,
                //                         "replace_keyword" => '',
                //                         "relateids"    => $relateids
                //                 );
                //                 ProcessClient::getInstance("dream")->addTask("chat_send_async", $info);

                $info = array(
                "liveid"     => $liveid,
                "content"     => $content,
                "uid"        => $sender,
                "ip"        => $user_ip,
                "deviceid"    => $deviceid,
                "chat_time" => date("Y-m-d H:i:s"),
                );
                ProcessClient::getInstance("dream")->addTask("chat_create_control", $info);

                //$chat->addWorldChat($sender, $content);
                $this->render();
            } else {//文本
                $content = $multi_array['content'];
            }
        }

        //兼容android一个两次decode的bug2018-01-21
        if ($platform == "android" && $appversion == "3.0.0") {
            $content = urldecode($content);
        }



        Interceptor::ensureNotFalse($liveid > 0, ERROR_PARAM_INVALID_FORMAT, "liveid");
        $anti_spam_forbidden = new AntiSpamForbidden();


        //检查是否设置封禁ip
        if ($anti_spam_forbidden->checkForbiddenIp($user_ip)) {
            $this->render();
        }


        if ($userinfo['level'] < 5 || in_array($sender, array(10899916))) {
            include_once 'message_client/RongCloudClient.php';
            $rongcloud_client = new RongCloudClient();
            $result = $rongcloud_client->queryChatroomUserExist($liveid, $sender);
            if(!($result['isInChrm'])) {
                $this->render();
            }
        }

        if (!in_array($liveid, array(1111))) {
            $live = new Live();
            $live_info = $live->getLiveInfo($liveid);
            Interceptor::ensureNotEmpty($live_info, ERROR_BIZ_LIVE_NOT_EXIST);
            Interceptor::ensureNotFalse($live_info["status"] == Live::ACTIVING, ERROR_BIZ_LIVE_NOT_ACTIVE);
        }


        //验证是否设置了禁用聊天，只有白名单可以聊天
        if (!ChatWhiteList::checkIsCanChat($liveid, $sender)) {
            $this->render();
        }

        //验证反垃圾词汇
        if($anti_spam_forbidden->checkForbidden($content)) {
            $this->render();
        }

        $send_gift_total          = (int) Counter::get(Counter::COUNTER_TYPE_PAYMENT_SEND_GIFT, $sender);
        $receive_gift_total       = (int) Counter::get(Counter::COUNTER_TYPE_PAYMENT_RECEIVE_GIFT, $sender);

        if ($send_gift_total <= 50 && $receive_gift_total <= 10 ) {//送礼大于等于100钻,或收入大于10钻
            //验证封禁词汇
            if (ForbiddenKeyword::forbiddenCheck($content)) {
                ProcessClient::getInstance("dream")->addTask(
                    "forbidden_control", array(
                    "seconds"     => 3600*24*100,
                    "uid"         => $sender,
                    "reason"     => "发送包含封禁词汇{$content}",
                    "sender"    => 10000003
                    )
                );

                ProcessClient::getInstance("dream")->addTask(
                    "chat_create_control", array(
                    "liveid"     => $liveid,
                    "content"     => $content . "[包含封禁词汇]",
                    "uid"        => $sender,
                    "ip"        => $user_ip,
                    "deviceid"    => $deviceid,
                    "chat_time" => date("Y-m-d H:i:s"),
                    )
                );
                $dao = new DAOChat($liveid);
                $dao->addMessage($liveid, $sender, 5, $content, '包含封禁词汇');
                $this->render();
            }
        }

        // 是否被播主拉黑
        $author = $live_info["author"]["uid"];


        $cache = new Cache("REDIS_CONF_CACHE");
        $version = str_replace('.', '', Context::get("version"));
        $version = intval($version);
        if ($version >= 2910 || $version >= 300) {
            //---------------------公聊字符数限制------------------//
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
                    $cache->expire($frequency_redis_key, $common_chat_frequency);
                    Interceptor::ensureNotFalse(false, ERROR_CHAT_MESSAGE_OVER_FREQUENCY, "$common_chat_frequency");
                }
                $cache->expire($frequency_redis_key, $common_chat_frequency);
            }
            $common_chat_max_num = $common_chat_max_num * 2;
            Interceptor::ensureNotFalse(! ($chat_content_num > $common_chat_max_num), ERROR_CHAT_MESSAGE_NUM_OVERRUN, "");//num:{$chat_content_num}-vip:{$userinfo['vip']} - num:$common_chat_max_num - guard:$user_guard - level:{$userinfo['level']}
            //--------------------公聊字符数限制--------------------//
        }
        //检验是否包含屏蔽词
        $plus['liveid'] = $liveid;
        $plus['sender'] = $sender;

        include_once 'antispam_client/policy/Policy.php';
        if (Policy::isDeny($content)) {
            $dao = new DAOChat($liveid);
            $dao->addMessage($liveid, $sender, 3, $content, ' ');
            $this->render();
        }

        //Interceptor::ensureNotFalse(FilterKeyword::check_shield($content, $plus), ERROR_KEYWORD_SHIELD, 'content');
        if (!FilterKeyword::check_shield($content, $plus)) {
            $this->render();
        }

        //替换内容
        $replace_keyword = array();
        $content = FilterKeyword::content_replace($content, $replace_keyword);
        $replace_keyword = !empty($replace_keyword) ? implode(',', $replace_keyword) : '';

        $type = !empty($replace_keyword) ? FilterKeyword::REPLACE : FilterKeyword::NORMAL;

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
        "content"     => !empty($multi_content) ? $multi_content : $content,
        "ip"        => $user_ip,
        "deviceid"    => $deviceid,
        "sender"    => $sender,
        "is_multi"  => !empty($multi_content) ? 1 : 0,
        "watches"     => !empty($live_info['watches']) ? $live_info['watches']: 0,
        "replace_keyword" => $replace_keyword,
        "relateids"    => $relateids
        );
        ProcessClient::getInstance("dream")->addTask("chat_send_async", $info);

        Counter::increase(Counter::COUNTER_TYPE_LIVE_CHATS, $liveid, 1);

        //--------------机器人--------------
        if ($sender != $live_info['uid']) {
            ProcessClient::getInstance("dream")->addTask("robot_receive", array("liveid" => $liveid,"content" => $content,"source" => 3));
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
        }

        if ($send_gift_total <= 50 && $receive_gift_total <= 10 ) {//送礼大于等于100钻,或收入大于10钻

            $config = new Config();
            $results = $config->getConfig("china", "public_chatp", "server", '1000000000000');
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
                    $dao = new DAOChat($liveid);
                    $dao->addMessage($liveid, $sender, 6, $content, '1分钟发送5次重复内容');

                    ProcessClient::getInstance("dream")->addTask(
                        "forbidden_control", array(
                        "seconds"     => 3600*24*10,
                        "uid"         => $sender,
                        "reason"     => '1分钟发送5次重复内容6',
                        "sender"    => 10000001
                        )
                    );
                    $dao_patroller_log = new DAOPatrollerLog();

                    $dao_patroller_log->addPatrollerLog('100', $sender, 'N', 'SILENCE', $liveid);


                }

                $cache->expire($slience_redis_key, 60);
            }
        }

        if (in_array($liveid, [1111])) {
            $chat->addWorldChat($sender, !empty($multi_content) ? $multi_content : $content);
        }

        RobotChat::recordLastChat($liveid);

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
        $bool = Messenger::sendLiveJoinMessageFromOther($liveid, $userinfo, $userinfo['nickname'] . '踩着' . $op_userinfo['nickname'] . "的小尾巴过来啦\xF0\x9F\x8E\x89\xF0\x9F\x8E\x89\xF0\x9F\x8E\x89", Messenger::MESSAGE_TYPE_ONLINE_JOIN_ROOM, intval($user_guard), $privacy, $op_userinfo);
        //Logger::log("pk_match_log", "joinRoom", array("author" => $author, 'liveid' => $liveid, 'op_userid' => $userid, 'zhubo' => $liveinfo['uid']));
        $this->render(array('bool' => $bool));
    }

    public function chatHistoryAction()
    {
        $liveid   = intval($this->getParam("liveid"));    // 直播id
        $author   = intval(Context::get("userid"));        // 用户id

        if (!in_array($liveid, array(1111))) {
            $this->render(array('list' => []));
        }

        $chat = new Chat();
        $data = $chat->getWorldChatList();

        $this->render(array('list' => $data));
    }


    public function addQuickWordAction()
    {
        $author   = intval(Context::get("userid"));        // 主播id
        $json_content     = trim(strip_tags($this->getParam("content")));//便捷语

        $json_array = json_decode($json_content, true);

        foreach ($json_array as $json) {
            $content = $json['content'];
            $mb_len = mb_strlen($content, 'utf8');

            Interceptor::ensureNotFalse($author > 0, ERROR_PARAM_NOT_SMALL_ZERO, "userid");
            Interceptor::ensureNotFalse(! ($mb_len > 15), ERROR_CHAT_MESSAGE_QUICK_WORD_MAX_WORD, "");
            Interceptor::ensureNotFalse(! (QuickWord::getTotal($author) >= 3), ERROR_CHAT_MESSAGE_QUICK_MAX_NUM, "");

        }

        foreach ($json_array as $content) {
            $quick_word = new QuickWord();
            $quick_word->addWord($author, $content);
        }
        $this->render();
    }

    public function getQuickListAction()
    {
        $author   = intval(Context::get("userid"));        // 主播id

        $quick_word = new QuickWord();
        $data = $quick_word->getList($author);
        $platform=Context::get('platform');
        if ($platform=='android') {
            if (empty($data)) {
                $data=array(array(
                "id"=>5000000,
                "content"=>"赞",
                "sort"=>100,
                "foreigncontent"=>"赞",
                "addtime"=>"2017-06-17 10:59:21",
                "modtime"=>"2017-08-13 22:14:11",
                "traditional"=>"赞"
                ));
            }
        }

        //         $config = new Config();
        //         $results = $config->getConfig("china", "public_chat", "server", '1000000000000');
        //         $public_chat_array = json_decode($results['value'], true);

        //         foreach ($public_chat_array AS &$val) {
        //             $val['is_can_edit'] = 0;
        //         }
        //         $data = array_merge($public_chat_array, $data);

        $this->render($data);
    }

    public function updateQuickWordAction()
    {
        $id          = $this->getParam("id")  ? intval($this->getParam('id')) : 0;
        $author   = intval(Context::get("userid"));        // 主播id

        $status     = trim($this->getParam("status"));//审核状态
        $adminid      = $this->getParam("adminid")  ? intval($this->getParam('adminid')) : 0;
        $dao_quick_word = new DAOQuickWord();

        if ($status == 'UNPASS') {
            $info = $dao_quick_word->getInfo($id);
            $userid = $info['uid'];
            $content = "亲爱的主播，您的公聊便捷语审核未通过，请重新提交";
            Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $userid, $content, $content, 0);

        }


        $dao_quick_word->adminupdate($id, $adminid, $status);


        $this->render();
    }

    public function modifyQuickWordAction()
    {
        $id          = $this->getParam("id")  ? intval($this->getParam('id')) : 0;
        $author   = intval(Context::get("userid"));        // 主播id
        $json_content     = trim(strip_tags($this->getParam("content")));//便捷语

        $json_array = json_decode($json_content, true);
        foreach ($json_array as $json) {
            $content = $json['content'];
            $id = $json['id'];
            $mb_len = mb_strlen($content, 'utf8');

            Interceptor::ensureNotFalse($author > 0, ERROR_PARAM_NOT_SMALL_ZERO, "userid");
            Interceptor::ensureNotFalse(! ($mb_len > 15), ERROR_CHAT_MESSAGE_QUICK_WORD_MAX_WORD, "");
            $dao_quick_word = new DAOQuickWord();

            $info = $dao_quick_word->getInfo($id);
            Interceptor::ensureNotFalse($author == $info['uid'], ERROR_CHAT_MESSAGE_QUICK_NOT_SELF, "id");

        }


        $dao_quick_word->modify($id, $content);

        $this->render();
    }
}
?>
