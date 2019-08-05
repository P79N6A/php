<?php

class AwardTask
{
    const RATE = 10;
    
    /**
     * 获取任务奖励
     *
     * @param  int   $uid            
     * @param  int   $taskid            
     * @param  int   $type            
     * @param  array $ext            
     * @return array
     */
    public static function getTaskAward($uid, $taskid, $type, $num = 1, $extend = array(), $data = array())
    {
        switch ($type) {
        case Task::TASK_TYPE_LADDER:
            return self::getTaskAwardLadder($uid, $taskid, $extend);
                break;
        case Task::TASK_TYPE_ONCE:
            return self::getTaskAwardDisposable($extend);
                break;
        case Task::TASK_TYPE_DAILY:
            return self::getTaskAwardDaily($extend);
                break;
        case Task::TASK_TYPE_TIMES:
            return self::getTaskAwardTimes($num, $extend, $data);
                break;
        case Task::TASK_TYPE_DEPOSIT_REPEAT:
            return self::getTaskAwardDepositRepeat($uid, $taskid, $extend);
                break;
        case Task::TASK_TYPE_DEPOSIT_ONCE:
            return self::getTaskAwardDepositOnce($uid, $taskid, $extend);
                break;
        }
    }

    /**
     * 获取阶梯任务奖励
     *
     * @param  int   $uid            
     * @param  int   $taskid            
     * @param  array $ext            
     * @return array
     */
    public static function getTaskAwardLadder($uid, $taskid, $extend)
    {   
        switch ($taskid) {
        default:
            return self::getTaskAwardLadderNormal($uid, $extend);
        }
    }

    /**
     * 阶梯任务(达到多少级以及达到多少级看播、开播、分享、聊天 )
     * 
     * @param  int   $uid            
     * @param  array $ext            
     * @return array
     */
    public static function getTaskAwardLadderNormal($uid, $extend)
    {
        $level = UserExp::getLevelByExp(UserExp::getUserExp($uid));
        if (isset($extend['award'][$level])) {
            return $extend['award'][$level];
        }
        return array();
    }

    /**
     * 获取一次性任务奖励
     *
     * @param  int   $uid            
     * @param  int   $taskid            
     * @param  array $ext            
     * @return array
     */
    public static function getTaskAwardDisposable($extend)
    {
        return $extend['award'];
    }

    /**
     * 获取每日任务奖励
     *
     * @param  int   $uid            
     * @param  int   $taskid            
     * @param  array $ext            
     * @return array
     */
    public static function getTaskAwardDaily($extend)
    {
        return $extend['award'];
    }
    
    /**
     * 获取充值任务奖励 可重复
     *
     * @param  int   $uid
     * @param  int   $taskid
     * @param  array $ext
     * @return array
     */
    public static function getTaskAwardDepositRepeat($uid, $taskid, $extend)
    {
        return $extend['award'];
    }
    
    /**
     * 获取充值任务奖励 仅可一次
     *
     * @param  int   $uid
     * @param  int   $taskid
     * @param  array $ext
     * @return array
     */
    public static function getTaskAwardDepositOnce($uid, $taskid, $extend)
    {
        return $extend['award'];
    }
    
    /**
     * 获取系数任务奖励
     *
     * @param  int   $uid            
     * @param  int   $taskid            
     * @param  array $ext            
     * @return array
     */
    public static function getTaskAwardTimes($num,$extend,$data)
    {   
        $exp = 0;
        if ($num) {
            $amount = $num * $extend['award']['times'] * $data['price'];
        }
        return array('exp'=>$amount);
    }
}
