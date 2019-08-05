<?php
require_once 'message_client/RongCloudClient.php';

class Chat
{
    const MAX_PATROLLER_LIMIT = 5; //场控最大个数

    public function send($liveid, $sender, $type, $content, $watches, $replace_keyword, $relateids, $user_ip, $deviceid, $is_multi = 0)
    {

        $userinfo = User::getUserInfo($sender);
        
        $live = new Live();
        $live_info = $live->getLiveInfo($liveid);
        $user_guard = UserGuard::getUserGuardRedis($sender, $live_info['uid']);
        
        
        //$privacy   = Privacy::hasPrivacyLive($live_info["uid"], $live_info["addtime"], $live_info["endtime"], $liveid);
        $privacy = Privacy::getPrivacyInfoByLiveInfo($live_info['privacy']);
        
        if (!empty($privacy) && isset($privacy['privacyid'])) {
            $watches = Counter::get(Counter::COUNTER_TYPE_LIVE_PRIVACY_WATCHES, $privacy['privacyid']);
        } else {
            $watches = Counter::get(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid);
        }
        if ($userinfo['level'] < 5) {
            include_once 'antispam_client/policy/Policy.php';
            $userinfo['nickname'] = Policy::format($userinfo['nickname']);
            $userinfo['nickname'] = preg_replace('/\s+/', '', $userinfo['nickname']);
            $userinfo['nickname'] = preg_replace('/\d{5,}/', "***", $userinfo['nickname']);
        }
        $data = array(
            "userid"=>$sender,
        "liveid"  => $liveid,
            "nickname"=>$userinfo['nickname'],
            "avatar"=>$userinfo['avatar'],
            "level"=>$userinfo['level'],
        "gender"=>$userinfo['gender'],
        "medal"=>$userinfo['medal'],
        "founder"=>$userinfo['founder'],
        'isGuard'=>intval($user_guard),
            "relateids"    => $relateids,//@相关人功能
            "vip" => (int)$userinfo['vip'],
            "fontcolor" => (string)Vip::getLevelConfig($userinfo['vip'], Vip::TYPE_FONT_COLOR),
            'anchor_token'=>$userinfo['anchor_token'],
        );

        $wrappers = array(
                "type"      => ($is_multi == 1) ? Messenger::MESSAGE_TYPE_CHATROOM_MULTI_TYPE : Messenger::MESSAGE_TYPE_CHATROOM_SEND,
                "watches" => $watches,
                "liveid"  => $liveid,
                "text"      => $content,
                "extra"      => $data,
                "time"    => time(),
        );

        $wrapper = array();
        $wrapper['content'] = $wrappers;
        $traceid = md5(serialize($wrapper));

        $wrapper["traceid"] = $traceid;
        
        include_once 'process_client/ProcessClient.php';
        
        try {
            $rongcloud_client = new RongCloudClient();
            $rongcloud_client->sendChatRoomMessage($liveid, $sender, json_encode($wrapper));
            
            $cache = new Cache("REDIS_CONF_CACHE");
            $key = "dreamlive_live_user_real_num_".$liveid;
            $result = json_decode($cache->get($key), true);
            $watches = $result['num']?$result['num']:0;
            $info = array(
            "liveid"     => $liveid,
            "nums"         => $watches,
            "addtime"    => date("Y-m-d H:i:s"),
            "type"        => 301,
            "date"        => date("Ymd"),
                    
            );
            ProcessClient::getInstance("dream")->addTask("chat_count_num", $info);
            
        } catch (Exception $e) {
            Logger::log("rongcloud_gettoken_error", "sendChat", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
        }
        
        
        $wrapper['addtime'] = date("Y-m-d H:i:s");
        
        
        ProcessClient::getInstance("dream")->addTask("push_replay_title", array('liveid' => $liveid, 'sender' => $sender ,'content' => json_encode($wrapper)));
        
        include_once 'antispam_client/AntiSpamClient.php';
        $anti_spam = new AntiSpamClient();
        $anti_spam->isDirty('chat', $content, $liveid, $sender, $traceid, $user_ip, $deviceid);

        try {
            $dao_chat = new DAOChat($liveid);
            $dao_chat->addMessage($liveid, $sender, $type, $content, $replace_keyword);
        } catch (Exception $e) {
            Logger::log("chat_send", "insert_chat_error", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
        }
        
        return true;
    }

    public function isKicked($liveid, $userid)
    {
        $cache = new Cache("REDIS_CONF_CACHE");
        $key = "room_kick_{$liveid}";
        return $cache->sIsMember($key, $userid);
    }

    public function kick($liveid, $userid)
    {
        $operator = intval(Context::get('userid'));
        $live = new Live();
        $patroller = new Patroller();
        $liveinfo = $live->getLiveInfo($liveid);
        $role = 1;// 操作者 身份 1 主播 2 场控 3 vip

        Interceptor::ensureNotFalse($liveinfo["status"] == Live::ACTIVING, ERROR_BIZ_LIVE_NOT_ACTIVE);
        Interceptor::ensureNotFalse(!($userid == $liveinfo['uid']), ERROR_BIZ_CHATROOM_NO_AUTHORITY_PATROLLER);
        Interceptor::ensureNotFalse(!(Chat::isKicked($liveid, $userid)), ERROR_BIZ_CHATROOM_HAS_KICKED);

        $author_info = User::getUserInfo($operator);
        $userinfo = User::getUserInfo($userid);
        //vip 防
        if($userinfo['vip'] > 0 ) {
            $userVipGuard = Vip::getLevelConfig($userinfo['vip'], Vip::TYPE_KICK);
            Interceptor::ensureFalse(Vip::KICK_GUARD_ALL == $userVipGuard, ERROR_BIZ_CHATROOM_VIP_GUARD);
            Interceptor::ensureFalse(Vip::KICK_GUARD_OP == $userVipGuard && $operator != $liveinfo['uid'], ERROR_BIZ_CHATROOM_VIP_GUARD);
        }
        //场控 vip
        if ($operator != $liveinfo['uid']) {
            //先 场控 然后 vip  
            $operatorIsPartroller = $patroller->isPatroller($operator, $liveinfo['uid'], $liveid);
            if(!$operatorIsPartroller && $author_info['vip']>0) {
                $balance = Vip::incrLeftNumber($operator, Vip::TYPE_KICK_NUM);
                Interceptor::ensureNotFalse($balance > -1, ERROR_BIZ_CHATROOM_VIP_KICK_NUM_LT_ONE);
                $role = 3;
            }else{
                Interceptor::ensureNotFalse($operatorIsPartroller, ERROR_BIZ_CHATROOM_NO_AUTHORITY_PATROLLER);
                $role = 2;
            }
            //守护不能踢
            Interceptor::ensureNotFalse(!UserGuard::getUserGuardRedis($userid, $liveinfo['uid']), ERROR_BIZ_CHATROOM_NO_AUTHORITY_PATROLLER);
            //场控不能踢
            Interceptor::ensureNotFalse(!$patroller->isPatroller($userid, $liveinfo['uid'], $liveid), ERROR_BIZ_CHATROOM_NO_AUTHORITY_PATROLLER);
        }

        $ispatroller = ($operator != $liveinfo['uid']) ? 'Y' : 'N';
        
        $operator_name = $author_info['nickname'];
        $opratorinfo  = User::getUserInfo($operator);
        $bool = Messenger::sendKick($liveid, $userinfo['nickname'] . "被{$operator_name}踢出房间", Context::get('userid'), $userid, $userinfo['nickname'], $userinfo['avatar'], $userinfo['exp'], $userinfo['level'], $ispatroller, $opratorinfo);

        Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $userid, "您被“{$operator_name}” 逐出房间", "您被“{$operator_name}” 逐出房间", $liveid);
        
        usleep(5000);
        
        $cache = new Cache("REDIS_CONF_CACHE");
        $key = "room_kick_{$liveid}";
        $cache->sAdd($key, $userid);
        $cache->expire($key, 86400);

        if ($bool) {
            $rongcloud_client = new RongCloudClient();
            $rongcloud_client->addKickUser($liveid, $userid);
        }

        $dao_patroller_log = new DAOPatrollerLog();

        $dao_patroller_log->addPatrollerLog($operator, $userid, $ispatroller, 'KICK', $liveid);

        return array(
            'operator' => $role,
            'balance'  => $balance ? $balance:0
        );
    }

    public function addSilence($liveid, $userid)
    {
        $cache = new Cache("REDIS_CONF_CACHE");
        $patroller = new Patroller();
        $operator = intval(Context::get('userid'));
        $live = new Live();
        $liveinfo = $live->getLiveInfo($liveid);
        $role = 1;// 操作者 身份 1 主播 2 场控 3 vip

        Interceptor::ensureNotFalse($liveinfo["status"] == Live::ACTIVING, ERROR_BIZ_LIVE_NOT_ACTIVE);

        Interceptor::ensureNotFalse(!($userid == $liveinfo['uid']), ERROR_BIZ_CHATROOM_NO_AUTHORITY_PATROLLER);

        $author_info = User::getUserInfo($operator);
        $userinfo = User::getUserInfo($userid);
        if($userinfo['vip'] > 0 ) {
            $userVipGuard = Vip::getLevelConfig($userinfo['vip'], Vip::TYPE_SILENCE);
            Interceptor::ensureFalse(Vip::SILENCE_GUARD_ALL == $userVipGuard, ERROR_BIZ_CHATROOM_VIP_GUARD);
            Interceptor::ensureFalse(Vip::SILENCE_GUARD_OP == $userVipGuard && $operator != $liveinfo['uid'], ERROR_BIZ_CHATROOM_VIP_GUARD);
        }

        if ($operator != $liveinfo['uid']) {

            $operatorIsPartroller = $patroller->isPatroller($operator, $liveinfo['uid'], $liveid);
            if(!$operatorIsPartroller && $author_info['vip']>0) {
                $balance = Vip::incrLeftNumber($operator, Vip::TYPE_SILENCE_NUM);
                Interceptor::ensureNotFalse($balance > -1, ERROR_BIZ_CHATROOM_VIP_SILENCE_NUM_LT_ONE);
                $role = 3;
            }else{
                Interceptor::ensureNotFalse($operatorIsPartroller, ERROR_BIZ_CHATROOM_NO_AUTHORITY_PATROLLER);
                $role = 2;
            }

            //守护不能禁言
            Interceptor::ensureNotFalse(!UserGuard::getUserGuardRedis($userid, $liveinfo['uid']), ERROR_BIZ_CHATROOM_NO_AUTHORITY_PATROLLER);
            Interceptor::ensureNotFalse(!$patroller->isPatroller($userid, $liveinfo['uid'], $liveid), ERROR_BIZ_CHATROOM_NO_AUTHORITY_PATROLLER);
        }
        
        $ispatroller = ($operator != $liveinfo['uid']) ? 'Y' : 'N';

        $key = "room_slience_{$liveid}";
        $cache->sAdd($key, $userid);
        $cache->expire($key, 86400);

        $operator_name =  $author_info['nickname'];
        $opratorinfo  = User::getUserInfo($operator);
        Messenger::sendSilence($liveid, $userinfo['nickname'] . "被{$operator_name}禁言了", Context::get('userid'), $userid, $userinfo['nickname'], $userinfo['avatar'], $userinfo['exp'], $userinfo['level'], $ispatroller, $opratorinfo);

        $dao_patroller_log = new DAOPatrollerLog();

        $dao_patroller_log->addPatrollerLog($operator, $userid, $ispatroller, 'SILENCE', $liveid);

        return array(
            'operator' => $role,
            'balance'  => $balance ? $balance:0
        );
    }

    public function delSilence($liveid, $userid)
    {
        $cache = new Cache("REDIS_CONF_CACHE");

        $operator = intval(Context::get('userid'));
        $live = new Live();
        $patroller = new Patroller();
        $liveinfo = $live->getLiveInfo($liveid);

        Interceptor::ensureNotFalse($liveinfo["status"] == Live::ACTIVING, ERROR_BIZ_LIVE_NOT_ACTIVE);

        Interceptor::ensureNotFalse(!($userid == $liveinfo['uid']), ERROR_BIZ_CHATROOM_NO_AUTHORITY_PATROLLER);

        if ($operator != $liveinfo['uid']) {
            Interceptor::ensureNotFalse($patroller->isPatroller($operator, $liveinfo['uid'], $liveid), ERROR_BIZ_CHATROOM_NO_AUTHORITY_PATROLLER);
        }

        if ($patroller->isPatroller($userid, $liveinfo['uid'], $liveid)) {
            Interceptor::ensureNotFalse($operator == $liveinfo['uid'], ERROR_BIZ_CHATROOM_NO_AUTHORITY_PATROLLER);
        }

        $ispatroller = ($operator != $liveinfo['uid']) ? 'Y' : 'N';

        $key = "room_slience_{$liveid}";
        $cache->sRemove($key, $userid);
        $cache->expire($key, 86400);

        $author_info = User::getUserInfo($operator);
        $userinfo = User::getUserInfo($userid);
        $operator_name = $author_info['nickname'];
        $opratorinfo  = User::getUserInfo($operator);
        Messenger::sendUnSilence($liveid, $userinfo['nickname'] . "被{$operator_name}解除禁言了", Context::get('userid'), $userid, $userinfo['nickname'], $userinfo['avatar'], $userinfo['exp'], $userinfo['level'], $ispatroller, $opratorinfo);

        $dao_patroller_log = new DAOPatrollerLog();

        $ispatroller = ($operator != $liveinfo['uid']) ? 'Y' : 'N';

        $dao_patroller_log->addPatrollerLog($operator, $userid, $ispatroller, 'UNSILENCE', $liveid);

        return true;
    }

    public function isSilence($liveid, $userid)
    {
        $cache = new Cache("REDIS_CONF_CACHE");

        $key = "room_slience_{$liveid}";
        return $cache->sIsMember($key, $userid);
    }



    public function voteMessage($author, $relateid, $liveid, $activity_id)
    {
        $watches          = Counter::get(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid);
        $userinfo = User::getUserInfo($author);
        Messenger::sendLiveVote($liveid, $userinfo['nickname'] . "为主播投了一票,", $relateid, $author, $userinfo['nickname'], $userinfo['avatar'], $userinfo['exp'], $userinfo['level'], $watches, $activity_id);

        return true;
    }
    
    
    public function multiSend($liveid, $sender, $type, $content, $watches, $replace_keyword, $relateids, $user_ip, $deviceid)
    {
        $userinfo = User::getUserInfo($sender);
        
        $live = new Live();
        $live_info = $live->getLiveInfo($liveid);

        $watches = Counter::get(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid);
        
        $data = array(
        "userid"=>$sender,
        "liveid"  => $liveid,
        "nickname"=>$userinfo['nickname'],
        "avatar"=>$userinfo['avatar'],
        "level"=>$userinfo['level'],
        "gender"=>$userinfo['gender'],
        "medal"=>$userinfo['medal'],
        "founder"=>$userinfo['founder'],
        'isGuard'=>0,
        "relateids"    => $relateids,//@相关人功能
        "vip" => (int)$userinfo['vip'],
        "fontcolor" => (string)Vip::getLevelConfig($userinfo['vip'], Vip::TYPE_FONT_COLOR),
        'anchor_token'=>$userinfo['anchor_token'],
        );
        
        $wrappers = array(
        "type"      => Messenger::MESSAGE_TYPE_CHATROOM_MULTI_TYPE,
        "watches" => $watches,
        "liveid"  => $liveid,
        "text"      => $content,
        "extra"      => $data,
        "time"    => time(),
        );
        
        $wrapper = array();
        $wrapper['content'] = $wrappers;
        $traceid = md5(serialize($wrapper));
        
        $wrapper["traceid"] = $traceid;
        
        include_once 'process_client/ProcessClient.php';
        
        try {
            $rongcloud_client = new RongCloudClient();
            $rongcloud_client->sendChatRoomMessage($liveid, $sender, json_encode($wrapper));
            
            $cache = new Cache("REDIS_CONF_CACHE");
            $key = "dreamlive_live_user_real_num_".$liveid;
            $result = json_decode($cache->get($key), true);
            $watches = $result['num']?$result['num']:0;
            $info = array(
            "liveid"     => $liveid,
            "nums"         => $watches,
            "addtime"    => date("Y-m-d H:i:s"),
            "type"        => 330,
            "date"        => date("Ymd"),
                    
            );
            ProcessClient::getInstance("dream")->addTask("chat_count_num", $info);
            
        } catch (Exception $e) {
            Logger::log("rongcloud_gettoken_error", "sendChat", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
        }
        
        
        $wrapper['addtime'] = date("Y-m-d H:i:s");

        include_once 'antispam_client/AntiSpamClient.php';
        $anti_spam = new AntiSpamClient();
        $anti_spam->isDirty('chat', $content, $liveid, $sender, $traceid, $user_ip, $deviceid);
        
        try {
            $dao_chat = new DAOChat($liveid);
            $dao_chat->addMessage($liveid, $sender, $type, $content, $replace_keyword);
        } catch (Exception $e) {
            Logger::log("chat_send", "insert_chat_error", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
        }
        
        return true;
    }
    
    public function addWorldChat($sender, $content)
    {
        $cache     = new Cache("REDIS_CONF_CACHE");
        $key     = "world_chat_history_key";
        $user     = new User();
        $user_info = $user->getUserInfo($sender);
        
        
        $user_item = array(
        "uid"         => $user_info['uid'],
        "nickname"    => $user_info['nickname'],
        "level"     => $user_info['level'],
        "vip"        => $user_info['vip'],
        "medal"     => $user_info['medal'],
        "gender"     => $user_info['gender'],
        "avatar"    => $user_info['avatar'],
        "content"     => $content,
        "addtime"    => time(),
        );
        
        $length = $cache->lLen($key);
        if ($length < 100) {
            $cache->rPush($key, json_encode($user_item));
        } else {
            $cache->lPop($key);
            $cache->rPush($key, json_encode($user_item));
        }
        return true;
    }
    
    
    public function getWorldChatList()
    {
        $cache     = new Cache("REDIS_CONF_CACHE");
        $key     = "world_chat_history_key";
        
        $data = $cache->lRange($key, 0, 100);
        
        $re_data = [];
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $item = json_decode($value, true);
                if (Forbidden::isForbidden($item['uid'])) {
                    continue;
                }
                $item['liveid'] = 1111;
                
                $re_data[] = $item;
            }
        }
        
        return $re_data;
    }
}
?>
