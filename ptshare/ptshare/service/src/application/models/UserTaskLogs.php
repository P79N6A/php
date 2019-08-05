<?php
class UserTaskLogs
{

    /**
     * 添加任务奖励
     * @param int $uid
     * @param int $taskid
     * @param array $award
     */
    public static function AddUserTaskLogs($uid, $taskid, $award)
    {
        $DAOUserTaskLogs = new DAOUserTaskLogs($uid);
        return $DAOUserTaskLogs->addUserTaskLogs($uid, $taskid, $award);
    }

    /**
     * 修改订单
     * @param int $uid
     * @param int $logid
     * @param int $orderid
     * @return
     */
    public static function updateUserTaskLogs($uid, $logid, $orderid)
    {
        $DAOUserTaskLogs = new DAOUserTaskLogs($uid);
        return $DAOUserTaskLogs->updateUserTaskLogs($logid, $orderid);
    }

    /**
     * 获取完成任务列表
     * @param int $uid
     * @param int $num
     * @param int $offset
     * @return array
     */
    public static function getUserTaskList($uid, $num, $offset)
    {
        $taskTemp = $arrTemp = array();
        $DAOTask  = new DAOTask();
        $taskList = $DAOTask->getTaskList();
        foreach($taskList as $item){
            $taskTemp[$item['taskid']] = $item;
        }

        $DAOUserTaskLogs = new DAOUserTaskLogs($uid);
        $list  = $DAOUserTaskLogs->getUserTaskLogsList($uid, $num, $offset);
        foreach ($list as $key=>$val) {
            $arrTemp[$key]['taskid']  = $val['taskid'];
            $arrTemp[$key]['name']    = $taskTemp[$val['taskid']]['name'];
            $arrTemp[$key]['award']   = json_decode($val['award'],true);
            $arrTemp[$key]['addtime'] = $val['addtime'];

            $offset = $val['id'];
        }

        $total = $DAOUserTaskLogs->getUserTaskLogsTotal($uid);
        $more = (bool) $DAOUserTaskLogs->getMoreUserTaskLogs($uid, $offset);
        return array($arrTemp, $total, $offset, $more);
    }
}