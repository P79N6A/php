<?php 

class MatchMessage
{
    
    
    /**
     * pk人员对应pkid的redis映射
     *
     * @param array    $members
     * @param int      $matchid
     * @param datetime $endtime
     */
    static public function setMatchMemberRedis(array $members, $matchid, $endtime)
    {
        
        Logger::log("pk_match_log", "setMatchMemberRedis", array("matchid" => $matchid));
        
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key_prefix = "member_match_";
        $expired_seconds = (strtotime($endtime) - time());
        
        try{
            foreach ($members as $member) {
                $cache->set($key_prefix . $member, $matchid);
                $cache->expire($key_prefix . $member, $expired_seconds);
            }
            
            $match_redis_key = "hmset_match_info_" . $matchid;
            $cache_info = [];
            $cache_info['members'] = json_encode($members);
            $cache_info['endtime'] = $endtime;
            $cache_info['unix_endtime'] = strtotime($endtime);
            $cache->hmset($match_redis_key, $cache_info);
        } catch (Exception $e) {
            Logger::log("pk_match_log", "setMatchMemberRedis_error", array("matchid" => $matchid,"code"=>$e->getCode(),"msg"=>$e->getMessage()));
        }
        
        return true;
    }
    
    /**
     * pk结束后删除比赛redis
     *
     * @param int $matchid
     */
    static public function remMatchMemberRedis($matchid)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $match_redis_key = "hmset_match_info_" . $matchid;
        $cache->del($match_redis_key);
    }
    
    /**
     * 根据用户id获取比赛id
     *
     * @param  int $userid
     * @return $matchid
     */
    static public function getRedisMatchId($userid)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key_prefix = "member_match_";
        
        $matchid = $cache->get($key_prefix . $userid);
        Logger::log("pk_match_log", "getmatchid", array("userid" => $userid));
        return $matchid;
    }
    
    
    /**
     * 给pk对手同步分数
     *
     * @param int    $receiver
     * @param int    $amount
     * @param string $type     gift,ticket,guard
     * @param int    $matchid
     */
    static public function sendMatchMemberScore($sender, $receiver, $amount, $type, $matchid)
    {
        
        
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $match_redis_key     = "hmset_match_info_" . $matchid;
        $living_user_key       = "User_living_cache";
        $devote_rank_key    = "devote_rank_" . $matchid . '_' . $receiver;
        $match_info         = $cache->hgetall($match_redis_key);
        $match_members         = json_decode($match_info['members'], true);
        $now                 = time();
        $receive_ticket        = 0;
        Logger::log("pk_match_log", "sendMatchMemberScore", array("matchid" => $matchid, "sender" => $sender, "receiver" => $receiver, "matchifo" => json_encode($match_info)));
        if (!empty($match_info) && $match_info['unix_endtime'] > $now && !empty($match_members)) {
            
            try {
                
                $receive_ticket         = Counter::increase(Counter::COUNTER_TYPE_MATCH_RECEIVE_GIFT, $receiver .'_' .$matchid, $amount);
                $cache->zIncrBy($devote_rank_key, $amount, $sender);
                
                include_once "process_client/ProcessClient.php";
                ProcessClient::getInstance("dream")->addTask("live_rank_generate", array("type" => "matchreceiver", "action" => "increase", "userid" => $receiver, "score" => $amount, "relateid" => $sender));
                ProcessClient::getInstance("dream")->addTask("live_rank_generate", array("type" => "matchsender", "action" => "increase", "userid" => $sender, "score" => $amount, "relateid" => $receiver));
                
            } catch (Exception $e) {
                Logger::log("pk_match_log", "write  counter and rank", array("matchid" => $matchid, "sender" => $sender, "receiver" => $receiver, "code"=>$e->getCode(),"msg"=>$e->getMessage()));
            }
            
            
            Logger::log("pk_match_log", "increase", array('mem' => json_encode($match_members)));
            
            foreach ($match_members as $member) {
                if ($receiver != $member) {
                    $liveid = $cache->zScore($living_user_key, $member);
                    
                    if (!empty($liveid)) {
                        Messenger::sendLiveMatchMemberScore($liveid, $receiver, $matchid, $receive_ticket);
                        Logger::log("pk_match_log", "sendok", array("matchid" => $matchid, "liveid" => $liveid));
                    } else {
                        Logger::log("pk_match_log", "sendgetLiveId", array("matchid" => $matchid, "uid" => $member));
                    }
                }
            }
        }
        
        return $receive_ticket;
    }
    
    /**
     * 爆发榜单
     *
     * @param int $matchid
     * @param int $uid
     */
    static public function addPkBreakRank($matchid, $uid)
    {
        $pk_break_key        = "pk_sender_break";
        $cache                 = Cache::getInstance("REDIS_CONF_CACHE");
        
        $devote_rank_key    = "devote_rank_" . $matchid . '_' . $uid;
        $elements  = $cache->zRevRangeByScore($devote_rank_key, PHP_INT_MAX, 0, ['withscores' => true, 'limit' => [0, 1]]);
        if (!empty($elements)) {
            $addiences = array_keys($elements);
            foreach ($addiences as $sender_uid) {
                //$sender_uid = $addiences[0];
                if (!empty($sender_uid)) {
                    $new_score = $elements[$sender_uid];
                    
                    $old_score = $cache->zScore($pk_break_key, $sender_uid);
                    
                    if (empty($old_score) || $new_score > $old_score) {
                        $cache->zAdd($pk_break_key, $new_score, $sender_uid);
                        Logger::log("pk_match_log", "break", array("score" => $new_score, "uid" => $sender_uid));
                    }
                }
            }
        }
        
        return true;
    }
    
    
    static public function getPkRankListAll($matchid, $uid)
    {
        $cache                 = Cache::getInstance("REDIS_CONF_CACHE");
        $devote_rank_key    = "devote_rank_" . $matchid . '_' . $uid;
        $rank_elements  = $cache->zRevRangeByScore($devote_rank_key, PHP_INT_MAX, 0, ['withscores' => true, 'limit' => [0, -1]]);
        
        return $rank_elements;
    }

}
?>