<?php
class RateTask
{
    const DEFAULT_RATE = 1;

    public function getTaskProfit($userid, $taskid, $num, $data)
    {
        /* {{{ */
        $task_info = DAOTask::getInstance()->getTaskInfo($taskid);
        
        $amount = 0;
        if ($num) {
            $rate = self::DEFAULT_RATE;
            
            if ($task_info["extend"]) {
                $extend = json_decode($task_info["extend"], true);
                if ($extend["rate"]) {
                    $rate = (int) $extend["rate"];
                }
            }
            
            $amount = $num * $rate;
        }
        
        return $amount;
    } /* }}} */
}
