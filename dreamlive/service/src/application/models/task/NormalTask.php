<?php
class NormalTask
{
    public function getTaskProfit($userid, $taskid)
    {
        /* {{{ */
        $task_info = DAOTask::getInstance()->getTaskInfo($taskid);
        return $task_info["amount"];
    } /* }}} */
}
