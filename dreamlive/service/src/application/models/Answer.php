<?php

class Answer
{

    CONST ANSWER_TIME_OUT = 20;
    
    
    public function getWinners($roundid)
    {
        $cache = Cache::getInstance('REDIS_CONF_CACHE');
        $key_last_order = "key_Order:" . $roundid . '_' . 12;
        $last_question_id = $cache->get($key_last_order);//上一题目id
        
        $question_info = Answer::getQuestionInfo($roundid, $last_question_id);
        $correct_answer = strtoupper(trim($question_info['correct_answer']));
        
        $winner_set_key = 'Set_DA:' . $roundid. '_' . $last_question_id. '_' . $correct_answer;
        
        $winnerList = $cache->smembers($winner_set_key);
        
        return $winnerList;
    }
    
    
    static public function getQuestionInfo($roundid, $questionid)
    {
        $cache = Cache::getInstance('REDIS_CONF_CACHE');
        $key_question= "key_AN:". $roundid. '_' . $questionid;
        try {
            $question_info = json_decode($cache->get($key_question), true);//当前题目信息
        } catch (Exception $e) {
            Logger::log("answer_log", "get cache questioninfo:", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
        }
        
        return $question_info;
    }
    
    public function cashSend($roundid, $amount, $questionid)
    {
        Logger::log("answer_log", "get params :", array("roundid" => $roundid, "amount" => $amount, 'questionid' => $questionid));
        $cache = Cache::getInstance('REDIS_CONF_CACHE');
        
        $key = "act_match_bonus";
        try {
            $round_info = json_decode($cache->get($key), true);
        } catch (Exception $e) {
            Logger::log("answer_log", "get cache act_match_bonus:", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
        }
        
        // if ($round_info['is_end'] == '1') {
        //     Interceptor::ensureNotFalse(false, ERROR_SUMMIT_USER_IS_ENDED);//活动已结束
        // }

        // $round_info['is_end'] = 1;
        
        // try {
        //     $cache->set($key, json_encode($round_info));
        // } catch (Exception $e) {
        //     Logger::log("answer_log", "cashSend setRoundToCache is end", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
        // }
        
        $question_info = Answer::getQuestionInfo($roundid, $questionid);
        $correct_answer = strtoupper(trim($question_info['correct_answer']));
        
        $winner_set_key = 'Set_DA:' . $roundid. '_' . $questionid. '_' . $correct_answer;
        
        $winnerList = $cache->smembers($winner_set_key);

        Logger::log("answer_log", "winner cashSend :", array("winnerList" => json_encode($winnerList)));
        
        include_once 'process_client/ProcessClient.php';
        try{
            if (!empty($winnerList)) {
                foreach ($winnerList as $uid) {
                    $array = [];
                    $array[$uid] = $amount;
                    //AccountAnswerInterface::cash(json_encode($array));
                    ProcessClient::getInstance("dream")->addTask("answer_cash_send", array("uid"=> $uid,"jsonstr"=> json_encode($array),"roundid"=> $roundid,));
                    Logger::log("answer_log", "winner cashSend worker :", array("uid"=> $uid,"jsonstr" => json_encode($array)));
                    
                }
            }
        } catch (Exception $e) {
            Logger::log("answer_log", "write yjj cashSend :", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
        }
        
        
        
        Logger::log("answer_log", "get cashSend :", array("roundid" => $roundid, "amount" => $amount, 'questionid' => $questionid));
        
        return $winnerList;
    }
    
    
    public function setRoundToCache($roundid, $title, $amount, $startime)
    {
        $cache = Cache::getInstance('REDIS_CONF_CACHE');
        $key = "act_match_bonus";
        $array = [];
        
        $array['roundid']     = $roundid;
        $array['title']        = $title;
        $array['amount']    = $amount;
        $array['startime']  = $startime;
        $array['start_unixtime'] = strtotime($startime);
        $array['is_end']    = 0;
        
        try {
            $cache->set($key, json_encode($array));
        } catch (Exception $e) {
            Logger::log("answer_log", "setRoundToCache", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
        }
        
        return true;
    }
    
    
    /**
     * 
     * @param  int $roundid
     * @param  int $questionid
     * @param  int $answer
     * @param  int $order
     * @return bool
     */
    public function publish($roundid, $questionid, $answer, $order, $duration, $title = '')
    {
        $cache = Cache::getInstance('REDIS_CONF_CACHE');
        
        try{
            $liveid = $cache->ZSCORE("User_living_cache", 666666);
            if (!empty($liveid)) {
                $send_onetime_fre = "send_ontime_frequency".$liveid;
                if (! ($cache->INCR($send_onetime_fre) > 1) ) {
                    include_once 'message_client/RongCloudClient.php';
                    $rongcloud_client = new RongCloudClient();
                    
                    $rongcloud_client->addChatroomWhiteList($liveid, 10000000);
                }
                
                $cache->expire($send_onetime_fre, 172800);//两天有效期
            }
        } catch (Exception $e) {
            
        }
        
        $act_match_key = "act_match_bonus";
        try {
            $round_info = json_decode($cache->get($act_match_key), true);
        } catch (Exception $e) {
            Logger::log("answer_log", "get cache act_match_bonus:", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
        }
        
        $now = time();
        if ($roundid != $round_info['roundid'] || $now < $round_info['start_unixtime']) {
            Interceptor::ensureNotFalse(false, ERROR_SUMMIT_USER_IS_UNSTART);//活动未开启
        }
        
        if ($round_info['is_end'] == '1') {
            Interceptor::ensureNotFalse(false, ERROR_SUMMIT_USER_IS_ENDED);//活动已结束
        }
        
        $key = "key_AN:". $roundid. '_' . $questionid;
        $value_array = [];
        $now = time();
        $value_array['correct_answer']         = strtoupper($answer);
        $value_array['title']                 = $title;
        $value_array['startime_unix']         = $now;
        $value_array['endtime_unix']         = $now + $duration + 3;
        $value_array['duration']            = $duration;
        $value_array['startime']            = date("Y-m-d H:i:s", $now);
        $value_array['endtime']                = date("Y-m-d H:i:s", $value_array['endtime_unix']);
        $key_order = "key_Order:".$roundid .'_' . $order;
        
        $key_now_order = "now_Order:" . $roundid;
        try {
            $cache->set($key, json_encode($value_array));
        } catch (Exception $e) {
            Logger::log("answer_log", "set key_AN:", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
        }
        
        try {
            $cache->set($key_order, $questionid);
            $cache->set($key_now_order, $order);
        } catch (Exception $e) {
            Logger::log("answer_log", "set key_Order:", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
        }
        
        return true;
    }
    
    /**
     * 
     * @param int $roundid
     * @param int $questionid
     * @param int $answer
     * @param int $order
     * @param int $userid
     * return array();
     */
    public function submit($roundid, $questionid, $answer, $order, $userid)
    {
        $now = time();
        $cache = Cache::getInstance('REDIS_CONF_CACHE');
        
        //返回数组
        $return_msg = ['is_correct' => 0, 'use_rivival' => 0,  'questionid' => $questionid, 'roundid' => $roundid, 'order' => $order];
        
        //----------------------获取场次信息-----------------
        $key = "act_match_bonus";
        try {
            $round_info = json_decode($cache->get($key), true);
        } catch (Exception $e) {
            Logger::log("answer_log", "get cache act_match_bonus:", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
        }
        
        $now = time();
        if ($roundid != $round_info['roundid'] || $now < $round_info['start_unixtime']) {
            Interceptor::ensureNotFalse(false, ERROR_SUMMIT_USER_IS_UNSTART);//活动未开启
        }
        
        if ($round_info['is_end'] == '1') {
            Interceptor::ensureNotFalse(false, ERROR_SUMMIT_USER_IS_ENDED);//活动已结束
        }
        //----------------------获取场次信息-----------------
        
        //----------------------获取场次顺序号-----------------
        $key_now_order = "now_Order:" . $roundid;
        try {
            $order = trim($cache->get($key_now_order));
        } catch (Exception $e) {
            Logger::log("answer_log", "get order:", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
        }
        //----------------------获取场次顺序号-----------------
        
        //----------------------验证题目信息答题开始时间答题结束时间-----------------
        $question_info = Answer::getQuestionInfo($roundid, $questionid);
        
        if (empty($question_info)) {
            Interceptor::ensureNotFalse(false, ERROR_SUMMIT_USER_QUESTION_NOT_SEND);//题目还未下发
        }
        
        $correct_answer = strtoupper(trim($question_info['correct_answer']));
        $endtime_unix     = trim($question_info['endtime_unix']);
        $startime_unix     = trim($question_info['startime_unix']);
        
        if ($now > $endtime_unix) {
            Interceptor::ensureNotFalse(false, ERROR_SUMMIT_USER_QUESTION_IS_END);//答题时间已结束
        }
        
        if ($now < $startime_unix) {
            Interceptor::ensureNotFalse(false, ERROR_SUMMIT_USER_QUESTION_NOT_START);//答题时间未开始
        }
        //----------------------验证题目信息答题开始时间答题结束时间-----------------
        
        
        //----------------------每人每场次每道题答一次-----------------
        $one_person_one_round_one_question_key = "key_only_one_" . $roundid . '_' . $questionid. '_' . $userid;
        if ($cache->INCR($one_person_one_round_one_question_key) > 1) {
            Interceptor::ensureNotFalse(false, ERROR_SUMMIT_USER_QUESTION_ONLY_ONE_TIME);//每人每场次每道题答一次
        }
        $cache->expire($one_person_one_round_one_question_key, 1800);
        
        //----------------------每人每场次每道题答一次-----------------
        
        //----------------------判断除了第一道题的上一道题是否答对-----------------
        if ($order > 1 && in_array($order, array(2,3,4,5,6,7,8,9,10,11,12))) {
            $pre_order = ($order-1);//上一题目顺序号
            
            $key_pre_order = "key_Order:" . $roundid . '_' . $pre_order;
            $pre_question_id = $cache->get($key_pre_order);//上一题目id
            
            $pre_question_info = Answer::getQuestionInfo($roundid, $pre_question_id);//上一道题目信息
            $pre_correct_answer = strtoupper(trim($pre_question_info['correct_answer']));
            
            $key_pre_question_info = 'Set_DA:' . $roundid. '_' . $pre_question_id. '_' . $pre_correct_answer;
            
            if (!$cache->sIsmember($key_pre_question_info, $userid)) {//上一道题没答对
                if ($order != 12) {
                    if ($correct_answer == $answer) {//本道题答对的情况下，上一道题没答，自动使用复活卡
                        
                        //$key_round_rivival_key = "Rivival_DA:" . $roundid;//本场次复活卡使用集合
                        $frequency_time_redis_key = "riviva_da_" . $roundid .'_' . $userid;
                        //!$cache->sIsMember($key_round_rivival_key, $userid)
                        if (! ($cache->INCR($frequency_time_redis_key) >2) ) {//本场次还没有使用复活卡
                             $rivival_count = Counter::get(Counter::COUNTER_TYPE_ROUND_NUM, $userid);
                            if (isset($rivival_count) && $rivival_count > 0) {
                                if ($pre_order == 1) {
                                         $this->useRivivalCard($roundid, $pre_question_id, '空', $pre_correct_answer, $pre_order, $userid);
                                         $result_pre_key = 'Set_DA:' . $roundid. '_' . $pre_question_id. '_' . $pre_correct_answer;
                                         $ret = $cache->sadd($result_pre_key, $userid);
                                        
                                } else if ($pre_order != 1 && $pre_order > 1) {
                                    $pre_pre_order = ($pre_order-1);//上一题目的上一道题顺序号
                                        
                                    $key_pre_pre_order = "key_Order:" . $roundid . '_' . $pre_pre_order;
                                    $pre_pre_question_id = $cache->get($key_pre_pre_order);//上一题的上一题目id
                                        
                                    $pre_pre_question_info = Answer::getQuestionInfo($roundid, $pre_pre_question_id);//上一道题目信息
                                    $pre_pre_correct_answer = strtoupper(trim($pre_pre_question_info['correct_answer']));
                                        
                                    $key_pre_pre_question_info = 'Set_DA:' . $roundid. '_' . $pre_pre_question_id. '_' . $pre_pre_correct_answer;
                                        
                                    if (!$cache->sIsmember($key_pre_pre_question_info, $userid)) {
                                             $this->useRivivalCard($roundid, $pre_pre_question_id, '空', $pre_pre_correct_answer, $pre_pre_order, $userid);
                                             $result_pre_pre_key = 'Set_DA:' . $roundid. '_' . $pre_pre_question_id. '_' . $pre_pre_correct_answer;
                                             $ret = $cache->sadd($result_pre_pre_key, $userid);
                                    } else {
                                        Interceptor::ensureNotFalse(false, ERROR_SUMMIT_USER_IS_DIE_OUT);//已淘汰
                                    }
                                }
                            }
                        }
                        $cache->expire($frequency_time_redis_key, 172800);//两天有效期
    
                    } else {
                        Interceptor::ensureNotFalse(false, ERROR_SUMMIT_USER_IS_DIE_OUT);//已淘汰
                    }
                }
                
            }
        }
        
        if ($correct_answer != $answer ) {
            
            if ($order != 12) {
                //                 $key_round_key = "Rivival_DA:" . $roundid;//本场次复活卡使用集合
                //                 $bool = $cache->sIsMember($key_round_key, $userid);
                $frequency_time_redis_key = "riviva_da_" . $roundid .'_' . $userid;
                Logger::log("answer_log", "use rivival card", array("bool" => $bool, "userid" => $userid));
                //!$cache->sIsmember($key_round_key, $userid);
                if (!($cache->INCR($frequency_time_redis_key) > 2)) {
                    $rivival_count = Counter::get(Counter::COUNTER_TYPE_ROUND_NUM, $userid);
                    Logger::log("answer_log", "use rivival card", array("count" => $rivival_count, "userid" => $userid));
                    if (isset($rivival_count) && $rivival_count > 0) {
                        $this->useRivivalCard($roundid, $questionid, $answer, $correct_answer, $order, $userid);
                        $answer = $correct_answer;
                        $return_msg['use_rivival'] = 1;
                        $return_msg['is_correct'] = 1;
                    }
                }
                $cache->expire($frequency_time_redis_key, 172800);//两天有效期
            }
            
        } else {
            $return_msg['is_correct'] = 1;
        }
        //----------------------判断本题对错，如果错自动使用复活卡-----------------
        
        //----------------------添加答案集合-----------------
        if (in_array($answer, ['A', 'B', 'C', 'D'], true)) {
            $result_key = 'Set_DA:' . $roundid. '_' . $questionid. '_' . $answer;
            
            $ret = $cache->sadd($result_key, $userid);
        }
        //----------------------添加答案集合-----------------
        
        return $return_msg;
    }
    
    public function useRivivalCard($roundid, $questionid, $answer, $correct_answer, $order, $userid)
    {
        $cache = Cache::getInstance('REDIS_CONF_CACHE');
        $key_round_key = "Rivival_DA:" . $roundid;//本场次复活卡使用集合
        try {
            $bool = Counter::decrease(Counter::COUNTER_TYPE_ROUND_NUM, $userid, 1);
        } catch (Exception $e) {
            Logger::log("answer_log", "decrease rivival card", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
        }
        if ($bool) {//复活卡技术减一
            try {
                $ret = $cache->sadd($key_round_key, $userid);//加入本场次复活卡使用集合
            } catch (Exception $e) {
                Logger::log("answer_log", "set rivival card", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
            }
            
            include_once 'process_client/ProcessClient.php';
            $option = array(
            "userid"         => $userid,
            "questionid"     => $questionid,
            "roundid"         => $roundid,
            "addtime"         => date("Y-m-d H:i:s"),
            "answer"        => $answer,
            "correct_answer"=> $correct_answer,
            "order"            => $order,
                    
            );
            ProcessClient::getInstance("dream")->addTask("rivival_used_log", $option);
            
        }
    }
    
    public function getQuestionArray($roundid)
    {
        $cache = Cache::getInstance('REDIS_CONF_CACHE');
        
        $data = [];
        $act_bonus = json_decode($cache->get('act_match_bonus'), true);
        $data['matchinfo'] = $act_bonus;
        
        if ($act_bonus['is_end'] == '1') {
            $this->render($data);
        }
        
        $roundid = $act_bonus['roundid'];
        
        $all_question_order_keys = $cache->keys("key_Order:" . $roundid . '*');
        $count = count($all_question_order_keys);
        $gary = [];
        for ($i = 1; $i < ($count+1); $i++)
        {
            $k = "key_Order:" . $roundid . '_' . $i;
            $question_id = $cache->get($k);
            
            $value = json_decode($cache->get("key_AN:" . $roundid . '_' . $question_id), true);
            $value['questionid'] = $question_id;
            $value['redis_key'] = $k;
            $value['order'] = $i;
            $answer_array = ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0];
            $all_answer_keys  = $cache->keys("Set_DA:". $roundid . '_' .$question_id . '*');
            if (empty($all_answer_keys)) {
                $answer_array['A'] = 0;
                $answer_array['B'] = 0;
                $answer_array['C'] = 0;
                $answer_array['D'] = 0;
            } else {
                $answer_array['A'] = $cache->scard("Set_DA:". $roundid . '_' .$question_id . '_A');
                $answer_array['B'] = $cache->scard("Set_DA:". $roundid . '_' .$question_id . '_B');
                $answer_array['C'] = $cache->scard("Set_DA:". $roundid . '_' .$question_id . '_C');
                $answer_array['D'] = $cache->scard("Set_DA:". $roundid . '_' .$question_id . '_D');
            }
            
            $value['stats'] = $answer_array;
            $gary[] = $value;
        }
        $data['question'] = $gary;
        
        return $data;
    }
    
    
}