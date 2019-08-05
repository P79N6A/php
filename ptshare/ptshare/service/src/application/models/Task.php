<?php
class Task{
    const TASK_ID_ONE    = 1;
    const TASK_ID_TWO    = 2;
    const TASK_ID_THREE  = 3;
    const TASK_ID_FOUR   = 4;
    const TASK_ID_FIVE   = 5;
    const TASK_ID_SIX    = 6;
    const TASK_ID_SEVEN  = 7;
    const TASK_ID_EIGHT  = 8;
    const TASK_ID_NINE   = 9;
    const TASK_ID_TEN    = 10;

    /**
     * 1，新用户注册奖励
     * {"award":{"exp":{"num":5,"type":"normal"}},"condition":[]}
     *
     * 2，分享给好友奖励
     * {"award":{"grape":{"num":2,"type":"normal"}},"condition":[]}
     *
     * 3，关注公众号奖励
     * {"award":{"grape":{"num":5,"type":"normal"}},"condition":[]}
     *
     * 4，首次分享闲置奖励
     * {"award":{"exp":{"num":0.05,"type":"rate"},"grape":{"num":10,"type":"normal"}},"condition":[]}
     *
     * 5，邀请新用户奖励
     * {"award":{"exp":{"num":5,"type":"normal"},"grape":{"min":3,"max":5,"type":"rand"}},"condition":[]}
     *
     * 6，每日群红包奖励
     * {"award":{"grape":{"min":2,"max":20,"type":"rand"}},"condition":[]}
     *
     * 7，完善资料奖励
     * {"award":{"grape":{"num":10,"type":"normal"}},"condition":[]}
     *
     * 8，粉丝红包奖励
     * {"award":{"grape":{"num":1,"type":"normal"}},"condition":[]}
     *
     * 9，租用购买奖励
     * {"award":{"exp":{"num":0.1,"type":"rate"}},"condition":[]}
     *
     * 10,分享闲置奖励
     * {"award":{"exp":{"num":0.05,"type":"rate"}},"condition":[]}
     */


    /**
     * 任务执行主方法
     * @param int $uid
     * @param int $taskid
     * @param int $num
     */
    public static function execute($uid, $taskid, $num, $ext = '[]')
    {
        $taskInfo = self::getTaskInfo($taskid);
        Interceptor::ensureNotEmpty(! empty($taskInfo), ERROR_BIZ_TASK_NOT_EXIST, $taskid);

        // 1,任务超限验证
        $userTaskInfo = UserTask::getUserTask($uid, $taskid, true);
        $user_task_info = self::verifyUserTask($taskInfo, $userTaskInfo);
        Logger::log("task_log", "user_task_info", array("verify" => $user_task_info, 'info' => json_encode($userTaskInfo)));
        if (! empty($user_task_info)) {
            if ($taskInfo["totallimit"]) {
                Interceptor::ensureNotFalse($userTaskInfo['totaltimes'] + $num <= $taskInfo['totallimit'], ERROR_BIZ_TASK_OVER_TIMES, $taskid);
            }
            if ($taskInfo["daylimit"]) {
                Interceptor::ensureNotFalse($userTaskInfo['daytimes'] + $num <= $taskInfo['daylimit'], ERROR_BIZ_TASK_OVER_TIMES, $taskid);
            }
        }

        // 2,获取任务奖励
        $award = self::getTaskAward(json_decode($taskInfo['extend'], true), json_decode($ext, true));
        Interceptor::ensureNotEmpty(! empty($award), ERROR_BIZ_TASK_AWARD_EMPTY, $taskid);
        Logger::log("task_log", "task_award", array("array" => json_encode($award)));
        try {
            $daoTask = new DAOTask();
            $daoTask->startTrans();

            // 2,分配经验
            self::addUserExp($uid, $award['exp']);

            // 3,user_task表处理
            UserTask::setUserTask($uid, $taskid, $num, $userTaskInfo);

            // 4,添加任务明细表
            $logid = UserTaskLogs::addUserTaskLogs($uid, $taskid, $award);

            $daoTask->commit();
        } catch (Exception $e) {
            $daoTask->rollback();
            Logger::log("task_log", "send grape", array("code" => $e->getCode(),"msg" => $e->getMessage()));
            throw new BizException($e->getMessage());
        }

        // 5,添加葡萄数
        if (isset($award['grape']) && $award['grape'] > 0) {
            $remark  = $taskInfo['name'];

            if($taskid == self::TASK_ID_EIGHT) {
                $ext = json_decode($ext, true);

                $sid = $ext["uid"];

                Interceptor::ensureNotFalse(!Attract::isFollowed($uid, $sid), ERROR_BIZ_IS_FOLLOWED);
		        Interceptor::ensureNotFalse(Attract::isPatron($sid), ERROR_BIZ_IS_NOT_PATRON, "sid");
                Interceptor::ensureNotFalse(is_numeric($sid) && $sid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");

                //扣钱
                try {
                    $orderid = Account::addFollowAward($sid, $uid, $award['grape'], $remark);
            	    UserTaskLogs::updateUserTaskLogs($uid, $logid, $orderid);

            	    Attract::follow($uid, $sid);
                } catch(Exception $e) {
                    //余额不足
                    if($e->getCode() == ERROR_BIZ_PAYMENT_ACCOUNT_BALANCE_LACK) {
                        //临界情况
                        Attract::quit($sid);
                    }
                    Logger::log("task_log", "send grape", array("code" => $e->getCode(),"msg" => $e->getMessage()));
                    throw $e;
                }
            } else {
                $orderid = Account::addGrapeAward($uid, $award['grape'], $remark, $award);
            	UserTaskLogs::updateUserTaskLogs($uid, $logid, $orderid);
            }
        }

        $startime = date("Y-m-d H:i:s");
        return array(
            "uid"      => $uid,
            "taskid"   => $taskid,
            "orderid"  => $orderid,
            "award"    => $award,
            "startime" => $startime
        );
    }

    /**
     * Task验证
     * @param int $uid
     * @param int $taskid
     */
    public static function verifyUserTask($taskInfo, $userTaskInfo)
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
                if ($userTaskInfo['totaltimes'] > 0) {
                    Interceptor::ensureFalse($userTaskInfo['totaltimes'] >= $taskInfo["totallimit"], ERROR_BIZ_TASK_OVER_TIMES, $taskInfo['taskid']);
                }
            }
            if ($taskInfo["daylimit"]) {
                $remain = min($remain, $taskInfo["daylimit"] - $userTaskInfo['daytimes']);
                if ($userTaskInfo['daytimes'] > 0) {
                    Interceptor::ensureFalse($userTaskInfo['daytimes'] >= $taskInfo["daylimit"], ERROR_BIZ_TASK_OVER_TIMES, $taskInfo['taskid']);
                }
            }
            Interceptor::ensureNotFalse($remain > 0, ERROR_BIZ_TASK_OVER_TIMES, $taskInfo['taskid']);
        }
        return true;
    }

    /**
     * 获取用户奖励
     * @param array $extend
     * @return array
     */
    public static function getTaskAward($extend, $ext)
    {
        if (empty($extend) || empty($extend['award'])) {
            return array();
        }
        if($_REQUEST['channel'] && isset($extend['channel'][$_REQUEST['channel']])){
            $extend['award'] = $extend['channel'][$_REQUEST['channel']];
        }
        $award = array();
        if (isset($extend['award']['exp']) && ! empty($extend['award']['exp'])) {
            $type = $extend['award']['exp']['type'];
            switch ($type) {
                case 'normal':
                    if (isset($extend['award']['exp']['num'])) {
                        $award['exp'] = $extend['award']['exp']['num'];
                    }
                    break;
                case 'rand':
                    if (isset($extend['award']['exp'])) {
                        $award['exp'] = rand($extend['award']['exp']['min'], $extend['award']['exp']['max']);
                    }
                    break;
                case 'rate':
                    if (isset($extend['award']['exp']['num']) && $ext['grape']) {
                        $award['exp'] = intval($ext['grape'] * $extend['award']['exp']['num']);
                    }
                    break;
            }
        }
        if (isset($extend['award']['grape']) && ! empty($extend['award']['grape'])) {
            $type = $extend['award']['grape']['type'];
            switch ($type) {
                case 'normal':
                    if (isset($extend['award']['grape']['num'])) {
                        $award['grape'] = $extend['award']['grape']['num'];
                    }
                    break;
                case 'rand':
                    if (isset($extend['award']['grape'])) {
                        $award['grape'] = rand($extend['award']['grape']['min'], $extend['award']['grape']['max']);
                    }
                    break;
                case 'rate':
                    if (isset($extend['award']['grape']['num']) && $ext['grape']) {
                        $award['grape'] = intval($ext['grape'] * $extend['award']['grape']['num']);
                    }
                    break;
            }
        }
        return $award;
    }


    /**
     * 获取用户任务列表
     * @param int $uid
     * @return array()
     */
    public static function getTaskList($uid){
        $DAOTask = new DAOTask();
        $list = $DAOTask->getTaskList();
        $arrTemp = array();
        foreach($list as $key =>$val){
            $flags = false;
            $param = array();
            if (! empty($uid)) {
                $userTaskInfo = UserTask::getUserTask($uid, $val['taskid']);
                if (! empty($userTaskInfo)) {
                    if ($val['totallimit'] > 0 && $userTaskInfo['totaltimes'] >= $val['totallimit']) {
                        $flags = true;
                    }
                    if ($val['daylimit'] > 0 && $userTaskInfo['daytimes'] >= $val['daylimit']) {
                        $flags = true;
                    }
                    if($val['taskid']==6){
                        $flags = false;
                    }
                }
                $extends = Share::getShareUrl($uid, $val['taskid'], $val['title']);
            }

            $extend = json_decode($val['extend'],true);
            $award = array();
            if($extend['award']['grape']['type']){
                $award['type'] = $extend['award']['grape']['type'];
            }
            if($extend['award']['grape']['num']){
                $award['grape'] = $extend['award']['grape']['num'];
            }
            if($extend['award']['grape']['min']){
                $award['grape']['min'] = $extend['award']['grape']['min'];
            }
            if($extend['award']['grape']['max']){
                $award['grape']['max'] = $extend['award']['grape']['max'];
            }

            $arrTemp[$key]['taskid']   = $val['taskid'];
            $arrTemp[$key]['name']     = $val['name'];
            $arrTemp[$key]['type']     = $val['title'];
            $arrTemp[$key]['score']    = $val['score'];
            $arrTemp[$key]['award']    = $award;
            $arrTemp[$key]['schema']   = $val['schema'];
            $arrTemp[$key]['describe'] = $val['describe'];
            $arrTemp[$key]['extends']  = $extends;
            $arrTemp[$key]['flags']    = $flags;
        }
        return $arrTemp;
    }



    /**
     * 获取任务详情
     * @param int $taskid
     */
    public static function getTaskInfo($taskid){
        $DAOTask = new DAOTask();
        $taskInfo = $DAOTask->getTaskInfo($taskid);

        Interceptor::ensureNotEmpty($taskInfo, ERROR_BIZ_TASK_NOT_EXIST, $taskid);
        Interceptor::ensureNotFalse($taskInfo["active"] == "Y", ERROR_BIZ_TASK_CLOSED, $taskid);

        $now = Util::getTime();
        if ($taskInfo["begintime"] != "0000-00-00 00:00:00") {
            Interceptor::ensureFalse($now < strtotime($taskInfo["begintime"]), ERROR_BIZ_TASK_UNSTART, $taskid);
        }

        if ($taskInfo["endtime"] != "0000-00-00 00:00:00") {
            Interceptor::ensureFalse($now > strtotime($taskInfo["endtime"]), ERROR_BIZ_TASK_EXPIRE, $taskid);
        }

        return $taskInfo;
    }

    /**
     * 分配经验
     * @param int $uid
     * @param int $exp
     * @throws Exception
     */
    public static function addUserExp($uid, $exp){
        if (! (isset($exp) && $exp > 0)) {
            return;
        }
        $level = UserExp::getLevelByExp(UserExp::getUserExp($uid));
        UserExp::addUserExp($uid, $exp);
        User::reload($uid);
        return UserExp::getLevelByExp(UserExp::getUserExp($uid));
    }
}
?>
