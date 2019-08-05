<?php
class Messenger
{
    const PRODUCT = "dream";
    const EXPIRE  = 180; //有效期 3分钟
    const KEFUID  = 30000000; //客服id

    const MESSAGE_TYPE_LIVE_START           = 200; //直播开始
    const MESSAGE_TYPE_LIVE_STOP            = 201; //直播结束
    const MESSAGE_TYPE_LIVE_PRAISE          = 202; //赞
    const MESSAGE_TYPE_LIVE_INTERRUPT       = 204; //后台强制下播
    const MESSAGE_TYPE_LIVE_RESTART         = 212; //直播重启
    const MESSAGE_TYPE_LIVE_PAUSE           = 210; //直播切出
    const MESSAGE_TYPE_LIVE_RESUME          = 211; //直播恢复
    const MESSAGE_TYPE_BROADCAST_CHAT        = 205; //
    const MESSAGE_TYPE_BROADCAST_STOP        = 206; //
    const MESSAGE_TYPE_BROADCAST_FOLLOW     = 207;
    const MESSAGE_TYPE_FEEDBACK                = 208;
    const MESSAGE_TYPE_GIFT                    = 209;//发送礼物消息
    const MESSAGE_TYPE_PROP                    = 213;//发送弹幕
    const MESSAGE_TYPE_LIVE_CHANGE_STREAM    =214;//更换拉流地址通知单个用户
    const MESSAGE_TYPE_RESTART_LIVE            = 215;//通知主播重新开播
    const MESSAGE_TYPE_FOLLOW_BROADCAST        = 216;//观众关注主播
    const MESSAGE_TYPE_FOLLOW_AUDIENCE        = 217;//主播关注观众
    const MESSAGE_TYPE_LIVE_SHARE           = 218; //分享直播
    const MESSAGE_TYPE_GIFT_PUBLISH            = 219;//发送礼物私信
    const MESSAGE_TYPE_LIVE_VOTE            = 220;//投票
    const MESSAGE_TYPE_ADD_PATROLLER        = 221;//添加场控
    const MESSAGE_TYPE_DEL_PATROLLER        = 222;//删除场控
    const MESSAGE_TYPE_CHANGE_CLOUD_IP        = 223;//更换直播云ip
    const MESSAGE_TYPE_TEST_SPEED            = 224;//测速消息
    const MESSAGE_TYPE_LIVE_SHARE_NEW       = 225; //分享截图录屏消息
    const MESSAGE_TYPE_LIVE_RED               = 226; //分享红包
    const MESSAGE_TYPE_LIVE_WARNNING           = 227; //主播警告
    const MESSAGE_TYPE_LIVE_PRIVACY           = 228; //私密直播消息
    const MESSAGE_TYPE_TEST_SPEED_NEW        = 229; //测速消息
    const MESSAGE_TYPE_FOLLOWER_NOTICE        = 230; //粉丝通知
    const MESSAGE_TYPE_TEST_SPEED_NODE      = 231; //测速消息
    const MESSAGE_TYPE_CHANGE_SN            = 232; //切换流地址
    const MESSAGE_RECEIVE_MONEY_LAST        = 233; //还差多少票到达下个级别
    const MESSAGE_RECEIVE_MONEY_COMPLETE    = 234; //完成了某个级别
    const MESSAGE_MATCH_NOTICE_MEMBER_MSG    = 235; //通知队友自己的分数pk需求
    const MESSAGE_MATCH_NOTICE_FINISHED        = 236; //通知pk的直播间结束的消息pk需求
    const MESSAGE_TYPE_USER_APPLY_MATCH     = 237;//向受邀者发起pk请求
    const MESSAGE_TYPE_USER_ACCEPT_MATCH    = 238;//通知相关用户跟新信息
    const MESSAGE_TYPE_PRIVATE_GIFT          = 239;//私信礼物

    const MESSAGE_TYPE_USER_FOLLOW              = 100; //有人关注
    const MESSAGE_TYPE_USER_LEVEL               = 101; //等级变化
    const MESSAGE_TYPE_LIVE_USER_FORBIDDEN       = 102; //用户被封禁通知-群消息方式
    const MESSAGE_TYPE_USER_FORBIDDEN_PRIVATE   = 103; //用户被封禁通知-私信方式
    const MESSAGE_TYPE_LIVE_STOP_MESSAGE           = 104; //主播被下播通知-私信方式

    const MESSAGE_TYPE_CHATROOM_JOIN        = 300; //进入群聊
    const MESSAGE_TYPE_CHATROOM_SEND        = 301; //发送聊天
    const MESSAGE_TYPE_CHATROOM_KICK        = 302; //聊天室踢人
    const MESSAGE_TYPE_CHATROOM_SILENCE     = 303; //被禁言
    const MESSAGE_TYPE_CHATROOM_UNSILENCE     = 304; //被解禁
    const MESSAGE_TYPE_CHATROOM_QUIT         = 305; //退出群聊
    const MESSAGE_TYPE_USER_BLOCKED            = 306; //拉黑
    const MESSAGE_TYPE_USER_UNBLOCKED         = 307; //取消拉黑
    const MESSAGE_TYPE_BUCKTE_FIRST_CHATROOM_SEND        = 308; //热一弹窗提醒
    const MESSAGE_TYPE_BUCKTE_RISE_CHATROOM_SEND        = 309; //主播上升到10，20，50消息提示
    const MESSAGE_TYPE_BUY_PRIVACY_ROOM     = 310; //购买私密直播发一个消息
    const MESSAGE_TYPE_HOTLIVE_SORT_ROOM    = 311; //直播间热门排序更新
    const MESSAGE_TYPE_ONLINE_JOIN_ROOM     = 312; //在线进入直播间消息


    const MESSAGE_TYPE_MESSAGE_TEXT         = 400; //私信
    const MESSAGE_TYPE_MESSAGE_RECALL       = 401; //私信召回
    const MESSAGE_TYPE_MESSAGE_KEFU         = 402; //客服私信

    const MESSAGE_TYPE_BROADCAST_ALL        = 500; //给送有人广播
    const MESSAGE_TYPE_BROADCAST_SOME        = 501; //给一部分人广播
    const MESSAGE_TYPE_BROADCAST_TASK        = 502; //发送任务消息
    const MESSAGE_TYPE_COLLECT_LOG            = 503; //收集日志

    const MESSAGE_TYPE_BROADCAST_GUARD              = 601; //购买守护.聊天室
    const MESSAGE_TYPE_BROADCAST_GUARD_SEND_ALL      = 602; //购买守护.聊天室
    const MESSAGE_TYPE_BROADCAST_HORN_ALL              = 603; //世界喇叭

    const MESSAGE_TYPE_SPECIAL_CHATROOM_QUIT        = 700;//退出房间的消息
    
    const MESSAGE_TYPE_LINK_APPLY_TO_ANCHOR       = 901; //用户连麦申请，发消息通知主播
    const MESSAGE_TYPE_LINK_CANCEL_TO_ANCHOR      = 902; //用户取消连麦申请，发消息通知主播
    const MESSAGE_TYPE_LINK_REFUSE_TO_USER        = 903; //主播拒绝用户连麦申请，发消息通知用户
    const MESSAGE_TYPE_LINK_ACCEPT_TO_USER        = 904; //主播接受连麦，发消息给用户接入连麦
    const MESSAGE_TYPE_LINK_PERMIT_TO_LIVE        = 905; //主播关闭、开启连麦许可，通知直播间用户
    const MESSAGE_TYPE_LINK_DISCONNECTED_TO_LIVE  = 906; //挂断
    const MESSAGE_TYPE_LINK_ACCEPT_TO_LIVE        = 907; //主播接受连麦，发直播间消息

    const MESSAGE_SYSTERM_BRODCAST_USER            = 1000;//发送系统消息指定的用户ID
    const MESSAGE_TASK_BRODCAST_USER            = 999;//发送任务消息指定的用户ID
    const MESSAGE_SYSTERM_BRODCAST_COLLECT        = 1001;//收集手机日志

    const MESSAGE_SYSTEME_BRODCAST_TRACK             = 1002;//全站广播默认跑到消息
    const MESSAGE_SYSTEME_BRODCAST_TRACK_GIFT         = 1003;//全站广播之大礼物
    const MESSAGE_SYSTEME_BRODCAST_TRACK_GUARD         = 1004;//全站广播之守护
    const MESSAGE_SYSTEME_BRODCAST_TRACK_LOTTO         = 1005;//全站广播之抽大奖
    const MESSAGE_SYSTEME_BRODCAST_TRACK_DEFAULT     = 1006;//全站广播默认



    public static function isDisturbed($userid)
    {
        $dao_profile = new DAOProfile($userid);
        $result['option_dnd'] = $dao_profile->getProfile("option_dnd");
        if (empty($result) || empty($result["option_dnd"]) || "Y" != $result["option_dnd"]) {
            return false;
        }

        $result['timezone'] = $dao_profile->getProfile("timezone");
        $timezone = empty($result["timezone"]) ? 8 : $result["timezone"];

        $time = (is_numeric($timezone) && $timezone != 8) ? ($timezone > 8 ? strtotime("+ " . ($timezone - 8) . " hours") : strtotime("- " . ($timezone < 0 ? (abs($timezone) + 8) : (8 - $timezone)) . " hours")) : time();

        $h = date("H", $time);

        return $h > 22 || $h < 8;
    }

    public static function getUserPushId($userid)
    {
        $dao_profile = new DAOProfile($userid);
        $result['pushid'] = $dao_profile->getProfile("pushid");

        return empty($result["pushid"]) ? "" : $result["pushid"];
    }

    public static function sendToUser($receiver, $type, $text, $extends = array(), $apns = false)
    {
        /* {{{ */
        $wrappers = array(
            "userid" => $receiver,
            "type" => $type,
            "text" => $text,
            "time" => time(),
            "expire" => self::EXPIRE,
            "extra" => $extends
        );

        $wrapper = array();
        $traceid = md5(serialize($wrappers));

        $wrappers["traceid"] = $traceid;
        $wrapper['content'] = $wrappers;


        include_once 'message_client/RongCloudClient.php';
        $rongcloud_client = new RongCloudClient();

        $rongcloud_client->sendPrivateMessage($receiver, $extends['userid'], json_encode($wrapper), $text, $type);


        if($apns && ($pushid = self::getUserPushId($receiver)) != "") {
            self::sendToAPNS($pushid, $type, $text, $extends);
        }

        return $traceid;
    }

    public static function sendToGroup($liveid, $type, $sender, $text, $extends = array(), $watches = 0)
    {
        if (empty($liveid)) {
            return false;
        }
        
        $live = new Live();
        $live_info = $live->getLiveInfo($liveid);
        $privacy = Privacy::getPrivacyInfoByLiveInfo($live_info['privacy']);

        if (!empty($privacy) && isset($privacy['privacyid'])) {
            $watches = Counter::get(Counter::COUNTER_TYPE_LIVE_PRIVACY_WATCHES, $privacy['privacyid']);
        } else {
            $watches = Counter::get(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid);
        }

        $userinfo = User::getUserInfo($sender);
        
        $extends['gender'] = $userinfo['gender'];
        $extends['anchor_token']=$userinfo['anchor_token'];
        
        if (in_array($type, array(self::MESSAGE_TYPE_CHATROOM_JOIN,self::MESSAGE_RECEIVE_MONEY_COMPLETE, self::MESSAGE_MATCH_NOTICE_FINISHED))) {
            $sender = 1000;
        }

        $wrappers = array(
            "liveid" => $liveid,
            "type" => $type,
            "text" => $text,
            "watches"=> $watches,
            "time" => time(),
            "expire" => self::EXPIRE,
            "extra" => $extends
        );

        $wrapper = array();

        $traceid = md5(serialize($wrappers));

        $wrappers["traceid"] = $traceid;
        $wrapper['content'] = $wrappers;


        include_once 'message_client/RongCloudClient.php';
        $rongcloud_client = new RongCloudClient();
        if (in_array($type, array(self::MESSAGE_TYPE_GIFT))) {
            $objectName = 'gift';
        } else {
            //$objectName = 'RC:TxtMsg';
            $objectName = 'TxtMsg';
        }
        $objectName = 'RC:TxtMsg';
        $rongcloud_client->sendChatRoomMessage($liveid, $sender, json_encode($wrapper), $objectName);


        $wrapper['addtime'] = date("Y-m-d H:i:s");

        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("push_replay_title", array('liveid' => $liveid, 'sender' => $sender ,'content' => json_encode($wrapper)));
        
        try {
            $cache = new Cache("REDIS_CONF_CACHE");
            $key = "dreamlive_live_user_real_num_".$liveid;
            $result = json_decode($cache->get($key), true);
            $watches = $result['num']?$result['num']:0;
            $info = array(
            "liveid"     => $liveid,
            "nums"         => $watches,
            "addtime"    => date("Y-m-d H:i:s"),
            "type"        => $type,
            "date"        => date("Ymd"),
                    
            );
            ProcessClient::getInstance("dream")->addTask("chat_count_num", $info);
        } catch (Exception $e) {
            Logger::log("rongcloud_gettoken_error", "sendChatRoomMessage", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
        }
        
        return $traceid;
    }

    private static function buildAPNSMessage($type, $text, $extends = array())
    {
        $wrapper = array(
            "aps" => array(
                'badge' => 1,
                'alert' => array(
                    'body' => $text
                ),
                'sound' => 'default'
            ),
            "type" => $type,
            "time" => time(),
            "extends" => $extends
        );

        $wrapper["traceid"] = md5(serialize($wrapper));

        return $wrapper;
    }

    private static function sendToAPNS($pushid, $type, $text, $extends = array())
    {
        $wrapper = self::buildAPNSMessage($type, $text, $extends);

        $traceid = $wrapper["traceid"];

        $message = array(
            "pushid" => $pushid,
            "content" => json_encode($wrapper),
            "traceid" => $traceid,
            "expire" => self::EXPIRE
        );
        include_once 'process_client/ProcessClient.php';
        $traceid = ProcessClient::getInstance(self::PRODUCT)->addTask("apns", $message);

        return $traceid;
    }

    public static function sendPrivateMessage($receiver, $senderid, $sender_name, $sender_avatar, $sender_level, $relation, $text, $match_score = 0, $user_ip = '', $deviceid = '', $is_privacy = 0)
    {
        /* {{{ 私聊*/
        $data = array(
            "userid"=>$senderid,
            "nickname"=>$sender_name,
            "avatar"=>$sender_avatar,
            "level"=>$sender_level,
            "match_score" => $match_score,
            "relation"=>$relation
        );

        $tmp_text = json_decode($text, true);
        if (empty($is_privacy)) {
            $anti_spam_forbidden = new AntiSpamForbidden();
            if($anti_spam_forbidden->checkForbidden($tmp_text['content'])) {
                Logger::log("private_send_log", "no send", array("senderid"=> $senderid, "content" => $tmp_text['content']));
                return true;
            }
        }
        
        Logger::log("private_send_log", "send ok", array("senderid"=> $senderid, "content" => $tmp_text['content']));
        
        $type = $receiver==self::KEFUID ? self::MESSAGE_TYPE_MESSAGE_KEFU : self::MESSAGE_TYPE_MESSAGE_TEXT;

        $traceid = self::sendToUser($receiver, $type, $text, $data);

        include_once 'antispam_client/AntiSpamClient.php';
        $anti_spam = new AntiSpamClient();
        $anti_spam->isDirty('message', $tmp_text['content'], $receiver, $senderid, $traceid, $user_ip, $deviceid);

        if($receiver==self::KEFUID) {
            $message = array(
                'sender'=>$senderid,
                'receiver'=>$receiver,
                'content'=>$text,
            );
            include_once 'process_client/ProcessClient.php';
            $traceid = ProcessClient::getInstance(self::PRODUCT)->addTask("kefu_message_woker", $message);
        }

        return true;
    }

    public static function sendUserFobidden($receiver, $text, $reason, $expire, $exp = 0, $level = 0)
    {
        /* {{{ 被封禁的消息*/

        $data = array(
        "userid" => $receiver,
        "reason" => $reason,
        "expire"    => $expire,
        "exp" => intval($exp),
        "level" => intval($level)
        );

        self::sendToUser($receiver, self::MESSAGE_TYPE_USER_FORBIDDEN_PRIVATE, $text, $data);

        self::sendToAPNS($receiver, self::MESSAGE_TYPE_USER_FORBIDDEN_PRIVATE, $text, $data);

        return true;
    }
    
    public static function sendUserReachNextLevelLast($receiver, $last_diamond, $next_level_name)
    {
        /* {{{还差多少票到达下个级别*/
        
        $data = array(
        "userid" => $receiver,
        "short_ticket"    => $last_diamond,
        "next_level" => $next_level_name,
        );
        
        $text = "距离获得今日{$next_level_name}成就还差{$last_diamond}星票，加了个油！";
        self::sendToUser($receiver, self::MESSAGE_RECEIVE_MONEY_LAST, $text, $data);
        
        self::sendToAPNS($receiver, self::MESSAGE_RECEIVE_MONEY_LAST, $text, $data);
        
        return true;
    }
    
    public static function sendLiveWarning($receiver, $text, $liveid)
    {
        /* {{{ 警告的消息*/
        
        $data = array(
        "userid" => $receiver,
        "liveid" => $liveid
        );
        
        self::sendToUser($receiver, self::MESSAGE_TYPE_LIVE_WARNNING, $text, $data);
        
        self::sendToAPNS($receiver, self::MESSAGE_TYPE_LIVE_WARNNING, $text, $data);
        
        return true;
    }

    public static function sendUserLiveInterrupt($receiver, $liveid, $text, $reason)
    {
        /* {{{ 主播被强制下播消息*/

        $data = array(
        "userid" => $receiver,
        "reason" => $reason,
        "liveid" => (string) $liveid,
        "sender" => self::MESSAGE_SYSTERM_BRODCAST_USER
        );

        self::sendToUser($receiver, self::MESSAGE_TYPE_LIVE_STOP_MESSAGE, $text, $data);

        self::sendToAPNS($receiver, self::MESSAGE_TYPE_LIVE_STOP_MESSAGE, $text, $data);

        return true;
    }
    
    
    public static function sendLiveReachTicketLevel($liveid, $reach_level_name, $userid, $is_guard, $key_level)
    {
        /* {{{ 广播某人帮主播达到某个段位*/
        $userinfo = User::getUserInfo($userid);
        $data = array(
        "liveid" => $liveid,
        "userid" => $userid,
        "nickname" => $userinfo['nickname'],
        "avatar" => $userinfo['avatar'],
        "exp" => intval($userinfo['exp']),
        "level" => intval($userinfo['level']),
        "vip"    => intval($userinfo['vip']),
        "reach_level_name" => $reach_level_name,
        "reach_level"        => $key_level
                
        );
        if ($is_guard) {
            $level = "守护";
        } elseif (!empty($userinfo['vip'])) {
            $level = "vip";
        } else {
            $level = $userinfo['level'];
        }
        
        $text = "感谢{$level} {$userinfo['nickname']} 助力, 主播完成今日「{$reach_level_name}」成就！";
        
        self::sendToGroup($liveid, self::MESSAGE_RECEIVE_MONEY_COMPLETE, $userid, $text, $data);
        return true;
    }
    
    /**
     * 粉丝通知
     *
     * @param  int    $receiver
     * @param  int    $sender
     * @param  string $sender_nickname
     * @param  string $sender_avatar
     * @param  int    $sender_exp
     * @param  int    $sender_level
     * @param  string $text
     * @param  int    $liveid
     * @return boolean
     */
    public static function sendLiveNotice($receiver, $sender, $sender_nickname, $sender_avatar, $sender_exp, $sender_level, $text, $liveid)
    {
        //$text = Util::unicode_decode($text);
        $v = array( "message" => $text, "userid" => $sender);
        
        $json = ['contentType' => 3, "content" => json_encode($v) , "description" => ""];
        
        $data = array(
        "liveid" => $liveid,
        "userid" => $sender,
        "nickname" => $sender_nickname,
        "avatar" => $sender_avatar,
        "relation"=>Follow::relation($receiver, $sender),
        "exp" => intval($sender_exp),
        "level" => intval($sender_level),
        "show_personal_page" => 1,//展示个人主页标识
        );
        
        self::sendToUser($receiver, self::MESSAGE_TYPE_MESSAGE_TEXT, json_encode($json), $data);
        Logger::log("follow_notice_log", "step1", array("ok" => 'done',"receive" => $receiver,"sender"=>$sender,"score" => json_encode($json)));
        
        if (($pushid = self::getUserPushId($receiver)) != "") {
            self::sendToAPNS($pushid, self::MESSAGE_TYPE_MESSAGE_TEXT, $text, $data);
        }
        
        return true;
    }

    public static function sendLiveStart($receiver, $text, $liveid, $userid, $nickname, $avatar, $exp = 0, $level = 0, $medal = array(), $vr = array())
    {
        /* {{{ 直播开始消息*/
        if (self::isDisturbed($receiver)) {
            return false;
        }

        $live = new Live();
        $liveinfo = $live->getLiveInfo($liveid);
        $stream = new Stream();
        $replay = ($liveinfo['record'] == 'Y') ? true : false;
        $flv = $stream->getFLVUrl($liveinfo['sn'], $liveinfo['partner'], $liveinfo['region'], $replay);
        //istudio合作
        if (!empty($liveinfo['pullurl'])) {
            $flv = $liveinfo['pullurl'];
        }
        
        $privacy = Privacy::getPrivacyInfoByLiveInfo($liveinfo['privacy']);
        
        $data = array(
            "liveid" => $liveid,
            "userid" => $userid,
            "nickname" => $nickname,
            "avatar" => $avatar,
            "exp" => intval($exp),
            "level" => intval($level),
            "medal" => $medal,
            "sn"=>$liveinfo['sn'],
            "partner"=>$liveinfo['partner'],
            "flv"=>$flv
        );

        if (!empty($privacy) && isset($privacy['privacyid'])) {
            $data['privacy'] = true;
            $data['privacyid'] = $privacy['privacyid'];
        } else {
            $data['privacy'] = false;
        }

        self::sendToUser($receiver, self::MESSAGE_TYPE_LIVE_START, $text, $data);

        if (($pushid = self::getUserPushId($receiver)) != "") {
            self::sendToAPNS($pushid, self::MESSAGE_TYPE_LIVE_START, $text, $data);
        }

        return true;
    }

    public static function sendLiveStop($liveid, $text, $userid, $nickname, $avatar, $audience, $praise, $type = 0, $reason = "", $exp = 0, $level = 0, $watches = 0, $receive_gifts = 0, $live_privacy_ticket = 0, $privacy_watches = 0)
    {
        /* {{{ 直播结束*/
        self::sendToGroup(
            $liveid, self::MESSAGE_TYPE_LIVE_STOP, $userid, $text, array(
            "liveid" => $liveid,
            "userid" => $userid,
            "nickname" => $nickname,
            "avatar" => $avatar,
            "audience" => $audience,
            "praise" => $praise,
            "type" => $type,
            "live_ticket"=> intval($receive_gifts),
            "endtime"=>date("Y-m-d H:i:s"),
            "watches"=> intval($watches),
            "reason" => $reason,
            "privacy_tickets"=> $live_privacy_ticket,
            "privacy_watches"=> $privacy_watches,
            "exp" => intval($exp),
            "level" => intval($level)
            ), $watches
        );
        return true;
    }

    public static function sendLiveVote($liveid, $text, $userid, $sender, $nickname, $avatar, $exp = 0, $level = 0, $watches = 0, $activity_id = '')
    {
        /* {{{ 投票*/
        self::sendToGroup(
            $liveid, self::MESSAGE_TYPE_LIVE_VOTE, $sender, $text, array(
            "liveid" => $liveid,
            "userid" => $sender,
            "nickname" => $nickname,
            "avatar" => $avatar,
            "sender" => $sender,
            'activity_id'=>$activity_id,
            "endtime"=>date("Y-m-d H:i:s"),
            "watches"=> $watches,
            "exp" => intval($exp),
            "level" => intval($level)
            ), $watches
        );
        return true;
    }

    public static function sendLiveRed($liveid, $text, $sender, $userid, $nickname, $avatar, $packetid, $share, $threshold, $stage, $watches = 0)
    {
        /* {{{ 红包*/
        self::sendToGroup(
            $liveid, self::MESSAGE_TYPE_LIVE_RED, $sender, $text, array(
            "liveid" => $liveid,
            "packetid" => $packetid,
            "share" => $share,
            "threshold" => $threshold,
            "stage" => $stage,
            "userid" => $userid,
            "nickname" => $nickname,
            "avatar" => $avatar,
            ), $watches
        );
        return true;
    }

    public static function sendLiveAddPatroller($liveid, $text, $userid, $sender, $nickname, $avatar, $exp = 0, $level = 0, $watches = 0, $opratorinfo = array(), $user_guard = 0)
    {
        /* {{{ 添加场控*/
        self::sendToGroup(
            $liveid, self::MESSAGE_TYPE_ADD_PATROLLER, $sender, $text, array(
            "liveid" => $liveid,
            "userid" => $sender,
            "relateid"=> $userid,
            "nickname" => $nickname,
            "avatar" => $avatar,
            "sender" => $sender,
            "op_nickname" => $opratorinfo['nickname'],
            "op_userid"    => $opratorinfo['uid'],
            "endtime"=>date("Y-m-d H:i:s"),
            "watches"=> $watches,
            "exp" => intval($exp),
            'isGuard'=>intval($user_guard),
            "level" => intval($level)
            ), $watches
        );
        return true;
    }

    public static function sendLiveDelPatroller($liveid, $text, $userid, $sender, $nickname, $avatar, $exp = 0, $level = 0, $watches = 0, $opratorinfo = array(), $user_guard = 0)
    {
        /* {{{ 删除场控*/
        self::sendToGroup(
            $liveid, self::MESSAGE_TYPE_DEL_PATROLLER, $sender, $text, array(
            "liveid" => $liveid,
            "userid" => $sender,
            "relateid"=> $userid,
            "nickname" => $nickname,
            "avatar" => $avatar,
            "op_nickname" => $opratorinfo['nickname'],
            "op_userid"    => $opratorinfo['uid'],
            "sender" => $sender,
            "endtime"=>date("Y-m-d H:i:s"),
            "watches"=> $watches,
            "exp" => intval($exp),
            'isGuard'=>intval($user_guard),
            "level" => intval($level)
            ), $watches
        );
        return true;
    }

    public static function sendLiveInterrupt($liveid, $text, $reason, $userid, $nickname, $avatar, $exp = 0, $level = 0, $praise = 0, $watches = 0, $receive_gifts = 0, $live_privacy_ticket = 0, $privacy_watches = 0)
    {
        /* {{{ 强制下播*/
        self::sendToGroup(
            $liveid, self::MESSAGE_TYPE_LIVE_INTERRUPT, self::MESSAGE_SYSTERM_BRODCAST_USER, $text, array(
            "liveid" => $liveid,
            "reason" => $reason,
            "userid" => $userid,
            "nickname" => $nickname,
            "avatar" => $avatar,
            "praise" => $praise,
            "live_ticket"=> $receive_gifts,
            "endtime"=>date("Y-m-d H:i:s"),
            "watches"=> $watches,
            "exp" => intval($exp),
            "privacy_tickets"=> $live_privacy_ticket,
            "privacy_watches"=> $privacy_watches,
            "level" => intval($level)
            ), $watches
        );
        return true;
    }

    public static function sendSquareChatroomStop($liveid)
    {
        /* {{{ 广场直播间直播结束*/
        self::sendToGroup(Live::SQUARE_CHATROOM_ID, self::MESSAGE_TYPE_SPECIAL_CHATROOM_QUIT, self::MESSAGE_SYSTERM_BRODCAST_USER, "直播结束", array("liveid" => $liveid), 1);
        return true;
    }

    public static function sendLiveShare($liveid, $text, $userid, $nickname, $avatar, $exp = 0, $level = 0, $user_guard = 0)
    {
        /* {{{ 直播分享*/
        $userinfo = User::getUserInfo($userid);
        self::sendToGroup(
            $liveid, self::MESSAGE_TYPE_LIVE_SHARE, $userid, $text, array(
            "liveid" => $liveid,
            "userid" => $userid,
            "nickname" => $nickname,
            "avatar" => $avatar,
            "exp" => intval($exp),
            "level" => intval($level),
            'isGuard'=>intval($user_guard),
            "vip" => (int)$userinfo['vip'],
                "anchor_token"=>$userinfo['anchor_token'],
            )
        );
        return true;
    }

    public static function sendLiveShareNew($liveid, $text, $userid, $nickname, $avatar, $exp = 0, $level = 0, $user_guard = 0, $type = 'live')
    {
        /* {{{ 录屏截图分享*/
        $userinfo = User::getUserInfo($userid);
        self::sendToGroup(
            $liveid, self::MESSAGE_TYPE_LIVE_SHARE_NEW, $userid, $text, array(
            "liveid" => $liveid,
            "userid" => $userid,
            "nickname" => $nickname,
            "avatar" => $avatar,
                "anchor_token"=>$userinfo['anchor_token'],
            "exp" => intval($exp),
            "level" => intval($level),
            'isGuard'=>intval($user_guard),
            'sharetype' => $type,
            "vip" => (int)$userinfo['vip'],
            )
        );
        return true;
    }

    public static function sendLiveFollowing($liveid, $text, $sender, $userid, $nickname, $avatar, $type = 0, $exp = 0, $level = 0, $user_guard = 0, $vip = 0)
    {
        /* {{{ 发送直播间关注1、主播关注观众2、观众关注主播*/
        self::sendToGroup(
            $liveid, $type, $sender, $text, array(
            "liveid" => $liveid,
            "userid" => $userid,
            "nickname" => $nickname,
            "avatar" => $avatar,
            "exp" => intval($exp),
            "level" => intval($level),
            'isGuard'=>intval($user_guard),
            "vip" => (int)$vip,
            )
        );
        return true;
    }

    public static  function sendLiveJoinMessage($liveid, $sender, $content, $type, $guard=0, $privacy = array())
    {
        $userinfo = User::getUserInfo($sender);
        //$ride=Bag::getRideByUid($sender);
        $extra = array(
        "liveid" => $liveid,
        "userid"=>$sender,
        "nickname"=>$userinfo['nickname'],
        "avatar"=>$userinfo['avatar'],
        "level"=>$userinfo['level'],
                "anchor_token"=>$userinfo['anchor_token'],
        "medal"=>$userinfo['medal'],
        "gender"=>$userinfo['gender'],
        "founder"=>$userinfo['founder'],
                'isGuard'=>intval($guard),
                'rideurl'=>$userinfo['rideurl'],
                'rideid'=>$userinfo['rideid'],
        "vip" => (int)$userinfo['vip'],
        "fontcolor" => (string)Vip::getLevelConfig($userinfo['vip'], Vip::TYPE_FONT_COLOR),
        );

        $watches = Counter::get(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid);
        if (!$watches) {
            if (!empty($privacy)) {
                $watches = 0;
            } else {
                $watches = 1;
            }
        } else {
            if (!empty($privacy)) {
                $watches = Counter::get(Counter::COUNTER_TYPE_LIVE_PRIVACY_WATCHES, $privacy['privacyid']);
            }
        }

        //@file_put_contents('/home/dream/codebase/service/src/www/1.txt',print_r($extra,true)."\r\n",FILE_APPEND);

        self::sendToGroup($liveid, $type, $sender, $userinfo['nickname'] . $content, $extra, $watches);
    }
    
    public static  function sendLiveJoinMessageFromOther($liveid, $userinfo, $content, $type, $guard=0, $privacy = array(), $op_userinfo = array())
    {
        //$ride=Bag::getRideByUid($userinfo['uid']);
        $extra = array(
        "liveid"         => $liveid,
        "userid"        => $userinfo['uid'],
        "nickname"        => $userinfo['nickname'],
        "avatar"        => $userinfo['avatar'],
        "level"            => $userinfo['level'],
        "medal"            => $userinfo['medal'],
        "gender"        => $userinfo['gender'],
                "anchor_token" => $userinfo['anchor_token'],
        "op_nickname"    => $op_userinfo['nickname'],
        "op_avatar"        => $op_userinfo['avatar'],
        "op_level"        => $op_userinfo['level'],
        "op_medal"        => $op_userinfo['medal'],
        "op_gender"        => $op_userinfo['gender'],
        "op_vip"        => $op_userinfo['vip'],
                "op_anchor_token"=>$op_userinfo['anchor_token'],
        "founder"        => $userinfo['founder'],
        'isGuard'        => intval($guard),
        'rideurl'        => $userinfo['rideurl'],
                'rideid'       =>$userinfo['rideid'],
        "vip"             => (int)$userinfo['vip'],
        );
        
        //Logger::log("pk_match_log", "joinRoomsss", array("type" => $type));
        
        $watches = Counter::get(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid);
        if (!$watches) {
            if (!empty($privacy)) {
                $watches = 0;
            } else {
                $watches = 1;
            }
        } else {
            if (!empty($privacy)) {
                $watches = Counter::get(Counter::COUNTER_TYPE_LIVE_PRIVACY_WATCHES, $privacy['privacyid']);
            }
        }
        
        self::sendToGroup($liveid, $type, 1000, $content, $extra, $watches);
        
    }

    public static function sendLiveRestart($liveid, $text, $userid, $sn, $partner, $flv)
    {
        /* {{{ 通知观众更换拉流地址*/
        return self::sendToGroup(
            $liveid, self::MESSAGE_TYPE_LIVE_RESTART, $userid, $text, array(
            "sn"=>$sn,
            "partner"=>$partner,
            "flv"=>$flv)
        );
    }

    public static function sendChangeLiveCloudIp($receiver, $ip)
    {
        /* {{{ 通知更换直播云ip*/
        $data = array(
        "ip"=>$ip,
        );
        self::sendToUser($receiver, self::MESSAGE_TYPE_CHANGE_CLOUD_IP, '更换直播云ip', $data);
    }

    public static function sendUserRestartLive($receiver, $senderid, $sender_name, $sender_avatar, $sender_level, $relation, $text, $liveid)
    {
        /* {{{ 通知主播重新开播*/
        $stream = new Stream();
        list($sn, $partner) = $stream->getStream($receiver);

        $rtmp = $stream->getRTMPUrl($sn, $partner);

        $data = array(
        "userid"    =>$senderid,
        "nickname"    =>$sender_name,
        "avatar"    =>$sender_avatar,
        "level"        =>$sender_level,
        "relation"    =>$relation,
        'sn'        => $sn,
        'partner'    => $partner,
        'rtmp'        => $rtmp,
        'liveid'    => $liveid
        );

        self::sendToUser($receiver, self::MESSAGE_TYPE_RESTART_LIVE, $text, $data);
    }

    public static function sendUserChangeLiveStream($receiver, $text, $sn, $partner, $flv)
    {
        /* {{{ 通知观众更换拉流地址*/
        $data = array(
        "sn"=>$sn,
            "partner"=>$partner,
            "flv"=>$flv
        );

        self::sendToUser($receiver, self::MESSAGE_TYPE_LIVE_CHANGE_STREAM, $text, $data);
    }
    
    public static function sendLiveMatchMemberScore($liveid, $sender, $matchid, $score)
    {
        /* {{{ 通知比赛对手自己的分数 */
        $userinfo = User::getUserInfo($sender);
        $data = array(
        "userid"    =>    $sender,
        "matchid"    =>    $matchid,
        "score"        =>    $score,
        "level"        =>  $userinfo['level'],
        "gender"    =>  $userinfo['gender'],
        "medal"        =>  $userinfo['medal'],
        "vip"         => (int)$userinfo['vip'],
        "nickname"     => $userinfo['nickname'],
        "avatar"    => $userinfo['avatar'],    
            
        );
        
        self::sendToGroup($liveid, self::MESSAGE_MATCH_NOTICE_MEMBER_MSG, $sender, 'pk最新分数', $data);
        
        return true;
    }
    
    public static function sendLiveMatchFinished($liveid, $matchid, $inviter, $invitee, $inviter_score, $invitee_score, $winner, $messgae = "")
    {
        /* {{{ 通知比赛结束消息 */
        $userinfo1 = User::getUserInfo($inviter);
        $userinfo2 = User::getUserInfo($invitee);
        $data = array(
        "userid"            =>    $sender,
        "matchid"            =>    $matchid,
        "winner"            =>  $winner,
        "content"            =>  $messgae,
        "inviter"            =>  ['inviter' => $inviter, 'score' => $inviter_score, 'nickname' => $userinfo1['nickname'], 'avatar' => $userinfo1['avatar']],
        "invitee"            =>    ['invitee' => $inviter, 'score' => $invitee_score, 'nickname' => $userinfo2['nickname'], 'avatar' => $userinfo2['avatar']],
        );
        
        self::sendToGroup($liveid, self::MESSAGE_MATCH_NOTICE_FINISHED, 1000, 'pk结束', $data);
        
        return true;
    }

    public static function sendUserTestSpeed($receiver,$extra = array())
    {
        /* {{{ 发送测试网宿速度消息*/
        $extra['userid'] = self::MESSAGE_SYSTERM_BRODCAST_USER;
        self::sendToUser($receiver, self::MESSAGE_TYPE_TEST_SPEED, '测速', $extra);
    }
    
    public static function sendUserTestSpeedNews($receiver,$speed = array())
    {
        /* {{{ 发送测试网宿速度消息*/
        $extra = array(
            'userid'=> self::MESSAGE_SYSTERM_BRODCAST_USER,
            'speed' => $speed
        );
        self::sendToUser($receiver, self::MESSAGE_TYPE_TEST_SPEED_NEW, '测速', $extra);
    }
    
    public static function sendUserStreamSpeedNodes($receiver,$speed,$stream,$sn,$liveid)
    {
        /* {{{ 发送测试网宿速度消息*/
        $extra = array(
            'liveid' => $liveid,
            'sn'     => $sn,
            'userid' => self::MESSAGE_SYSTERM_BRODCAST_USER,
            'speed'  => $speed,
            'stream' => $stream,
        );file_put_contents('/tmp/streamSpeedNodes'.date('Y-m').'.log', print_r($extra, true)."\n", FILE_APPEND);
        self::sendToUser($receiver, self::MESSAGE_TYPE_TEST_SPEED_NODE, '测速', $extra);
    }

    public static function sendLivePause($liveid, $text, $userid, $nickname, $avatar, $audience, $praise, $type = 0, $reason = "", $exp = 0, $level = 0)
    {
        /* {{{ 直播结束*/
        self::sendToGroup(
            $liveid, self::MESSAGE_TYPE_LIVE_PAUSE, $userid, $text, array(
            "liveid" => $liveid,
            "userid" => $userid,
            "nickname" => $nickname,
            "avatar" => $avatar,
            "audience" => $audience,
            "praise" => $praise,
            "type" => $type,
            "reason" => $reason,
            "exp" => intval($exp),
            "level" => intval($level)
            )
        );
        return true;
    }

    public static function sendLiveResume($liveid, $text, $userid, $nickname, $avatar, $audience, $praise, $type = 0, $reason = "", $exp = 0, $level = 0)
    {
        /* {{{ 直播恢复*/
        self::sendToGroup(
            $liveid, self::MESSAGE_TYPE_LIVE_RESUME, $userid, $text, array(
            "liveid" => $liveid,
            "userid" => $userid,
            "nickname" => $nickname,
            "avatar" => $avatar,
            "audience" => $audience,
            "praise" => $praise,
            "type" => $type,
            "reason" => $reason,
            "exp" => intval($exp),
            "level" => intval($level)
            )
        );
        return true;
    }

    public static function sendForbiden($liveid, $sender, $text, $userid, $nickname, $avatar, $reason, $expire,  $level = 0)
    {
        /* {{{ 封禁*/
        return self::sendToGroup(
            $liveid, self::MESSAGE_TYPE_LIVE_USER_FORBIDDEN, $sender, $text, array(
            "liveid" => $liveid,
            "userid" => $userid,
            "nickname" => $nickname,
            "avatar" => $avatar,
            "reason"    => $reason,
            "level" => intval($level)
            )
        );
    }

    public static function sendUserBlocked($receiver, $text, $sender, $nickname, $avatar, $exp = 0, $level = 0)
    {
        /* {{{ 拉黑*/
        $data = array(
        "userid" => $receiver,
        "nickname" => $nickname,
        "avatar" => $avatar,
        "exp" => intval($exp),
        "level" => intval($level)
        );

        self::sendToUser($receiver, self::MESSAGE_TYPE_USER_BLOCKED, $text, $data);

        self::sendToAPNS($receiver, self::MESSAGE_TYPE_USER_BLOCKED, $text, $data);

        return true;
    }

    public static function sendLiveBlocked($liveid, $text, $sender, $relateid, $nickname, $avatar, $exp = 0, $level = 0)
    {
        /* {{{ 拉黑发群消息*/
        return self::sendToGroup(
            $liveid, self::MESSAGE_TYPE_USER_BLOCKED, $sender, $text, array(
            "liveid" => $liveid,
            "userid" => $sender,
            "relateid" => $relateid,
            "text"    => $text,
            "nickname" => $nickname,
            "avatar" => $avatar,
            "reason"    => $text,
            "level" => intval($level)
            )
        );
    }


    public static function sendCancelUserBlocked($receiver, $text, $sender, $nickname, $avatar, $exp = 0, $level = 0, $liveid = 0)
    {
        /* {{{ 取消拉黑*/
        $data = array(
        "userid" => $receiver,
        "nickname" => $nickname,
        "avatar" => $avatar,
        "exp" => intval($exp),
        "level" => intval($level),
        "liveid" => $liveid
        );

        self::sendToUser($receiver, self::MESSAGE_TYPE_USER_UNBLOCKED, $text, $data);

        self::sendToAPNS($receiver, self::MESSAGE_TYPE_USER_UNBLOCKED, $text, $data);

        return true;
    }

    public static function sendUserLevelChange($liveid, $sender, $text, $userid, $nickname, $avatar, $level = 0, $user_guard = 0)
    {
        /* {{{ 用户等级变化*/
        Logger::log(
            'task_err', '::::', array(
            $liveid, $sender, $text, $userid, $nickname, $avatar, $level
            )
        );
        $userinfo = User::getUserInfo($userid);
        return self::sendToGroup(
            $liveid, self::MESSAGE_TYPE_USER_LEVEL, self::MESSAGE_SYSTERM_BRODCAST_USER, $text, array(
            "liveid" => $liveid,
            "userid" => $userid,
            "nickname" => $nickname,
            'isGuard'=> intval($user_guard),
            "avatar" => $avatar,
            "level" => intval($level),
            "vip" => (int)$userinfo['vip'],
                "anchor_token"=>$userinfo['anchor_token'],
            )
        );
    }

    public static function sendPraise($liveid, $sender, $text, $num, $total, $isfirst, $watches, $nickname, $avatar, $level = 0, $user_guard = 0, $gender = "F", $vip = 0)
    {
        /* {{{ 点赞*/
        $userinfo=User::getUserInfo($sender);
        $data = array(
            "liveid" => $liveid,
            "num" => $num,
            "total" => $total,
            "userid" => strval($sender),
            "nickname" => $nickname,
            "level" => intval($level),
            "anchor_token"=>$userinfo['anchor_token'],
            'isGuard'=> intval($user_guard),
            "watches"=> $watches,
            "gender" => $gender,
            "avatar" => $avatar,
            "isfirst" => $isfirst,
            "vip" => (int)$vip,
        );
        return self::sendToGroup($liveid, self::MESSAGE_TYPE_LIVE_PRAISE, $sender, $text, $data, $watches);
    }

    public static function sendSilence($liveid, $text, $userid, $relateid, $nickname, $avatar, $exp = 0, $level = 0, $isPatroller = 'N', $opratorinfo = array())
    {
        /* {{{ 发送禁言*/
        return self::sendToGroup(
            $liveid, self::MESSAGE_TYPE_CHATROOM_SILENCE, $userid, $text, array(
            "liveid" => $liveid,
            "userid" => $relateid,
            "nickname" => $nickname,
            "avatar" => $avatar,
            "op_nickname" => $opratorinfo['nickname'],
            "op_userid"    => $opratorinfo['uid'],
            "is_patroller"=>$isPatroller,
            "exp" => intval($exp),
            "level" => intval($level)
            )
        );
    }

    public static function sendKick($liveid, $text, $userid, $relateid, $nickname, $avatar, $exp = 0, $level = 0, $isPatroller = 'N', $opratorinfo = array())
    {
        /* {{{ 发送踢出房间*/
        return self::sendToGroup(
            $liveid, self::MESSAGE_TYPE_CHATROOM_KICK, $userid, $text, array(
            "liveid" => $liveid,
            "userid" => $relateid,
            "nickname" => $nickname,
            "op_nickname" => $opratorinfo['nickname'],
            "op_userid"    => $opratorinfo['uid'],
            "avatar" => $avatar,
            "is_patroller"=>$isPatroller,
            "exp" => intval($exp),
            "level" => intval($level)
            )
        );
    }

    public static function sendUnSilence($liveid, $text, $userid, $relateid, $nickname, $avatar, $exp = 0, $level = 0, $isPatroller = 'N',$opratorinfo = array())
    {
        /* {{{ 发送解禁*/
        return self::sendToGroup(
            $liveid, self::MESSAGE_TYPE_CHATROOM_UNSILENCE, $userid, $text, array(
            "liveid" => $liveid,
            "userid" => $relateid,
            "nickname" => $nickname,
            "avatar" => $avatar,
            "op_nickname" => $opratorinfo['nickname'],
            "op_userid"    => $opratorinfo['uid'],
            "is_patroller"=>$isPatroller,
            "exp" => intval($exp),
            "level" => intval($level)
            )
        );
    }

    public static function sendGift($liveid, $text, $userid, $nickname, $avatar, $level, $receiver, $name, $image, $type, $giftid, $num, $doublehit, $giftUniTag, $receive_ticket, $live_ticket, $only_mark,$consume, $user_guard, $vip, $match_score)
    {
        /* {{{ 发送礼物*/
        $userinfo=User::getUserInfo($userid);
        $data = array(
            "liveid" => $liveid,
            "userid" => $userid,
            "nickname" => $nickname,
            "avatar" => $avatar,
            "level" => $level,
            "anchor_token"=>$userinfo['anchor_token'],
            "name" => $name,
            "image" => $image,
            "type" => $type,
            "giftid" => $giftid,
            "num" => $num,
            "doublehit" => $doublehit,
            "giftUniTag" => $giftUniTag,
            "receiveTicket" => $receive_ticket,
            "live_ticket" => $live_ticket,
            "only_mark" => $only_mark,
            'isGuard'=>intval($user_guard),
            "vip" => (int)$vip,
            "match_score" => $match_score,
            "consume"=>$consume,//礼物币种类型
        );
        return self::sendToGroup($liveid, self::MESSAGE_TYPE_GIFT, $userid, $text, $data);
    }

    public static function sendBuyPrivacyRoom($liveid, $text, $userid, $nickname, $avatar, $level, $receiver, $receive_ticket,$watches,$buyers, $match_score)
    {
        /* {{{ 购买私密直播*/
        $live = new Live();
        $live_info = $live->getLiveInfo($liveid);
        $privacy = Privacy::getPrivacyInfoByLiveInfo($live_info['privacy']);
        
        $userinfo = User::getUserInfo($live_info['uid']);
        $data = array(
        "liveid" => $liveid,
        "userid" => $userid,
        "nickname" => $nickname,
        "avatar" => $avatar,
        "level" => $level,
        "receiver" => $receiver,
                "anchor_token"=>$userinfo['anchor_token'],
        "buyer"    => $userid,
        "receiveTicket" => $receive_ticket,
                "vip"  => (int)Vip::getUserVipLevel($userid),
                "isGuard" => (int)UserGuard::getUserGuardRedis($receiver, $userid),
                "watches" => $watches,
                "buyers" =>$buyers,
        "medal"=>$userinfo['medal'],
        "gender"=>$userinfo['gender'],
        "founder"=>$userinfo['founder'],
        "match_score" => $match_score,
        );
        
        return self::sendLivePrivacyToGroup($liveid, self::MESSAGE_TYPE_BUY_PRIVACY_ROOM, 1000, $text, $data, $watches, $buyers);
    }


    public static function sendProp($liveid, $text, $userid, $nickname, $avatar, $level = 0, $medal = array())
    {
        /* {{{ 发送弹幕*/
        
        $live = new Live();
        $liveinfo = $live->getLiveInfo($liveid);
        $user_guard = UserGuard::getUserGuardRedis($userid, $liveinfo['uid']);
        
        $userinfo = User::getUserInfo($userid);
        
        $data = array(
        "liveid" => $liveid,
        "userid" => $userid,
        "nickname" => $nickname,
        "avatar" => $avatar,
        "level" => intval($level),
        "medal"    => $medal,
        'isGuard'=>intval($user_guard),
        "vip" => (int)$userinfo['vip'],
        "fontcolor" => (string)Vip::getLevelConfig($userinfo['vip'], Vip::TYPE_FONT_COLOR),
                "anchor_token"=>$userinfo['anchor_token'],
        );
        
        $anti_spam_forbidden = new AntiSpamForbidden();
        if($anti_spam_forbidden->checkForbidden($text)) {
            return true;
        }
        
        $traceid = self::sendToGroup($liveid, self::MESSAGE_TYPE_PROP, $userid, $text, $data);
        
        include_once 'antispam_client/AntiSpamClient.php';
        $anti_spam = new AntiSpamClient();
        $anti_spam->isDirty('chat', $text, $liveid, $userid, $traceid);
        
        return $traceid;
    }


    public static function sendGuard($liveid, $userid, $title, $option)
    {
        /* {{{ 聊天室发送守护消息 */

        $extends = array(
            'liveid' => $liveid,
            'userid' => $option['receive_uid'],
            'nickname' => $option['receive_nickname'],
            'is_patroller' => $option['is_patroller'],
            'type' => $option['type'],
            'expires' => $option['expires'],
            'op_medal' => $option['medal'],
            'op_avatar' => $option['avatar'],
            'op_nickname' => $option['send_nickname'],
            'op_userid'    => $option['send_uid'],
            'op_level' => intval($option['level']),
            'receiveTicket' => intval($option['receiveTicket']),
            "match_score" => $option['match_score'],
        );

        return self::sendToGroup($liveid, self::MESSAGE_TYPE_BROADCAST_GUARD, $userid, $title, $extends);
    }

    public static function sendGuardAll($content, $option)
    {
        /* {{{ 全站消息发送守护消息 */
        $extends = array(
            'liveid' => $option['liveid'],
            'userid' => $option['receive_uid'],
            'nickname' => $option['receive_nickname'],
            'is_patroller' => $option['is_patroller'],
            'type' => $option['type'],
            'expires' => $option['expires'],
            'op_medal' => $option['medal'],
            'op_avatar' => $option['avatar'],
            'op_nickname' => $option['send_nickname'],
            'op_userid' => $option['send_uid'],
            'op_level' => intval($option['level']),
            'receiveTicket' => intval($option['receiveTicket']),
            "match_score" => $option['match_score'],
        );

        return self::sendChatroomBroadcast(self::MESSAGE_SYSTERM_BRODCAST_USER, self::MESSAGE_TYPE_BROADCAST_GUARD, $content, $extends);
        //return self::sendToGroup($liveid, self::MESSAGE_TYPE_BROADCAST_GUARD, $userid, $title, $extends);
    }




    public static function sendBroadcast($type, $title, $content, $relateid, $extends = array())
    {
        /* {{{ 官方消息，所有注册用户都收到*/

        if (empty($content)) {
            return true;
        }

        $cons = array(
        "content" => $content,
        "contentType" => 0,//0文本,1图片
        "description" => '',
        );

        $wrappers = array(
        "title" => $title,
        "type" => $type,
        "text" => json_encode($cons),
        "relateid" => $relateid,
        "time" => time(),
        "expire" => self::EXPIRE,
        "userid"    => self::MESSAGE_SYSTERM_BRODCAST_USER

        );
        !empty($extends) ? $wrappers['extra'] = $extends : '' ;
        $wrapper = array();
        $wrapper['content'] = $wrappers;
        $traceid = md5(serialize($wrapper));

        $wrapper["traceid"] = $traceid;

        include_once 'message_client/RongCloudClient.php';
        $rongcloud_client = new RongCloudClient();
        $rongcloud_client->sendBroadcast(self::MESSAGE_SYSTERM_BRODCAST_USER, json_encode($wrapper), $content);

        return true;
    }

    public static function sendSystemPublish($type, $toUsers,  $title, $content, $relateid, $extends = array(), $system_type = 0)
    {
        /* {{{ 指定的用户发送官方消息*/
        
        if ($system_type == 3) {
            $c = array('message' => $content, "userid" => $relateid);
            $c_string = json_encode($c);
        } else {
            $c_string = $content;
        }
        $cons = array(
        "content" => $c_string,
        "contentType" => $system_type,//0文本,1图片
        "description" => '',
        );

        $wrappers = array(
        "title" => $title,
        "type" => $type,
        "text" => json_encode($cons),
        "relateid" => $relateid,
        "time" => time(),
        "expire" => self::EXPIRE,
        "userid"    => self::MESSAGE_SYSTERM_BRODCAST_USER
        );

        !empty($extends) ? $wrappers['extra'] = $extends : '' ;

        $wrapper = array();
        $wrapper['content'] = $wrappers;
        $traceid = md5(serialize($wrapper));

        $wrapper["traceid"] = $traceid;

        include_once 'message_client/RongCloudClient.php';
        $rongcloud_client = new RongCloudClient();
        $rongcloud_client->sendSystemPublish(self::MESSAGE_SYSTERM_BRODCAST_USER, explode(',', $toUsers), json_encode($wrapper), $content, $content);

        return true;
    }

    public static function sendTaskSystemMessage($type, $receiver,  $title, $content, $relateid, $extends = array(), $medal = array())
    {
        /* {{{ 发送任务消息*/
        $cons = array(
        "content" => $content,
        "contentType" => 0,//0文本,1图片
        "description" => '',
        );

        if (empty($medal)) {
            $userinfo = User::getUserInfo($receiver);
            $medal = $userinfo['medal'];
        }


        $wrappers = array(
        "title" => $title,
        "type" => $type,
        "text" => json_encode($cons),
        "relateid" => $relateid,
        "time" => time(),
        "expire" => self::EXPIRE,
        "userid"    => self::MESSAGE_TASK_BRODCAST_USER
        );
        $extends['title'] = $title;
        $extends['text']  = $content;
        $extends['relateid'] = $relateid;
        $extends['userid'] = self::MESSAGE_TASK_BRODCAST_USER;
        $wrappers['extra'] = $extends ;

        $wrapper = array();
        $wrapper['content'] = $wrappers;
        $traceid = md5(serialize($wrapper));

        $wrapper["traceid"] = $traceid;
        $wrappers['receiver'] = $receiver;
        Logger::log("task_message", "message", $wrappers);
        include_once 'message_client/RongCloudClient.php';
        $rongcloud_client = new RongCloudClient();
        $rongcloud_client->sendSystemPublish(self::MESSAGE_TASK_BRODCAST_USER, $receiver, json_encode($wrapper));

        return true;
    }

    public static function sendGiftPublish($receiver, $text, $uid, $sender_name, $sender_avatar, $sender_level, $liveid, $gift_name, $gift_image, $gift_type, $giftid, $gift_num, $doublehit, $giftUniTag, $receive_ticket, $live_ticket, $only_mark,$consume, $user_guard = 0, $vip = 0, $match_score = 0)
    {
        /* {{{ 礼物发送私信*/
        $userinfo=User::getUserInfo($uid);
        $data = array(
            "liveid" => $liveid,
            "userid" => $uid,
            "nickname" => $sender_name,
            "avatar" => $sender_avatar,
            "level" => $sender_level,
            'anchor_token'=>$userinfo['anchor_token'],
            "name" => $gift_name,
            "image" => $gift_image,
            "type" => $gift_type,
            "giftid" => $giftid,
            "num" => $gift_num,
            "doublehit" => $doublehit,
            "giftUniTag" => $giftUniTag,
            "receiveTicket" => $receive_ticket,
            "live_ticket" => $live_ticket,
            "only_mark" => $only_mark,
            'isGuard'=>intval($user_guard),
            "vip" => (int)$vip,
            "match_score" => $match_score,
            "relation"=>Follow::relation($receiver, $uid),
            "consume"=>$consume,//礼物币种类型
        );

        self::sendToUser($receiver, self::MESSAGE_TYPE_GIFT_PUBLISH, $text, $data);

        return true;
    }

    public static function sendPrivateGift($receiver, $text, $uid, $sender_name, $sender_avatar, $sender_level, $liveid, $gift_name, $gift_image, $gift_type, $giftid, $gift_num, $doublehit, $giftUniTag, $receive_ticket, $live_ticket, $only_mark,$consume, $user_guard = 0, $vip = 0, $match_score = 0)
    {
        /* {{{ 礼物发送私信*/
        $userinfo=User::getUserInfo($uid);
        $data = array(
            "liveid" => $liveid,
            "userid" => $uid,
            "nickname" => $sender_name,
            "avatar" => $sender_avatar,
            "level" => $sender_level,
            "name" => $gift_name,
            "anchor_token"=>$userinfo['anchor_token'],
            "image" => $gift_image,
            "type" => $gift_type,
            "giftid" => $giftid,
            "num" => $gift_num,
            "doublehit" => $doublehit,
            "giftUniTag" => $giftUniTag,
            "receiveTicket" => $receive_ticket,
            "live_ticket" => $live_ticket,
            "only_mark" => $only_mark,
            'isGuard'=>intval($user_guard),
            "vip" => (int)$vip,
            "match_score" => $match_score,
            "consume"=>$consume,//礼物币种类型
        );

        self::sendToUser($receiver, self::MESSAGE_TYPE_GIFT_PUBLISH, $text, $data);

        return true;
    }

    public static function sendCollectLog($type, $toUsers,  $title, $content, $relateid, $extends = array())
    {
        /* {{{ 指定的用户发送官方消息*/
        $cons = array(
        "content" => $content,
        "contentType" => 0,//0文本,1图片
        "description" => '',
        );
        $pushdata['content'] = array(
        'type'    => self::MESSAGE_TYPE_BROADCAST_SOME,
        'text'    => $content,
        'userid'    => self::MESSAGE_SYSTERM_BRODCAST_COLLECT,
        );
        $wrappers = array(
        "title" => $title,
        "type" => $type,
        "text" => json_encode($cons),
        "relateid" => $relateid,
        "time" => time(),
        "expire" => self::EXPIRE,
        "userid"    => self::MESSAGE_SYSTERM_BRODCAST_COLLECT
        );

        !empty($extends) ? $wrappers['extra'] = $extends : '' ;

        $wrapper = array();
        $wrapper['content'] = $wrappers;
        $traceid = md5(serialize($wrapper));

        $wrapper["traceid"] = $traceid;

        include_once 'message_client/RongCloudClient.php';
        $rongcloud_client = new RongCloudClient();
        $rongcloud_client->sendSystemPublish(self::MESSAGE_SYSTERM_BRODCAST_COLLECT, explode(',', $toUsers), json_encode($wrapper), $content, json_encode($pushdata));

        return true;
    }

    /**
     * 给所有直播间发消息
     *
     * @param  int    $sender
     * @param  int    $type
     * @param  string $content
     * @param  array  $extends
     * @return boolean
     */
    public static function sendChatroomBroadcast($sender, $type, $content, $extends = array())
    {
        $wrappers = array(
        "userid"     => $sender,
        "type"        => $type,
        "text"         => $content,
        "time"         => time(),
        "expire"     => self::EXPIRE,
        "extra"     => $extends
        );

        $wrapper = array();
        $wrapper['content'] = $wrappers;
        $traceid = md5(serialize($wrapper));

        $wrapper["traceid"] = $traceid;

        include_once 'message_client/RongCloudClient.php';
        $rongcloud_client = new RongCloudClient();
        $rongcloud_client->sendChatroomBrodcast($sender, json_encode($wrapper));

        return true;
    }

    public static function sendTrackAll( $extends)
    {
        /* {{{ 全站消息发送跑道消息 */
        return self::sendChatroomBroadcast(Account::COMAPNY_ACCOUNT, self::MESSAGE_SYSTEME_BRODCAST_TRACK, 'track_msg', $extends);
    }
    public static function sendTrackAllGift( $extends)
    {
        /* {{{ 全站消息发送跑道消息 */
        return self::sendChatroomBroadcast(Account::COMAPNY_ACCOUNT, self::MESSAGE_SYSTEME_BRODCAST_TRACK_GIFT, 'track_msg', $extends);
    }
    public static function sendTrackAllGuard( $extends)
    {
        /* {{{ 全站消息发送跑道消息 */
        return self::sendChatroomBroadcast(Account::COMAPNY_ACCOUNT, self::MESSAGE_SYSTEME_BRODCAST_TRACK_GUARD, 'track_msg', $extends);
    }
    public static function sendTrackAllLotto( $extends)
    {
        /* {{{ 全站消息发送跑道消息 */
        return self::sendChatroomBroadcast(Account::COMAPNY_ACCOUNT, self::MESSAGE_SYSTEME_BRODCAST_TRACK_LOTTO, 'track_msg', $extends);
    }
    public static function sendTrackAllDefault( $extends)
    {
        /* {{{ 全站消息发送跑道消息 */
        return self::sendChatroomBroadcast(Account::COMAPNY_ACCOUNT, self::MESSAGE_SYSTEME_BRODCAST_TRACK_DEFAULT, 'track_msg', $extends);
    }

    
    
    public static function sendUserLinkApply($receiver, $userid, $liveid)
    {
        /* {{{ 用户连麦申请，发消息通知主播 */
        $userInfo = User::getUserInfo($userid);
        $extra = array(
            "liveid" => $liveid,
            "userid"=>$userid,
            "nickname"=>$userInfo['nickname'],
            "avatar"=>$userInfo['avatar'],
            "level"=>$userInfo['level'],
            "medal"=>$userInfo['medal'],
            "gender"=>$userInfo['gender'],
            "founder"=>$userInfo['founder'],
            'isGuard'=>(int)UserGuard::getUserGuardRedis($receiver, $userid),
            'rideurl'=>$userInfo['rideurl'],
            'rideid'=>$userInfo['rideid'],
            "vip" => (int)$userInfo['vip'],
            "isLive"=>Live::isUserLive($userid)
        );
        self::sendToUser($receiver, self::MESSAGE_TYPE_LINK_APPLY_TO_ANCHOR, '连麦申请', $extra);
    }

    public static function sendUserLinkCancel($receiver, $userid, $liveid)
    {
        /* {{{ 用户取消连麦申请，发消息通知主播 */
        $userInfo = User::getUserInfo($userid);
        $extra = array(
            "liveid" => $liveid,
            "userid"=>$userid,
            "nickname"=>$userInfo['nickname'],
            "avatar"=>$userInfo['avatar'],
            "level"=>$userInfo['level'],
            "medal"=>$userInfo['medal'],
            "gender"=>$userInfo['gender'],
            "founder"=>$userInfo['founder'],
            'isGuard'=>(int)UserGuard::getUserGuardRedis($receiver, $userid),
            'rideurl'=>$userInfo['rideurl'],
            'rideid'=>$userInfo['rideid'],
            "vip" => (int)$userInfo['vip'],
        );
        self::sendToUser($receiver, self::MESSAGE_TYPE_LINK_CANCEL_TO_ANCHOR, '取消连麦申请', $extra);
    }

    public static function sendUserLinkRefuse($receiver, $userid, $liveid)
    {
        /* {{{ 主播拒绝用户连麦申请，发消息通知用户 */
        $userInfo = User::getUserInfo($userid);
        $extra = array(
            "liveid" => $liveid,
            "userid"=>$userid,
            "nickname"=>$userInfo['nickname'],
            "avatar"=>$userInfo['avatar'],
            "level"=>$userInfo['level'],
            "medal"=>$userInfo['medal'],
            "gender"=>$userInfo['gender'],
            "founder"=>$userInfo['founder'],
            'isGuard'=>(int)UserGuard::getUserGuardRedis($receiver, $userid),
            'rideurl'=>$userInfo['rideurl'],
            'rideid'=>$userInfo['rideid'],
            "vip" => (int)$userInfo['vip'],
        );
        self::sendToUser($receiver, self::MESSAGE_TYPE_LINK_REFUSE_TO_USER, '主播拒绝连麦申请', $extra);
    }
    
    
    public static function sendUserLinkAccept($receiver, $userid, $liveid,$linkid)
    {
        /* {{{ 主播接受连麦，发消息给用户接入连麦 */
        $userInfo = User::getUserInfo($userid);
        $extra = array(
            "liveid" => $liveid,
            "userid"=>$userid,
            "nickname"=>$userInfo['nickname'],
            "avatar"=>$userInfo['avatar'],
            "level"=>$userInfo['level'],
            "medal"=>$userInfo['medal'],
            "gender"=>$userInfo['gender'],
            "founder"=>$userInfo['founder'],
            'isGuard'=>(int)UserGuard::getUserGuardRedis($receiver, $userid),
            'rideurl'=>$userInfo['rideurl'],
            'rideid'=>$userInfo['rideid'],
            "vip" => (int)$userInfo['vip'],
            "linkid"=>$linkid
        );
        self::sendToUser($receiver, self::MESSAGE_TYPE_LINK_ACCEPT_TO_USER, '主播接受连麦', $extra);
    }
    
    public static function sendUserLinkAcceptToLive($userid, $liveid, $linkid)
    {
        /* {{{ 主播关闭、开启连麦许可，通知直播间用户 */
        $userInfo = User::getUserInfo($userid);
        $extra = array(
            "liveid" => $liveid,
            "userid"=>$userid,
            "linkid"=>$linkid,
            "nickname"=>$userInfo['nickname'],
            "avatar"=>$userInfo['avatar'],
            "level"=>$userInfo['level'],
            "medal"=>$userInfo['medal'],
            "gender"=>$userInfo['gender'],
            "founder"=>$userInfo['founder'],
            'rideurl'=>$userInfo['rideurl'],
            'rideid'=>$userInfo['rideid'],
            "vip" => (int)$userInfo['vip'],
        );
        self::sendToGroup($liveid, self::MESSAGE_TYPE_LINK_ACCEPT_TO_LIVE, $userid, '主播接受连麦', $extra);
    }

    public static function sendUserLinkPermit($userid, $liveid, $permit)
    {
        /* {{{ 主播关闭、开启连麦许可，通知直播间用户 */
        $userInfo = User::getUserInfo($userid);
        $extra = array(
            "liveid" => $liveid,
            "userid"=>$userid,
            "nickname"=>$userInfo['nickname'],
            "avatar"=>$userInfo['avatar'],
            "level"=>$userInfo['level'],
            "medal"=>$userInfo['medal'],
            "gender"=>$userInfo['gender'],
            "founder"=>$userInfo['founder'],
            'rideurl'=>$userInfo['rideurl'],
            'rideid'=>$userInfo['rideid'],
            "vip" => (int)$userInfo['vip'],
            "permit" => $permit
        );
        self::sendToGroup($liveid, self::MESSAGE_TYPE_LINK_PERMIT_TO_LIVE, $userid, '关闭/开启连麦许可', $extra);
    }
    
    
    public static function sendUserDisconnected($userid, $liveid, $type)
    {
        /* {{{ 主播关闭、开启连麦许可，通知直播间用户 */
        $userInfo = User::getUserInfo($userid);
        $extra = array(
            "liveid" => $liveid,
            "userid"=>$userid,
            "nickname"=>$userInfo['nickname'],
            "avatar"=>$userInfo['avatar'],
            "level"=>$userInfo['level'],
            "medal"=>$userInfo['medal'],
            "gender"=>$userInfo['gender'],
            "founder"=>$userInfo['founder'],
            'rideurl'=>$userInfo['rideurl'],
            'rideid'=>$userInfo['rideid'],
            "vip" => (int)$userInfo['vip'],
            "type" => $type
        );
        self::sendToGroup($liveid, self::MESSAGE_TYPE_LINK_DISCONNECTED_TO_LIVE, $userid, '连麦挂断', $extra);
    }
    
    


    public static function sendLivePrivacy($liveid, $userid, $title)
    { 
        
        $live = new Live();
        $live_info = $live->getLiveInfo($liveid);
        $privacy = Privacy::getPrivacyInfoByLiveInfo($live_info['privacy']);
        
        $buyers  = Counter::get(Counter::COUNTER_TYPE_LIVE_PRIVACY_WATCHES, $privacy['privacyid']);
        $watches = Counter::get(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid);

        $userinfo = User::getUserInfo($live_info['uid']);
        $feeds = Feeds::getLiveInfoFormat($live_info, $userinfo);
        
        return self::sendLivePrivacyToGroup($liveid, self::MESSAGE_TYPE_LIVE_PRIVACY, $userid, $title, $feeds, $watches, $buyers);
    }
    
    public static function sendLivePrivacyToGroup($liveid, $type, $sender, $text, $extends = array(),$watches,$buyers)
    {
        $wrappers = array(
            "liveid" => $liveid,
            "type" => $type,
            "text" => $text,
            "watches"=> $watches,
            "buyers"=>$buyers,
            "time" => time(),
            "expire" => self::EXPIRE,
            "extra" => $extends
        );

        $wrapper = array();
    
        $traceid = md5(serialize($wrappers));
    
        $wrappers["traceid"] = $traceid;
        $wrapper['content'] = $wrappers;
        file_put_contents('/tmp/privacy.log', 'wrappers='.json_encode($wrapper)."\n", FILE_APPEND);
    
        include_once 'message_client/RongCloudClient.php';
        $rongcloud_client = new RongCloudClient();
        $objectName = 'TxtMsg';
        $rongcloud_client->sendChatRoomMessage($liveid, $sender, json_encode($wrapper), $objectName);
    
    
        $wrapper['addtime'] = date("Y-m-d H:i:s");
    
        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("push_replay_title", array('liveid' => $liveid, 'sender' => $sender ,'content' => json_encode($wrapper)));
    
        return $traceid;
    }
    
}
?>
