<?php
class ShareController extends BaseController
{
    /**
     * 获取分享url
     */
    public function getShareUrlAction()
    {
        $userid = Context::get("userid");
        $taskid = $this->getParam("taskid", '');
        $type   = $this->getParam("type", '');

        Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");
        Interceptor::ensureNotFalse(is_numeric($taskid) && $taskid > 0, ERROR_PARAM_INVALID_FORMAT, "taskid");

        list($callback,$callbackUrl,$action,$param) = Share::getShareUrl($userid, $taskid, $type);

        $this->render(array('callback'=>$callback,'callbackUrl'=>$callbackUrl,'action'=>$action,'param'=>$param));
    }

    public function inviteNewUserSuccessAction()
    {
        $userid = Context::get("userid");
        $uid    = $this->getParam("uid", '');
        $taskid = $this->getParam("taskid", '');
        $type   = $this->getParam("type", '');

        Interceptor::ensureNotFalse(is_numeric($uid) && $uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");
        Interceptor::ensureNotFalse(is_numeric($taskid) && $taskid > 0, ERROR_PARAM_INVALID_FORMAT, "taskid");

        // 执行首次分享好友任务
        try {
            Task::execute($userid, 2, 1);
        } catch (Exception $e) {}


        $this->render();
    }
}