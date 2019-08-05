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
}
?>
