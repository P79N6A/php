<?php
class ReplyController extends BaseController
{
    public function addAction()
    {
        /* {{{ */
        $pid = (int) $this->getParam("pid");
        $qid = (int) $this->getParam("qid");
        $content = trim(strip_tags($this->getParam("content")));
        $type = trim(strip_tags($this->getParam("type")));
        
        $user_info = User::getUserInfo(Context::get("userid"));
        
        Interceptor::ensureNotFalse(! empty($user_info), ERROR_USER_NOT_EXIST);
        
        Interceptor::ensureNotFalse($pid > 0, ERROR_PARAM_INVALID_FORMAT, "pid");
        Interceptor::ensureNotEmpty($content, ERROR_PARAM_IS_EMPTY, "content");
        ! empty($qid) && Interceptor::ensureNotFalse($qid > 0, ERROR_PARAM_INVALID_FORMAT, "qid");
        
        $reply = new Reply();
        $reply_info = $reply->addReply($pid, $qid, $content, $type);
        
        $this->render($reply_info);
    }/* }}} */
    public function getRepliesAction()
    {
        /* {{{ */
        $pid = (int) $this->getParam("pid");
        $offset = (int) $this->getParam("offset");
        $num = (int) $this->getParam("num", 20);
        $direct = (int) $this->getParam("direct");
        $type = trim(strip_tags($this->getParam("type")));
        
        Interceptor::ensureNotFalse($pid > 0, ERROR_PARAM_INVALID_FORMAT, "pid");
        Interceptor::ensureNotFalse(
            in_array(
                $direct, array(
                Reply::LOADING_FORWARD,
                Reply::LOADING_BACKWARD
                )
            ), ERROR_PARAM_INVALID_FORMAT, "direct"
        );
        
        $reply = new Reply();
        list ($total, $replies) = $reply->getReplies($pid, $offset, $num, $direct);
        
        $data = array(
            "replies" => $replies,
            "total" => $total,
            "more" => count($replies) >= $num ? true : false
        );
        
        $this->render($data);
    }/* }}} */
    public function deleteAction()
    {
        /* {{{ */
        $pid = $this->getPost("pid") ? intval($this->getPost("pid")) : 0;
        $rid = $this->getPost("rid") ? intval($this->getPost("rid")) : 0;
        
        Interceptor::ensureNotFalse($pid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "pid");
        Interceptor::ensureNotFalse($rid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "rid");
        
        $user_info = User::getUserInfo(Context::get("userid"));
        
        Interceptor::ensureNotFalse(! empty($user_info), ERROR_USER_NOT_EXIST);
        
        $reply = new Reply();
        $reply->delReply($pid, $rid);
        
        $this->render(
            array(
            "rid" => $rid
            )
        );
    }/* }}} */
    public function reportAction()
    {
        /* {{{ */
        $pid = $this->getParam("pid") ? intval($this->getParam("pid")) : 0;
        $rid = $this->getParam("rid") ? intval($this->getParam("rid")) : 0;
        $uid = Context::get("userid");
        $deviceid = Context::get("deviceid");
        
        $user_info = User::getUserInfo(Context::get("userid"));
        
        Interceptor::ensureNotFalse(! empty($user_info), ERROR_USER_NOT_EXIST);
        
        Interceptor::ensureNotFalse($pid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "pid");
        Interceptor::ensureNotFalse($rid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "rid");
        
        $reply = new Reply();
        $reply->report($pid, $rid);
        
        $this->render();
    } /* }}} */
}
