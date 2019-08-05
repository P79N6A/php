<?php
class Message
{
    public function send($receiver, $sender, $type, $content, $replace_keyword, $user_ip, $deviceid)
    {
        $user = new User();
        $sender_info = $user->getUserInfo($sender);
        
        //是否被对方拉黑
        Interceptor::ensureNotFalse(!Blocked::exists($receiver, $sender), ERROR_USER_IS_BLOCKED, "$sender");

        if ($receiver != Messenger::KEFUID) {
            $version = str_replace('.', '', Context::get("version"));
            
            $version = intval($version);
            //Logger::log("send_message_log", "mesage1", array("sender" => $sender, 'bool' => ($version > 2910), "receiver" => $receiver,"version" =>Context::get("version")));
            if ($version >= 2910 || $version >= 300) {
                
                $is_can_send = self::checkIsCanSend($sender, $receiver, $sender_info['level'], $sender_info['vip']);
                //Logger::log("send_message_log", "mesage2", array("sender" => $sender, 'bool' => $is_can_send, "receiver" => $receiver,"version" =>Context::get("version")));
                if (! $is_can_send) {
                    Interceptor::ensureNotFalse(false, ERROR_MESSAGE_NO_AUTHORITY);//没有好友关系没权限
                }
            } else {
                $send_gift_total          = (int) Counter::get(Counter::COUNTER_TYPE_PAYMENT_SEND_GIFT, $sender);
                $receive_gift_total       = (int) Counter::get(Counter::COUNTER_TYPE_PAYMENT_RECEIVE_GIFT, $sender);
                
                if (($send_gift_total < 100 && $receive_gift_total < 10)) {//送礼大于等于100钻,或收入大于10钻
                    
                    return true;
                    
                }
            }
        }
        
        $relation = Follow::relation($receiver, $sender);//接收者和发送者的关系
        
        Messenger::sendPrivateMessage($receiver, $sender, $sender_info["nickname"], $sender_info["avatar"], $sender_info["level"], $relation, $content, 0, $user_ip, $deviceid);
        
        
        $dao_message = new DAOMessage($receiver);
        $dao_message->addMessage($sender, $receiver, $type, $content, $replace_keyword);

        return true;
    }
    
    public function notice($sender, $liveid, $content)
    {
        
        $config = new Config();
        $results = $config->getConfig("china", "follow_notice_config", "server", '1000000000000');
        
        $json_value = json_decode($results['value'], true);
        
        $cost_star_price = isset($json_value['price']) && !empty($json_value['price']) ? $json_value['price'] : 1000;
        
        $send_times_per_day = isset($json_value['times']) && !empty($json_value['times']) ? $json_value['times'] : 3;
        
        $account     = new Account();
        $dao_account = new DAOAccount($sender);
        try {
            $dao_account->startTrans();
            
            $orderid = $account->getOrderId($sender);
            
            $extends = [
            'sender' => $sender,
            'content' => $content,
            'liveid' => $liveid,
            ];
            
            $cost_star = $cost_star_price;
            $star = $account->getBalance($sender, ACCOUNT::CURRENCY_STAR);
            Interceptor::ensureNotEmpty($star, ERROR_BIZ_PAYMENT_STAR_BALANCE_DUE, "");
            Interceptor::ensureNotFalse($star>= $cost_star, ERROR_BIZ_PAYMENT_STAR_BALANCE_DUE, "");
            $cache = Cache::getInstance("REDIS_CONF_CACHE");
            $_had_send_key = "send_notice_" . date("Ymd") ."_" .$sender;
            
            $bool = $cache->get($_had_send_key);
            
            if (isset($bool) && $bool > $send_times_per_day) {
                Interceptor::ensureNotFalse(false, ERROR_BIZ_NOTICE_ONLY_THREE);
            }
            
            $cache->INCR($_had_send_key);
            
            $_had_send_key_liveid = "send_notice_" . date("Ymd") ."_" .$sender . "_" . $liveid;
            
            $bool = $cache->get($_had_send_key_liveid);
            
            if (isset($bool) && $bool > 0) {
                Interceptor::ensureNotFalse(false, ERROR_BIZ_NOTICE_ONLY_LIVE_ONE);
            }
            
            $cache->INCR($_had_send_key_liveid);
            $cache->expire($_had_send_key_liveid, 3600);
            Interceptor::ensureNotFalse($account->decrease($sender, ACCOUNT::TRADE_TYPE_SYSTERM_TOOL, $orderid, $cost_star, ACCOUNT::CURRENCY_STAR, "用户{$sender}使用粉丝通知{$content},消费{$cost_star}星星", $extends), ERROR_BIZ_PAYMENT_STAR_OUT_ACCOUNTED);
        
            $dao_account->commit();
        } catch (Exception $e) {
            $dao_account->rollback();
            throw $e;
        }
        
        return true;
    }
    
    /**
     * 小于16级
     * 不是好友关系
     * 不是vip
     * 不是守护关系
     * 不是被守护关系
     */
    static public function checkIsCanSend($sender, $receiver, $sender_level, $sender_vip) 
    {
        $relation = Follow::relation($receiver, $sender);//接收者和发送者的关系
        
        $user_guard = UserGuard::getUserGuardRedis($sender, $receiver);
        
        $guard_user = UserGuard::getUserGuardRedis($receiver, $sender);
        
        $config = new Config();
        $results = $config->getConfig("china", "private_message_level", "server", '1000000000000');
        
        $private_message_level = !empty($results['value']) ? intval($results['value']) : 16;
        
        if ($sender_level < $private_message_level && $relation != 'friend' && empty($sender_vip) && empty($user_guard) && empty($guard_user)) {
            return false;
        } else {
            return true;
        }
    }
}
?>
