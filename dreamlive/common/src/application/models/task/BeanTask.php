<?php
class BeanTask
{
    public function getTaskProfit($userid, $taskid)
    {
        /* {{{ */
        $task_info = DAOTask::getInstance()->getTaskInfo($taskid);
        
        $amount = 0;
        if ($task_info["extend"]) {
            $extend = json_decode($task_info["extend"], true);
            if ($extend["rand_amount"]) {
                if ($set = $extend["rand_amount"]["set"]) {
                    $amount = $set[array_rand($set)];
                } elseif ($extend["rand_amount"]["max"]) {
                    $amount = rand((int) $extend["rand_amount"]["min"], (int) $extend["rand_amount"]["max"]);
                }
            }
        }
        
        return $amount;
    } /* }}} */
}
