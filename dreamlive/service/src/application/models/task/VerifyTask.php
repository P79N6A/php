<?php
class VerifyTask
{
    // 阶梯任务
    const TASK_LADDER_LEVER_ID         = 8;// 达到多少级taskid
    const TASK_LADDER_SIGN_ID          = 7;// 签到任务taskid
    const TASK_LADDER_WATCH_ID         = 2;// 看播任务taskid
    const TASK_LADDER_LIVE_ID          = 3;// 开播任务taskid
    const TASK_LADDER_COMMENT_ID       = 4;// 评论任务taskid
    const TASK_LADDER_SHARE_ID         = 5;// 分享任务taskid
    
    // 一次性任务
    const TASK_ONCE_PERSONAL_DATA_ID   = 9;// 完善个人资料任务taskid
    const TASK_ONCE_BIND_MOBILE_ID     = 10;// 绑定电话任务taskid
    const TASK_ONCE_PERSONAL_ID        = 11;// 个人认证任务taskid
    const TASK_ONCE_SCHOOL_ID          = 12;// 学生认证任务taskid
    const TASK_ONCE_FIRST_RECHARGE_ID  = 13;// 首次充值任务taskid
    const TASK_ONCE_FOLLOW_TIMES_ID    = 14;// 关注主播数任务taskid
    const TASK_ONCE_WATCH_TIMES_ID     = 15;// 观看直播分钟数任务taskid
    
    // 日常任务
    const TASK_DAILY_FOLLOW_TIMES_ID   = 16;// 关注主播数
    const TASK_DAILY_BARRAGEB_TIMES_ID = 17;// 发送弹幕数
    const TASK_DAILY_RECHARGR_ID       = 18;// 充值
    const TASK_DAILY_WATCH_TIMES_ID    = 19;// 观看直播10分钟
    const TASK_DAILY_WATCH_LIVE_ID     = 20;// 观看直播直播数
    
    // 系数任务
    const TASK_TIIMES_GIFT_ID          = 1;// 发礼物
    const TASK_TIIMES_PROP_ID          = 6;// 飞屏
    const TASK_TIIMES_GUARD_ID         = 21;// 购买守护
    
    /**
     * 根据分类完验证任务条件
     * 
     * @param int   $uid            
     * @param int   $taskid            
     * @param int   $type            
     * @param array $extend            
     */
    public static function verifyCondition($uid, $taskid, $type, $extend)
    {
        switch ($type) {
        case Task::TASK_TYPE_LADDER:
            return self::verifyConditionLadder($uid, $taskid, $extend);
                break;
        case Task::TASK_TYPE_ONCE:
            return self::verifyConditionOnce($uid, $taskid, $extend);
                break;
        case Task::TASK_TYPE_DAILY:
            return self::verifyConditionDaily($uid, $taskid, $extend);
                break;
        case Task::TASK_TYPE_TIMES:
            return self::verifyConditionTimes($uid, $taskid, $extend);
                break;
        case Task::TASK_TYPE_DEPOSIT_REPEAT:
            return self::verifyConditionDepositRepeat($uid, $taskid, $extend);
                break;
        case Task::TASK_TYPE_DEPOSIT_ONCE:
            return self::verifyConditionDepositOnce($uid, $taskid, $extend);
                break;
        }
    }

    /**
     * 阶梯性任务验证
     * 
     * @param int $uid            
     * @param int $taskid            
     * @param int $extend            
     */
    public static function verifyConditionLadder($uid, $taskid, $extend)
    {
        return true;
    }

    /**
     * 一次性任务验证
     * 
     * @param int $uid            
     * @param int $taskid            
     * @param int $extend            
     */
    public static function verifyConditionOnce($uid, $taskid, $extend)
    {
        switch ($taskid) {
        case self::TASK_ONCE_PERSONAL_DATA_ID:
            return self::verifyOncePersionData($uid, $extend);
                break;
        case self::TASK_ONCE_BIND_MOBILE_ID:
            return self::verifyOnceBindMobile($uid, $extend);
                break;
        case self::TASK_ONCE_PERSONAL_ID:
            return self::verifyOncePersion($uid, $extend);
                break;
        case self::TASK_ONCE_SCHOOL_ID:
            return self::verifyOnceSchool($uid, $extend);
                break;
        case self::TASK_ONCE_FIRST_RECHARGE_ID:
            return self::verifyOnceFirstRecharge($uid, $extend);
                break;
        case self::TASK_ONCE_FOLLOW_TIMES_ID:
            return self::verifyOnceFollowTimes($uid, $extend);
                break;
        case self::TASK_ONCE_WATCH_TIMES_ID:
            return self::verifyOnceWatchTimes($uid, $extend);
                break;
        }
    }
    
    /**
     * 充值任务验证 可重复
     *
     * @param int $uid
     * @param int $extend
     */
    public static function verifyConditionDepositRepeat($uid, $taskid, $extend)
    {
        //TODO
        //$amount = $extend['amount'];
        $amount = $extend['amount']*10;
        $min_amount = $extend['condition']['min'] * 10;
        $max_amount = $extend['condition']['max'] * 10;
        
        if ($amount >= $min_amount && $amount <= $max_amount) {
            return true;
        }
        
        return false;
    }
    
    /**
     * 充值任务验证 仅可一次
     *
     * @param int $uid
     * @param int $extend
     */
    public static function verifyConditionDepositOnce($uid, $taskid, $extend)
    {
        
        //$amount = $extend['amount'];
        $amount = $extend['amount']*10;
        $min_amount = $extend['condition']['min'] * 10;
        $max_amount = $extend['condition']['max'] * 10;
         
        $dao_task = new DAOTask();
         
        $taskinfo = $dao_task->getTaskInfo($taskid);
        //Logger::log("task_err", "taskItem result1 ", [$taskid,json_encode($taskinfo)]);
        Util::log("kkk1", array($uid,$taskid,$extend,$taskinfo), 'ttt');
        if (!empty($taskinfo)) {
            $dao_user_task = new DAOUserTask($uid);
            $user_task_count = $dao_user_task->getUserTaskCount($taskid);
            //Logger::log("task_err", "taskItem result2 ", [$user_task_count]);
            Util::log("kkk2", array($user_task_count), 'ttt');
            if ($user_task_count > 0) {
                return false;
            } else {
                if ($amount >= $min_amount && $amount <= $max_amount) {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            if ($amount >= $min_amount && $amount <= $max_amount) {
                return true;
            } else {
                return false;
            }
        }
    }
    
    /**
     * 日常任务验证
     * 
     * @param int $uid            
     * @param int $taskid            
     * @param int $extend            
     */
    public static function verifyConditionDaily($uid, $taskid, $extend)
    {
        switch ($taskid) {
        case self::TASK_DAILY_FOLLOW_TIMES_ID:
            return self::verifyDailyFollow($uid, $extend);
                break;
        case self::TASK_DAILY_BARRAGEB_TIMES_ID:
            return self::verifyDailyBarrageb($uid, $extend);
                break;
        case self::TASK_DAILY_RECHARGR_ID:
            return self::verifyDailyRecharge($uid, $extend);
                break;
        case self::TASK_DAILY_WATCH_TIMES_ID:
            return self::verifyDailyWatchTimes($uid, $extend);
                break;
        case self::TASK_DAILY_WATCH_LIVE_ID:
            return self::verifyDailyWatchLive($uid, $extend);
                break;
                
            
        }
    }
    
    /**
     * 次数任务验证
     *
     * @param int $uid
     * @param int $taskid
     * @param int $extend
     */
    public static function verifyConditionTimes($uid, $taskid, $extend)
    {
        return true;
    }
    

    /**
     * 日常任务--关注主播数验证
     *
     * @param int $uid            
     * @param int $extend            
     */
    public static function verifyDailyFollow($uid, $extend)
    {
        Interceptor::ensureNotEmpty(! empty($extend['condition']['total']), ERROR_BIZ_TASK_AWARD_EMPTY, 'total');
        
        $key   = "following_ranking_date_" . date("Ymd"); // 日榜
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $total = $cache->ZSCORE($key, $uid);
        
        if ($total > $extend['condition']['total']) {
            return true;
        }
        return false;
    }

    /**
     * 日常任务--每日发一个弹幕验证
     * 
     * @param int $uid            
     * @param int $extend            
     */
    public static function verifyDailyBarrageb($uid, $extend)
    {
        return true;
    }

    /**
     * 日常任务--每日充值验证
     * 
     * @param int $uid            
     * @param int $extend            
     */
    public static function verifyDailyRecharge($uid, $extend)
    {
        //经济方法
        $date = date('Y-m-d');
        $num  = Deposit::getDepositNumByDate($uid, $date);
        if($num>0) {
            return true;
        }
        return false;
    }

    /**
     * 日常任务--观看直播10分钟验证
     * 
     * @param int $uid            
     * @param int $extend            
     */
    public static function verifyDailyWatchTimes($uid, $extend)
    {
        return true;
    }

    /**
     * 日常任务--观看5个直播间验证
     * 
     * @param int $uid            
     * @param int $extend            
     */
    public static function verifyDailyWatchLive($uid, $extend)
    {
        return true;
    }

    /**
     * 一次性任务--个人资料验证
     *
     * @param int $uid            
     * @param int $extend            
     */
    public static function verifyOncePersionData($uid, $extend)
    {
        Interceptor::ensureNotEmpty(! empty($extend['condition']['fields']), ERROR_BIZ_TASK_AWARD_EMPTY, 'fields');
        
        $where = '';
        foreach ($extend['condition']['fields'] as $item) {
            $where .= $item . ' != "" and ';
        }
        $where = substr($where, 0, strlen($where) - 4);
        
        $DAOUser = new DAOUser();
        return $DAOUser->isExistByFields($uid, $where);
    }

    /**
     * 一次性任务--绑定电话验证
     *
     * @param int $uid            
     * @param int $extend            
     */
    public static function verifyOnceBindMobile($uid, $extend)
    {
        Interceptor::ensureNotEmpty(! empty($extend['condition']['fields']), ERROR_BIZ_TASK_AWARD_EMPTY, 'fields');
        
        $where = '';
        foreach ($extend['condition']['fields'] as $item) {
            $where .= $item . ' != "" and';
        }
        $where = substr($where, 0, strlen($where) - 3);
        
        $UserBind = new DAOUserBind();
        $rid = $UserBind->isExistByFields($uid);
        
        $DAOVerified = new DAOVerified();
        $verified = $DAOVerified->isExistByFields($uid, $where);
        if($verified || is_numeric($rid)) {
            return true;
        }
        return false;
    }

    /**
     * 一次性任务--个人认证验证
     *
     * @param int $uid            
     * @param int $extend            
     */
    public static function verifyOncePersion($uid, $extend)
    {
        $user = new User();
        $userInfo = $user->getUserInfo($uid);
        if (isset($userInfo['verified']) && $userInfo['verified'] == true) {
            return true;
        }
        return false;
    }

    /**
     * 一次性任务--学生认证验证
     *
     * @param int $uid            
     * @param int $extend            
     */
    public static function verifyOnceSchool($uid, $extend)
    {
        $user = new User();
        $userInfo = $user->getUserInfo($uid);
        if (isset($userInfo['vs_school']) && $userInfo['vs_school'] == true) {
            return true;
        }
        return false;
    }

    /**
     * 一次性任务--首次充值验证
     *
     * @param int $uid            
     * @param int $extend            
     */
    public static function verifyOnceFirstRecharge($uid, $extend)
    {
        // 读经济方法
        $num = Deposit::getDepositNumByDate($uid);
        if ($num > 0) {
            return true;
        }
        return false;
    }

    /**
     * 一次性任务--首次关注人数验证
     *
     * @param int $uid            
     * @param int $extend            
     */
    public static function verifyOnceFollowTimes($uid, $extend)
    {
        Interceptor::ensureNotEmpty(! empty($extend['condition']['total']), ERROR_BIZ_TASK_AWARD_EMPTY, 'total');
        
        $dao_following = new DAOFollowing($uid);
        $total = $dao_following->countFollowings();
        
        if ($total > $extend['condition']['total']) {
            return true;
        }
        return false;
    }

    /**
     * 一次性任务--首次观看直播分钟数验证
     * 
     * @param int $uid            
     * @param int $extend            
     */
    public static function verifyOnceWatchTimes($uid, $extend)
    {
        return true;
    }

    /**
     * Task验证
     *
     * @param int $uid            
     * @param int $taskid            
     */
    public static function verifyTaskInfo($taskInfo, $userTaskInfo)
    {
        Interceptor::ensureNotEmpty($taskInfo, ERROR_BIZ_TASK_NOT_EXIST, $taskInfo['taskid']);
        Interceptor::ensureNotFalse($taskInfo["active"] == "Y", ERROR_BIZ_TASK_CLOSED, $taskInfo['taskid']);
        
        $now = Util::getTime();
        if ($taskInfo["begintime"] != "0000-00-00 00:00:00") {
            Interceptor::ensureFalse($now < strtotime($taskInfo["begintime"]), ERROR_BIZ_TASK_UNSTART, $taskInfo['taskid']);
        }
        
        if ($taskInfo["endtime"] != "0000-00-00 00:00:00") {
            Interceptor::ensureFalse($now > strtotime($taskInfo["endtime"]), ERROR_BIZ_TASK_EXPIRE, $taskInfo['taskid']);
        }
        if (($taskInfo["totallimit"] || $taskInfo["daylimit"])) {
            $remain = PHP_INT_MAX;
            if ($taskInfo["totallimit"]) {
                $remain = $taskInfo["totallimit"] - $userTaskInfo['totaltimes'];
                if($userTaskInfo['totaltimes']>0) {
                    Interceptor::ensureFalse($userTaskInfo['totaltimes'] >= $taskInfo["totallimit"], ERROR_BIZ_TASK_OVER_TIMES, $taskInfo['taskid']);
                }
            }
            if ($taskInfo["daylimit"]) {
                $remain = min($remain, $taskInfo["daylimit"] - $userTaskInfo['daytimes']);
                if($userTaskInfo['daytimes']>0) {
                    Interceptor::ensureFalse($userTaskInfo['daytimes'] >= $taskInfo["daylimit"], ERROR_BIZ_TASK_OVER_TIMES, $taskInfo['taskid']);
                }
            }
            Interceptor::ensureNotFalse($remain > 0, ERROR_BIZ_TASK_OVER_TIMES, $taskInfo['taskid']);
        }
        return true;
    }
}
