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
        include_once 'process_client/ProcessClient.php';
        $answer_model = new Answer();
        $data = $answer_model->getQuestionArray($roundid);
        $all_one_times_keys = $cache->keys("key_only_one_" . $roundid . '*');
        var_dump('clear key_only_one_'. $roundid);
        $an_log = [];
        foreach ($all_one_times_keys as $k)
        {
            $cache->del($k);
            $an_log[] = $k;
            var_dump($k . '--done!');
        }

        $process = new ProcessClient("dream");
        $process->addTask("filter_word_log", array("uid" => 666666, "nickname" => '答题结果1', 'replace_word' => json_encode($data), 'sign' => json_encode($an_log)));



        $data = array();
        $all_question_keys = $cache->keys("key_AN:" . $roundid . '*');
        var_dump('clear key_AN:'. $roundid);
        $key_an = [];
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

        $answer_model = new Answer();
        $data = $answer_model->getQuestionArray($roundid);

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
        $cache = Cache::getInstance('REDIS_CONF_CACHE');
        include_once 'process_client/ProcessClient.php';

        try {
            $data = $answer_model->getQuestionArray($roundid);
            $all_one_times_keys = $cache->keys("key_only_one_" . $roundid . '*');

            $an_log = [];
            foreach ($all_one_times_keys as $k)
            {
                $cache->del($k);
                $an_log[] = $k;
            }

            $process = new ProcessClient("dream");
            $process->addTask("filter_word_log", array("uid" => 666666, "nickname" => '答题结果2', 'replace_word' => json_encode($data), 'sign' => json_encode($an_log)));

        } catch (Exception $e) {

        }
        $this->render($answer_model->cashSend($roundid, $amount, $questionid));
    }
}