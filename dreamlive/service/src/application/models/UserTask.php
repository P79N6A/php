<?php
class UserTask
{
    public function getUserTask($userid, $taskid, $locked = false)
    {
        /* {{{ */
        $user_task_info = DAOUserTask::getInstance($userid)->getUserTask($taskid, $locked);
        return $this->_getRealUserTask($user_task_info);
    }
    /* }}} */
    private function _getRealUserTask($user_task_info)
    {
        /* {{{ */
        if ($user_task_info && (strtotime($user_task_info["modtime"]) < mktime(0, 0, 0))) {
            $user_task_info["daytimes"] = 0;
            $user_task_info["dayamount"] = 0;
        }
        return $user_task_info;
    }

        /* }}} */
    
    /**
     * 
     * 添加user_task
     * 
     *
     * @param int  $userid             
     * @param int  $taskid             
     * @param text $award            
     * @param int  $num             
     */
    public function setUserTask($userid, $taskid, $award, $num, $task_type)
    {
        $task_info = DAOTask::getInstance()->getTaskInfo($taskid);
        $user_task_info = $this->getUserTask($userid, $taskid, true);
        Logger::log("task_err", "userTask", [json_encode($user_task_info), $userid]);
        if ($user_task_info) {
            
            if ($task_type == Task::TASK_TYPE_DEPOSIT_ONCE) {
                Interceptor::ensureNotFalse(false, ERROR_BIZ_TASK_OVER_TIMES, $taskid);
            }
            
            if ($task_info["totallimit"]) {
                Interceptor::ensureNotFalse($user_task_info['totaltimes'] + $num <= $task_info['totallimit'], ERROR_BIZ_TASK_OVER_TIMES, $taskid);
            }
            
            if ($task_info["daylimit"]) {
                Interceptor::ensureNotFalse($user_task_info['daytimes'] + $num <= $task_info['daylimit'], ERROR_BIZ_TASK_OVER_TIMES, $taskid);
            }
        }
        Logger::log("task_err", "userTask", [json_encode($user_task_info)]);
        if (empty($user_task_info)) {
            $user_task_info = array(
                "uid" => $userid,
                "taskid" => $taskid,
                "awardext" => json_encode($award),
                "backupext" => json_encode($award),
                "totaltimes" => $num,
                "daytimes" => $num
            );
            DAOUserTask::getInstance($userid)->addUserTask($user_task_info);
            
        } else {
            $backupext = json_decode($user_task_info['backupext'], true);
            if (isset($award['exp'])) {
                $backupext['exp'] = $backupext['exp'] + $award['exp'];
            }
            if (isset($award['starlight'])) {
                $backupext['starlight'] = $backupext['starlight'] + $award['starlight'];
            }
            if (isset($award['diamonds'])) {
                $backupext['diamonds'] = $backupext['diamonds'] + $award['diamonds'];
            }
            $user_task_info["awardext"] = json_encode($award);
            $user_task_info["backupext"] = json_encode($backupext);
            $user_task_info["totaltimes"] += $num;
            $user_task_info["daytimes"] += $num;
            DAOUserTask::getInstance($userid)->modUserTask($taskid, $user_task_info);
            
        }
        
        return $user_task_info;
    }
}
