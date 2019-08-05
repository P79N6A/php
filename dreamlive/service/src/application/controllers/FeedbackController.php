<?php
class FeedbackController extends BaseController
{
    const MAX_TIMES = 5;
    const EXPIRE_TIMES = 60;

    public function addAction()
    {
        /* {{{ */
        $content = trim(strip_tags($this->getParam('content')));

        Interceptor::ensureNotEmpty($content,    ERROR_PARAM_IS_EMPTY, "content");

        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key = "feedback:uid:". Context::get("userid");

        if (false !== ($times = $cache->incr($key))) {
            if ($times == 1) {
                $cache->expire($key, self::EXPIRE_TIMES);
            }

            Interceptor::ensureNotFalse($times <= self::MAX_TIMES,    ERROR_BIZ_PASSPORT_FEEDBACK_TOO_OFTEN);
        }

        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask(
            "sync_feedback_add", array(
            "content" => $content,
            "uid" => Context::get("userid")
            )
        );

        $this->render();
    } /* }}} */

    public function getHelpListAction()
    {
        $help = new DAOHelps();
        $helpList = $help->getHelpList();

        Interceptor::ensureNotEmpty($helpList, ERROR_BIZ_PASSPORT_FEEDBACK_HELP_NOT_FOUND);

        $this->render(array('help'=>$helpList, 'offset'=>0));
    }

    public function getHelpInfoAction()
    {
        $id = intval($this->getParam('id'));

        Interceptor::ensureNotEmpty($id, ERROR_PARAM_IS_EMPTY, "id");

        $help = new DAOHelps();
        $helpInfo = $help->getHelpInfo($id);

        Interceptor::ensureNotEmpty($helpInfo, ERROR_BIZ_PASSPORT_FEEDBACK_HELP_NOT_FOUND);

        $this->render($helpInfo);
    }

    public function adminAddfeedbackHelpsAction()
    {
        $title   = trim(strip_tags($this->getParam('title')));
        $content = trim(strip_tags($this->getParam('content'), "<br>"));
        $rank    = intval($this->getParam('rank'));

        Interceptor::ensureNotEmpty($title, ERROR_PARAM_IS_EMPTY, "title");
        Interceptor::ensureNotEmpty($content, ERROR_PARAM_IS_EMPTY, "content");
        Interceptor::ensureNotEmpty($rank, ERROR_PARAM_IS_EMPTY, "rank");

        $help = new DAOHelps();

        Interceptor::ensureFalse($help->exist($rank), ERROR_BIZ_PASSPORT_FEEDBACK_RANK_EXIST);

        $help->addHelp($title, $content, $rank);
        
        $this->render();
    }

    public function adminSetfeedbackHelpsAction()
    {
        $id      = intval($this->getParam('id'));
        $title   = trim(strip_tags($this->getParam('title')));
        $content = trim(strip_tags($this->getParam('content'), "<br>"));
        $rank    = intval($this->getParam('rank'));

        Interceptor::ensureNotEmpty($id, ERROR_PARAM_IS_EMPTY, "id");
        Interceptor::ensureNotEmpty($title, ERROR_PARAM_IS_EMPTY, "title");
        Interceptor::ensureNotEmpty($content, ERROR_PARAM_IS_EMPTY, "content");
        Interceptor::ensureNotEmpty($rank, ERROR_PARAM_IS_EMPTY, "rank");

        $help = new DAOHelps();

        Interceptor::ensureFalse($help->exist($rank, $id), ERROR_BIZ_PASSPORT_FEEDBACK_RANK_EXIST);

        $help->updateHelp($id, $title, $content, $rank);
        
        $this->render();
    }

    public function adminDelfeedbackHelpsAction()
    {
        $id      = intval($this->getParam('id'));

        Interceptor::ensureNotEmpty($id, ERROR_PARAM_IS_EMPTY, "id");

        $help = new DAOHelps();
        $help->delHelp($id);
        
        $this->render();
    }
}
?>
