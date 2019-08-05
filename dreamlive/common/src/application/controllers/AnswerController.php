<?php

class AnswerController extends  BaseController
{
    
    /**
     * 提交答案
     */
    public function submitAction()
    {
        $liveid      = $this->getParam("liveid")          ? intval($this->getParam('liveid')) : 0;
        $roundid    = $this->getParam("roundid")          ? intval($this->getParam('roundid')) : 0;//场次id
        $questionid = $this->getParam("questionid")      ? intval($this->getParam('questionid')) : 0;//题目id
        $order        = $this->getParam("order")          ? intval($this->getParam('order')) : 0;//顺序id
        $answer     = $this->getParam("answer")          ? trim($this->getParam('answer')) : '';
        $userid      = Context::get("userid");
        
        
        Interceptor::ensureNotFalse(is_numeric($liveid) && $liveid > 0, ERROR_PARAM_INVALID_FORMAT, 'liveid');
        Interceptor::ensureNotFalse(is_numeric($roundid) && $roundid > 0, ERROR_PARAM_INVALID_FORMAT, 'roundid');
        Interceptor::ensureNotFalse(is_numeric($questionid) && $questionid > 0, ERROR_PARAM_INVALID_FORMAT, 'questionid');
        Interceptor::ensureNotFalse(is_numeric($order) && $order > 0, ERROR_PARAM_INVALID_FORMAT, 'order');
        //Interceptor::ensureNotFalse(in_array($answer, array("A","B","C","D"), true), ERROR_PARAM_INVALID_FORMAT, "answer");
        Interceptor::ensureNotEmpty($answer, ERROR_PARAM_IS_EMPTY, 'answer');
        
        $answer_model = new Answer();
        $data = $answer_model->submit($roundid, $questionid, $answer, $order, $userid);
        
        $this->render($data);
    }
    
    /**
     * 获取胜利者
     */
    public function getWinnersAction()
    {
        $roundid    = $this->getParam("roundid")      ? intval($this->getParam('roundid')) : 0;//场次id
        
        Interceptor::ensureNotFalse(is_numeric($roundid) && $roundid > 0, ERROR_PARAM_INVALID_FORMAT, 'roundid');
        $answer_model = new Answer();
        $winner_list = $answer_model->getWinners($roundid);
        if (empty($winner_list)) {
            $winner_list = [];
        }
        $this->render(array('list' => $winner_list));
    }
    
    /**
     * 更新场次信息
     */
    public function setRoundAction()
    {
        $roundid    = $this->getParam("roundid")          ? intval($this->getParam('roundid')) : 0;//场次id
        $amount     = $this->getParam("amount")          ? trim($this->getParam('amount')) : '';
        $startime    = $this->getParam("startime")          ? trim($this->getParam('startime')) : '';
        $title      = $this->getParam("title")          ? trim($this->getParam('title')) : '';
        
        Interceptor::ensureNotFalse(is_numeric($roundid) && $roundid > 0, ERROR_PARAM_INVALID_FORMAT, 'roundid');
        Interceptor::ensureNotFalse($amount > 0, ERROR_PARAM_INVALID_FORMAT, 'amount');
        Interceptor::ensureNotEmpty($title, ERROR_PARAM_IS_EMPTY, 'title');
        Interceptor::ensureNotEmpty($amount, ERROR_PARAM_IS_EMPTY, 'amount');
        Interceptor::ensureNotEmpty($startime, ERROR_PARAM_IS_EMPTY, 'startime');
        //Interceptor::ensureNotFalse(Util::valid_date($startime), ERROR_PARAM_INVALID_FORMAT, 'startime');
        
        $answer_model = new Answer();
        
        $this->render($answer_model->setRoundToCache($roundid, $title, $amount, $startime));
    }

    /**
     * 发布题目
     */
    public function publishAction()
    {
        $roundid    = $this->getParam("roundid")          ? intval($this->getParam('roundid')) : 0;//场次id
        $questionid = $this->getParam("questionid")      ? intval($this->getParam('questionid')) : 0;//题目id
        $order        = $this->getParam("order")          ? intval($this->getParam('order')) : 0;//顺序id
        $answer     = $this->getParam("answer")          ? trim($this->getParam('answer')) : '';
        $title        = $this->getParam("title")          ? trim($this->getParam('title')) : '';
        $options    = $this->getParam("options")          ? trim($this->getParam('options')) : '';
        $duration    = $this->getParam("duration")          ? intval($this->getParam('duration')) : 0;//答题时间秒
        
        Interceptor::ensureNotFalse(is_numeric($roundid) && $roundid > 0, ERROR_PARAM_INVALID_FORMAT, 'roundid');
        Interceptor::ensureNotFalse(is_numeric($questionid) && $questionid > 0, ERROR_PARAM_INVALID_FORMAT, 'questionid');
        Interceptor::ensureNotFalse(is_numeric($order) && $order > 0, ERROR_PARAM_INVALID_FORMAT, 'order');
        Interceptor::ensureNotEmpty($answer, ERROR_PARAM_IS_EMPTY, 'answer');
        Interceptor::ensureNotEmpty($options, ERROR_PARAM_IS_EMPTY, 'options');
        Interceptor::ensureNotEmpty($duration, ERROR_PARAM_IS_EMPTY, '答题时长duration');
        
        $answer_model = new Answer();

        $this->render($answer_model->publish($roundid, $questionid, $answer, $order, $duration, $title));
    }
    
    
    public function clearCacheAction()
    {
        $roundid    = $this->getParam("roundid")          ? intval($this->getParam('roundid')) : 0;//场次id
        
        Interceptor::ensureNotFalse(is_numeric($roundid) && $roundid > 0, ERROR_PARAM_INVALID_FORMAT, 'roundid');
        
        $cache = Cache::getInstance('REDIS_CONF_CACHE');
        
        $all_question_keys = $cache->keys("key_AN:" . $roundid . '*');
        var_dump('clear key_AN:'. $roundid);
        foreach ($all_question_keys as $k)
        {
            $cache->del($k);
            var_dump($k . '--done!');
        }
        
        $all_question_order_keys = $cache->keys("key_Order:" . $roundid . '*');
        var_dump('clear key_Order:'. $roundid);
        foreach ($all_question_order_keys as $k)
        {
            $cache->del($k);
            var_dump($k . '--done!');
        }
        
        $all_one_times_keys = $cache->keys("key_only_one_" . $roundid . '*');
        var_dump('clear key_only_one_'. $roundid);
        foreach ($all_one_times_keys as $k)
        {
            $cache->del($k);
            var_dump($k . '--done!');
        }
        
        $all_answer_keys  = $cache->keys("Set_DA:". $roundid . '*');
        var_dump('clear Set_DA:'. $roundid);
        foreach ($all_answer_keys as $k)
        {
            $cache->del($k);
            var_dump($k . '--done!');
        }
        
        $all_rivival_keys = $cache->keys("Rivival_DA:". $roundid . '*');
        var_dump('clear Rivival_DA:'. $roundid);
        foreach ($all_rivival_keys as $k)
        {
            $cache->del($k);
            var_dump($k . '--done!');
        }
        
        $this->render();
    }
    
    /**
     * 获取答题缓存 后台
     */
    public function getQuestionAction()
    {
        $roundid    = $this->getParam("roundid")          ? intval($this->getParam('roundid')) : 0;//场次id

        Interceptor::ensureNotFalse(is_numeric($roundid) && $roundid > 0, ERROR_PARAM_INVALID_FORMAT, 'roundid');
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
        
        $this->render($data);
    }
    
    /**
     * 获取某个人的答题轨迹 后台
     */
    public function getUserLogsAction()
    {
        $roundid    = $this->getParam("roundid")          ? intval($this->getParam('roundid')) : 0;//场次id
        $userid     = $this->getParam("userid")      ? intval($this->getParam('userid')) : 0;//用户id
        
        Interceptor::ensureNotFalse(is_numeric($roundid) && $roundid > 0, ERROR_PARAM_INVALID_FORMAT, 'roundid');
        Interceptor::ensureNotFalse(is_numeric($userid) && $userid> 0, ERROR_PARAM_INVALID_FORMAT, 'userid');
    }
    
    /**
     * 获取答题日志统计  后台
     */
    public function getQuestionStatsAction()
    {
        $roundid    = $this->getParam("roundid")          ? intval($this->getParam('roundid')) : 0;//场次id
        $questionid = $this->getParam("questionid")      ? intval($this->getParam('questionid')) : 0;//题目id
        
        Interceptor::ensureNotFalse(is_numeric($roundid) && $roundid > 0, ERROR_PARAM_INVALID_FORMAT, 'roundid');
        Interceptor::ensureNotFalse(is_numeric($questionid) && $questionid > 0, ERROR_PARAM_INVALID_FORMAT, 'questionid');
        
        $cache = Cache::getInstance('REDIS_CONF_CACHE');
        $all_one_times_keys = $cache->keys("key_only_one_" . $roundid . '_'.$questionid .'*');
        
        $this->render($all_one_times_keys);
    }
    
    /**
     * 获取选项日志统计  后台
     */
    public function getAnswerStatsAction()
    {
        $roundid    = $this->getParam("roundid")          ? intval($this->getParam('roundid')) : 0;//场次id
        $questionid = $this->getParam("questionid")      ? intval($this->getParam('questionid')) : 0;//题目id
        $answer     = $this->getParam("answer")          ? trim($this->getParam('answer')) : '';
        
        Interceptor::ensureNotFalse(is_numeric($roundid) && $roundid > 0, ERROR_PARAM_INVALID_FORMAT, 'roundid');
        Interceptor::ensureNotFalse(is_numeric($questionid) && $questionid > 0, ERROR_PARAM_INVALID_FORMAT, 'questionid');
        Interceptor::ensureNotFalse(in_array($answer, array("A","B","C"), true), ERROR_PARAM_INVALID_FORMAT, "answer");
        Interceptor::ensureNotEmpty($answer, ERROR_PARAM_IS_EMPTY, 'answer');
        
        $cache = Cache::getInstance('REDIS_CONF_CACHE');
        $answer_logs = $cache->sMembers("Set_DA:" . $roundid . '_'.$questionid .'_'. $answer);
        
        $this->render($answer_logs);
    }
    
    /**
     * 发现金 后台
     */
    public function cashSendAction()
    {
        $roundid    = $this->getParam("roundid")          ? intval($this->getParam('roundid')) : 0;//场次id
        $questionid = $this->getParam("questionid")      ? intval($this->getParam('questionid')) : 0;//题目id
        $amount     = $this->getParam("amount")          ? trim($this->getParam('amount')) : '';
        
        Interceptor::ensureNotFalse(is_numeric($roundid) && $roundid > 0, ERROR_PARAM_INVALID_FORMAT, 'roundid');
        Interceptor::ensureNotFalse(is_numeric($questionid) && $questionid > 0, ERROR_PARAM_INVALID_FORMAT, 'questionid');
        Interceptor::ensureNotFalse($amount > 0, ERROR_PARAM_INVALID_FORMAT, 'amount');
        Interceptor::ensureNotEmpty($amount, ERROR_PARAM_IS_EMPTY, 'amount');
        
        $answer_model = new Answer();
        
        $this->render($answer_model->cashSend($roundid, $amount, $questionid));
    }
}