<?php
class TaskController extends BaseController
{
    /**
     * 执行任务主方法
     */
    public function executeAction()
    {
        $userid  = Context::get("userid");
        $taskid  = $this->getParam("taskid", 0);
        $ext     = $this->getParam("ext", "[]");

        Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");

        $res = Task::execute($userid, $taskid, 1, $ext);

        $this->render($res);
    }

    /**
     * 获取任务列表
     */
    public function getTaskListAction()
    {
        //$userid  = Context::get("userid");
        $token = trim($this->getCookie("token"));
        $userid  = Session::getLoginId($token);

        //Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");

        $result = Task::getTaskList($userid);

        $this->render(array('list'=>$result));
    }

    /**
     * 获取完成任务列表
     */
    public function getUserTaskListAction()
    {
        $offset  = $this->getParam("offset")  ? (int)($this->getParam("offset")) : 0;
        $num     = $this->getParam("num")     ? intval($this->getParam("num"))   : 20;
        $userid  = Context::get("userid");

        Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");

        list($list, $total, $offset, $more) = UserTaskLogs::getUserTaskList($userid, $num, $offset);

        $this->render(array('list'=>$list,'total'=>$total,'offset'=>$offset,'more'=>$more));
    }

    /**
     * 获取关注奖励人
     */
    public function getPatronsAction()
    {
        $uid = Context::get("userid");
        $num = $this->getParam("num") ? intval($this->getParam("num")) : 5;

        Interceptor::ensureNotFalse(is_numeric($uid) && $uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotFalse(is_numeric($num) && $num > 0, ERROR_PARAM_INVALID_FORMAT, "num");

        $patrons = Attract::getPatrons($uid, $num);

        $this->render(array("users"=>$patrons));
    }
}
?>
