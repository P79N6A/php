<?php

class ChatWhiteList
{
    
    /**
     * 查看是否可以聊天
     *
     * @param  liveid 聊天室id
     * @param  sender 发送消息人
     * @return true 可以聊天 false 不可以聊天
     */
    static public function checkIsCanChat($liveid, $sender)
    {
        $live = new Live();
        $live_info = $live->getLiveInfo($liveid);
        
        $cache = new Cache("REDIS_CONF_CACHE");
        $white_live_uid_key = "forbidden_live_chat_users_keys";
        
        $forbidden_chat_info = json_decode($cache->get($white_live_uid_key), true);
        
        if (empty($forbidden_chat_info)) {
            return true;
        }
        
        if (in_array($live_info['uid'], array_keys($forbidden_chat_info))) {//此类型主播聊天室禁止发言
            $detail_info     = $forbidden_chat_info[$live_info['uid']];
            
            $starttime         = !empty($detail_info['starttime']) ? strtotime($detail_info['starttime']) : 0 ;
            $endtime        = !empty($detail_info['endtime']) ? strtotime($detail_info['endtime']) : 0 ;
            $white_user_ids = !empty($detail_info['relateids']) ? explode(',', $detail_info['relateids']) : array();
            
            $white_user_ids[] = $live_info['uid'];
            
            $now = time();
            if (empty($starttime) && empty($endtime) && empty($white_user_ids)) {
                return false;
            }
            
            if ($now >= $starttime && $now <= $endtime) {//有时间范围设置
                if (!empty($white_user_ids) && in_array($sender, $white_user_ids)) {//有白名单设置
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        }
        
        return true;
    }
}

?>