<?php
class UserTask{

    /**
     * 获取用户任务
     * @param int $userid
     * @param int $taskid
     * @param boolean $locked
     * @return number
     */
    public static function getUserTask($userid, $taskid, $locked = false){
        $user_task_info = DAOUserTask::getInstance($userid)->getUserTask($taskid, $locked);
        return self::getRealUserTask($user_task_info);
    }

    /**
     * 用户任务数据处理
     * @param unknown $user_task_info
     * @return number
     */
    private static function getRealUserTask($user_task_info){
        if ($user_task_info && (strtotime($user_task_info["modtime"]) < mktime(0, 0, 0))) {
            $user_task_info["daytimes"]  = 0;
        }
        return $user_task_info;
    }

    /**
     * 添加user_task
     * @param int $userid
     * @param int $taskid
     * @param text $award
     * @param int $num
     */
    public static function setUserTask($userid, $taskid, $num, $userTaskInfo)
    {
        if (empty($userTaskInfo)) {
            DAOUserTask::getInstance($userid)->addUserTask($userid, $taskid, $num);
        } else {
            $userTaskInfo["totaltimes"] += $num;
            $userTaskInfo["daytimes"]   += $num;
            DAOUserTask::getInstance($userid)->modUserTask($taskid, $userTaskInfo);
        }
        return $userTaskInfo;
    }
}
