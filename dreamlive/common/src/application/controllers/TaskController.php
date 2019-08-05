<?php
class TaskController extends BaseController
{
    public function executeAction()
    {
        /* {{{ */
        $uid    = Context::get("userid");
        $taskid = $params["taskid"] = $this->getParam("taskid", 0);
        $ticket = $params["ticket"] = trim($this->getParam("ticket"));

        $ext    = $params["ext"] = $this->getParam("ext", "[]");

        $ticketList = explode('_', $ticket);
        $time = $params['time'] = $ticketList[1];

        Logger::log("task", "task execute params", $params);

        //Interceptor::ensureNotFalse(in_array($taskid, array(2,3)), ERROR_PARAM_INVALID_FORMAT, "taskid");
        //Interceptor::ensureNotFalse(is_numeric($taskid) && ! preg_match("/\./", $taskid) && $taskid > 0, ERROR_PARAM_INVALID_FORMAT, "taskid");
        Interceptor::ensureNotFalse(is_numeric($uid) && $uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        //Interceptor::ensureNotEmpty($ticket, ERROR_PARAM_IS_EMPTY, "ticket");
        //Task::checkTicket($ticket,$uid,$taskid,$time);

        $task = new Task();
        $res = $task->execute($uid, $taskid, 1);

        Logger::log("task", "task execute succ", $params);

        $this->render($res);
    }/* }}} */
    
    public function executeNewsAction()
    {
        /* {{{ */
        $uid    = Context::get("userid");
        $taskid = $params["taskid"] = $this->getParam("taskid", 0);
        $ticket = $params["ticket"] = trim($this->getParam("ticket"));
        
        $ext    = $params["ext"] = $this->getParam("ext", "[]");
        
        $ticketList = explode('_', $ticket);
        $time = $params['time'] = $ticketList[1];

        Logger::log("task", "task execute params", $params);
        
        Interceptor::ensureNotFalse(is_numeric($uid) && $uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotEmpty($ticket, ERROR_PARAM_IS_EMPTY, "ticket");
        //Task::checkTicket($ticket,$uid,$taskid,$time);
        
        $task = new Task();
        $res = $task->execute($uid, $taskid, 1, $ext);
        
        Logger::log("task", "task execute succ", $params);
        
        $this->render($res);
    }/* }}} */
    
    
    public function incrUserLevelAction()
    {
        /* {{{ */
        $uid = $this->getParam("uid", 0);
        $num = $this->getParam("num", 0);
        $reason = trim($this->getParam("reason"));
        
        Interceptor::ensureNotFalse(is_numeric($uid) && $uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotFalse(is_numeric($num) && $num > 0, ERROR_PARAM_INVALID_FORMAT, "num");
        
        $exp = UserExp::getUserExp($uid);
        $level = UserExp::getLevelByExp($exp);
        $newexp = UserExp::getExpByLevel($level + $num);
        
        UserExp::addUserExp($uid, $newexp - $exp);
        
        $user_info = User::getUserInfo($uid, true);
        
        $dao_tasklog = new DAOTasklog();
        $dao_tasklog->addTasklog($uid, 0, $newexp - $exp, $user_info["exp"], $reason);

        $user = new User();
        $user->reload($uid);
        
        $this->render();
    }/* }}} */
    public function incrUserExpAction()
    {
        /* {{{ */
        $uid = $this->getParam("uid", 0);
        $num = $this->getParam("num", 0);
        $reason = trim($this->getParam("reason"));
        
        Interceptor::ensureNotFalse(is_numeric($uid) && $uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotFalse(is_numeric($num) && $num > 0, ERROR_PARAM_INVALID_FORMAT, "num");
        
        UserExp::addUserExp($uid, $num);
        
        $user_info = User::getUserInfo($uid, true);
        
        $dao_tasklog = new DAOTasklog();
        $dao_tasklog->addTasklog($uid, 0, $num, $user_info["exp"], $reason);

        $user = new User();
        $user->reload($uid);
        
        $this->render();
    } /* }}} */
    
    /**
     * 获取用户任务列表
     */
    public function getTaskListAction()
    {
        $uid     = Context::get("userid");
        $type    = $this->getParam("type") ? $this->getParam("type") : Task::TASK_TYPE_ONCE;
        Interceptor::ensureNotFalse(is_numeric($uid) && $uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        
        $task = new Task();
        $result = $task->getTaskList($uid, $type);
        $this->render($result);
    }
    
    /**
     * 获取签到任务列表
     */
    public function getSignListAction()
    {
        $uid     = Context::get("userid");
        Interceptor::ensureNotFalse(is_numeric($uid) && $uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        
        $task   = new Task();
        $result = $task->getSignList($uid);
        $this->render($result);
    }
    
    /**
     * 签到
     */
    public function signTaskAction()
    {
        $uid     = Context::get("userid");
        Interceptor::ensureNotFalse(is_numeric($uid) && $uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        
        $task   = new Task();
        $result = $task->signTask($uid);
        
        $this->render();
    }
    
    /**
     * 等级任务
     */
    public function levelTaskAction()
    {
        $uid     = Context::get("userid");
        $level   = $this->getParam("level");

        Interceptor::ensureNotFalse(is_numeric($uid) && $uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotFalse($level && $level > 0, ERROR_PARAM_INVALID_FORMAT, "level");
        
        $task   = new Task();
        $result = $task->levelTask($uid, $level);
        
        $this->render($result);
    }

    //-----------------------后台----------------------
    public function adminUpdateTaskAction()
    {
        $taskid     = intval($this->getParam("taskid"));
        $name       = trim($this->getParam("name"));
        $active     = trim($this->getParam("active"));
        $totallimit = intval($this->getParam("totallimit"));
        $daylimit   = intval($this->getParam("daylimit"));
        $extend     = trim($this->getParam("extend"));
        $type       = intval($this->getParam("type"));
        $status     = trim($this->getParam("status"));

        $task   = new Task();
        $result = $task->adminUpdateTask($taskid, $name, $active, $totallimit, $daylimit, $extend, $type, $status);
        
        $this->render();
    }
}
?>
