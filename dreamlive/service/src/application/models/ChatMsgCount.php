<?php
class ChatMsgCount
{
    
    /**
     * 消息统计
     *
     * @param int $liveid
     */
    static public function count($liveid, $type)
    {
        
        try {
            include_once 'process_client/ProcessClient.php';
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
            
        }
        
        return true;
    }
}