<?php
class RobotChat
{
    const LAST_CHATING_KEY = 'last_chat_key_chache';
    
    /**
     * 记录聊天室最后一个聊天时间，区分热门直播间
     *
     * @param int $liveid
     */
    static public function recordLastChat($liveid) 
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $cache->zAdd(self::LAST_CHATING_KEY, time(), $liveid);
    }
    
    
    /**
     * 发消息
     *
     * @param  int    $liveid
     * @param  int    $sender
     * @param  string $content
     * @return boolean
     */
    static public function sendChat($liveid, $sender, $content)
    {
        
        $userinfo = User::getUserInfo($sender);
        
        $watches      = Counter::get(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid);
        
        $data = array(
        "userid"=>$sender,
        "liveid"  => $liveid,
        "nickname"=>$userinfo['nickname'],
        "avatar"=>$userinfo['avatar'],
        "level"=>$userinfo['level'],
        "medal"=>$userinfo['medal'],
        "founder"=>$userinfo['founder'],
        );
        
        $wrappers = array(
        "type"      => Messenger::MESSAGE_TYPE_CHATROOM_SEND,
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
        include_once 'message_client/RongCloudClient.php';
        $rongcloud_client = new RongCloudClient();
        $rongcloud_client->sendChatRoomMessage($liveid, $sender, json_encode($wrapper));
        
        $wrapper['addtime'] = date("Y-m-d H:i:s");
        
        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("push_replay_title", array('liveid' => $liveid, 'sender' => $sender ,'content' => json_encode($wrapper)));
        $rank = new Rank();

        $rank->setRank('liverobots', "delete", $sender, 1, $liveid);
        
        return true;
    }
    
}

?>