<?php


class Task
{
    const TASK_TYPE_LADDER          = 1;// 阶梯任务
    const TASK_TYPE_ONCE            = 2;// 一次性任务
    const TASK_TYPE_DAILY           = 3;// 日常任务
    const TASK_TYPE_TIMES           = 4;// 系数任务
    const TASK_TYPE_LEVEL           = 5;// 系数任务
    const TASK_TYPE_DEPOSIT_REPEAT    = 6;// 充值任务(可重复)
    const TASK_TYPE_DEPOSIT_ONCE    = 7;// 充值任务(仅可一次)
    
    const TASK_ID_SEND_GIFT    = 1; //发礼物
    const TASK_ID_LIVE_WATCH   = 2; //看播
    const TASK_ID_LIVE_START   = 3; //开播
    const TASK_ID_COMMENT      = 4; //评论
    const TASK_ID_SHARE        = 5; //分享
    const TASK_ID_PROP         = 6; //飞屏
    
    const SECRET_TTL            = 300;
    const MSG_ROOM_UP           = "恭喜%s升级到了%s级";
    const TICKET_SECRET         = 'kI*3NGjidI6gikdMjfT9';
    
    
    /**
     * 充值任务执行
     *
     * @param int    $uid    用户id
     * @param double $amount 充值金额
     */
    public function depositExecute($uid, $amount) 
    {
        include_once dirname(__FILE__).DIRECTORY_SEPARATOR. "task". DIRECTORY_SEPARATOR. "VerifyTask.php";
        
        $DAOTask = new DAOTask();
        $taskList     = $DAOTask->getTaskListByTypes(array(Task::TASK_TYPE_DEPOSIT_ONCE, Task::TASK_TYPE_DEPOSIT_REPEAT));//一次性任务
        Logger::log("account_task_work", "taskList", [json_encode($taskList),$uid,$amount]);
        if (!empty($taskList)) {
            foreach ($taskList AS $taskInfo) {
                $taskid = $taskInfo['taskid'];
                $ext = [];
                $ext['amount'] = $amount;
                Logger::log("account_task_work", "taskItem start ", [$taskid]);
                
                $extend = json_decode($taskInfo['extend'], true);
                $extend = array_merge($extend, $ext);
                Logger::log("account_task_work", "taskItem start2 ", [json_encode($taskInfo),json_encode($extend)]);
                
                $verify = VerifyTask::verifyCondition($uid, $taskid, $taskInfo['type'], $extend);
                Logger::log("account_task_work", "taskItem result ", [$verify]);
                if ($verify) {
                    Logger::log("account_task_work", "taskItem starting ", [$taskid]);
                    include_once 'process_client/ProcessClient.php';
                    $params = array(
                      'uid'         => $uid,
                      'task'        => $taskid,
                      'num'       => 1,
                      'ext'         => $ext
                    );
                    //ProcessClient::getInstance("dream")->addTask("task_execute_worker", $params);
                    $this->execute($uid, $taskid, 1, json_encode($ext));
                }
                
            }
        } else {
            Logger::log("account_task_work", "emptyTaskList", 'ddd ');
        }
    }
    
    /**
     * 任务执行主方法
     *
     * @param int $uid
     * @param int $taskid
     * @param int $num
     */
    public function execute($uid, $taskid, $num, $ext = '[]')
    {
        include_once dirname(__FILE__).DIRECTORY_SEPARATOR. "task". DIRECTORY_SEPARATOR. "VerifyTask.php";
        include_once dirname(__FILE__).DIRECTORY_SEPARATOR. "task". DIRECTORY_SEPARATOR. "AwardTask.php";
        
        $user = new User();
        $userInfo = $user->getUserInfo($uid);
        
        // 1,Task任务验证
        $userTask     = new UserTask();
        $taskInfo     = $this->getTaskInfo($taskid);
        $userTaskInfo = $userTask->getUserTask($uid, $taskid);
        
        if (in_array($taskInfo['type'], array(self::TASK_TYPE_DEPOSIT_ONCE, self::TASK_TYPE_DEPOSIT_REPEAT))) {
            $log_file_name = "account_task_work";
        } else {
            $log_file_name = "task_err";
        }
        Logger::log($log_file_name, "verify1", [$taskid, $taskInfo['extend']]);
        $verify = VerifyTask::verifyTaskInfo($taskInfo, $userTaskInfo);
        
        
        // 2,完成任务条件验证
        $extend = json_decode($taskInfo['extend'], true);
        $data   = json_decode(trim(strip_tags($ext)), true);
        if (is_array($data)) {
            $extend = array_merge($extend, $data);
            //$extend['amount'] = $data['amount'];
        }
        if (in_array($taskInfo['type'], array(self::TASK_TYPE_DEPOSIT_ONCE, self::TASK_TYPE_DEPOSIT_REPEAT))) {
            if (empty($data['amount'])) {
                Interceptor::ensureFalse(true, ERROR_BIZ_TASK_NOT_COMPLETE, $taskid);
            }
        }
        //print_r($extend);
        $r = json_encode($extend);
        Logger::log($log_file_name, "extend", [$taskid, $extend['award']['diamonds'], $r]);
        
        $verify = VerifyTask::verifyCondition($uid, $taskid, $taskInfo['type'], $extend);
        
        Logger::log($log_file_name, "verify2", [$taskid, $extend['award']['diamonds'], $r]);
        Interceptor::ensureFalse(! $verify, ERROR_BIZ_TASK_NOT_COMPLETE, $taskid);
        
        Logger::log($log_file_name, "verify3", [$taskid, $extend['award']['diamonds'], $r]);
        // 3,获取任务奖励
        
        $award = AwardTask::getTaskAward($uid, $taskid, $taskInfo['type'], $num, json_decode($taskInfo['extend'], true), $data);
        Interceptor::ensureNotEmpty(! empty($award), ERROR_BIZ_TASK_AWARD_EMPTY, $taskid);
        Logger::log($log_file_name, "task0", [$award['diamonds']]);
        
        
        try {
            $daoTask = new DAOTask();
            $daoTask->startTrans();
            
            // 5,递归分配经验
            if (isset($award['exp']) && $award['exp'] > 0) {
                $userExp = $this->recursionAddUserExp($uid, $award['exp'], $data['liveid']);
                $award['starlight'] = $award['starlight'] + $userExp['starlight'];
                $award['diamonds']  = $award['diamonds']  + $userExp['diamonds'];
            }
            Logger::log($log_file_name, "task1", [$award['diamonds']]);
            // 6,user_task表处理
            $userTaskInfo = $userTask->setUserTask($uid, $taskid, $award, $num, $taskInfo['type']);
            Logger::log($log_file_name, "task2", [$award['diamonds']]);
            // 7,添加任务明细表
            UserTaskDetails::addUserTaskDetails($uid, $taskid, $taskInfo['type'], array('award'=>$award,'user_task'=>$userTaskInfo));
            Logger::log($log_file_name, "task3", [$award['diamonds']]);
            // 8,添加任务奖励表
            $awardId = UserTaskAward::AddUserTaskAward($uid, $taskid, $award);
            Logger::log($log_file_name, "task4", [$award['diamonds']]);
            $daoTask->commit();
        } catch (Exception $e) {
            Logger::log($log_file_name, "task execute exception", $e);
            $daoTask->rollback();
            throw $e;
        }
       
        Logger::log($log_file_name, "step3", ["2222222222222", json_encode($award)]);
        
        // 8,work分配星光,星钻, 送座驾,礼物
        include_once 'process_client/ProcessClient.php';
        $params = array(
            'uid'     => $uid,
            'task'    => $taskid,
            'award'   => $award,
            'awardId' => $awardId
        );
        ProcessClient::getInstance("dream")->addTask("account_task_work", $params);


        $starttime = time();
        if (($taskInfo["totallimit"] && ($userTaskInfo["totaltimes"] >= $taskInfo["totallimit"])) || ($taskInfo["daylimit"] && ($userTaskInfo["daytimes"] >= $taskInfo["daylimit"]))) {
            $starttime = strtotime("23:59:59");
        }
    
        return array(
            "uid" => $uid,
            "taskid" => $taskid,
            "ticket" => self::makeTicket($uid, $taskid, $starttime),
            "award" => $award,
            "level" => (int) $userInfo["level"],
            "exp" => (int) $userInfo["exp"],
            "starttime" => $starttime
        );
    }
    
    /**
     * 用户未完成任务
     *
     * @param int $uid
     */
    public static function unfinishedTask($uid)
    {
        include_once dirname(__FILE__).DIRECTORY_SEPARATOR. "task". DIRECTORY_SEPARATOR. "VerifyTask.php";
        $flags = false;
        
        $unfinished = VerifyTask::TASK_UNFINISHED_ID;
        
        // 判断用户今日是否已经签到
        $startime = date("Y-m-d");
        $endtime  = date("Y-m-d", strtotime("1 day"));
        $DAOUserTaskDetails = new DAOUserTaskDetails($uid);
        $userTaskDetails    = $DAOUserTaskDetails->getUserTaskDetailsSign($startime, $endtime);
        if(empty($userTaskDetails)) {
            $flags = true;
        }
        
        // 判断一次性任务是否完成
        foreach($unfinished as $item){
            $taskInfo = $this->getTaskInfo($item);
            $extend   = json_decode($taskInfo['extend'], true);
            $verify   = VerifyTask::verifyCondition($uid, $item, $taskInfo['type'], $extend);
            if($verify===false) {
                $flags = true;
            }
        }
        return $flags;
    }
    

    /**
     * 获取用户任务列表
     *
     * @param  int $uid            
     * @return array()
     */
    public function getTaskList($uid, $type)
    {
        include_once dirname(__FILE__).DIRECTORY_SEPARATOR. "task". DIRECTORY_SEPARATOR. "VerifyTask.php";
        include_once dirname(__FILE__).DIRECTORY_SEPARATOR. "task". DIRECTORY_SEPARATOR. "AwardTask.php";
        
        $language = Context::getConfig("SYSTERM_COMMON_LANGUAGE");
        $user = new User();
        $userInfo = $user->getUserInfo($uid);
        $region = $userInfo['region'];
        
        $list = array();
        $arrTemp     = UserTaskDetails::getListType($uid, $type);//一次性任务
        $arrTemp1     = UserTaskDetails::getListType($uid, Task::TASK_TYPE_DEPOSIT_ONCE);//充值仅一次
        $arrTemp2     = UserTaskDetails::getListType($uid, Task::TASK_TYPE_DEPOSIT_REPEAT);//充值可重复
        
        $arrTemp = $arrTemp + $arrTemp1 + $arrTemp2;
        // 1,获取任务列表
        $DAOTask = new DAOTask();
        $taskList     = $DAOTask->getTaskList($type);//一次性任务
        $taskList1     = $DAOTask->getTaskList(Task::TASK_TYPE_DEPOSIT_ONCE);//充值仅一次
        $taskList2     = $DAOTask->getTaskList(Task::TASK_TYPE_DEPOSIT_REPEAT);//充值可重复
        
        
        $taskList = array_merge($taskList, $taskList1, $taskList2);
        
        foreach ($taskList as $key => $val) {
            $extend = json_decode($val['extend'], true);
            $list[$key]['name']      = isset($language['task_message'][$region]['title'][$val['taskid']]) ? sprintf($language['task_message'][$region]['title'][$val['taskid']], $extend['condition']['total']): sprintf($val['name'], $extend['condition']['total']);
            $list[$key]['taskid']    = $val['taskid'];
            $list[$key]['type']      = $val['type'];
            $list[$key]['describe']  = $val['describe'];
            if (isset($arrTemp[$val['taskid']]) && $val['type'] != self::TASK_TYPE_DEPOSIT_REPEAT) {
                $list[$key]['sign'] = true;
            } else {
                $list[$key]['sign'] = false;
            }
            
            if (!empty($extend['award']['gift']['id'])) {
                $gift=new Gift();
                $gift_info=$gift->getGiftInfo($extend['award']['gift']['id']);
                if(empty($gift_info)) {
                    unset($extend['award']['gift']);
                }else{
                    $extend['award']['gift']['name'] = $gift_info['name'];
                }
            }
            
            if (!empty($extend['award']['ride']['id'])) {
                $product=new Product();
                $product_info=$product->getOne($extend['award']['ride']['id']);
                if(empty($product_info)) {
                    unset($extend['award']['ride']);
                }else{
                    $extend['award']['ride']['name'] = $product_info['name'];
                }
            }
            
            $list[$key]['award'] = $extend['award'];
        }
        
        // 2,等级任务
        $DAOUserTaskDetails = new DAOUserTaskDetails($uid);
        $levelList = $DAOUserTaskDetails->getListByLevel(VerifyTask::TASK_LADDER_LEVER_ID);
        $level = array();
        foreach($levelList as $key=>$val){
            array_push($level, $val['level']);
        }

        $levelInfo = $DAOTask->getTaskInfo(VerifyTask::TASK_LADDER_LEVER_ID);
        if (! empty($levelInfo) && $levelInfo['active']=='Y') {
            $extend = json_decode($levelInfo['extend'], true);
            $sign = false;
            foreach($extend['award'] as $key=>$val){
                if(in_array($key, $level)) {
                    //continue;
                    $sign = true;
                }

                $award = array();
                $award['exp']       = $val['exp'];
                $award['starlight'] = $val['starlight'];
                $award['diamonds']  = $val['diamonds'];
                if (!empty($val['gift']['id'])) {
                    $gift=new Gift();
                    $gift_info=$gift->getGiftInfo($val['gift']['id']);
                    if(!empty($gift_info)) {
                        $award['gift']['id']   = $val['gift']['id'];
                        $award['gift']['num']  = $val['gift']['num'];
                        $award['gift']['name'] = $gift_info['name'];
                    }
                }
                if (!empty($val['ride']['id'])) {
                    $product=new Product();
                    $product_info=$product->getOne($val['ride']['id']);
                    if(!empty($product_info)) {
                        $award['ride']['id']      = $val['ride']['id'];
                        $award['ride']['expire']  = $val['ride']['expire'];
                        $award['ride']['name']    = $product_info['name'];
                    }
                }

                $item = array(
                    'name'   => isset($language['task_message'][$region]['title'][VerifyTask::TASK_LADDER_LEVER_ID]) ? sprintf($language['task_message'][$region]['title'][VerifyTask::TASK_LADDER_LEVER_ID], $key): sprintf($levelInfo['name'], $key),
                    'taskid' => VerifyTask::TASK_LADDER_LEVER_ID,
                    'type'   => self::TASK_TYPE_LADDER,
                    'describe' => $val['describe'],
                    'level'  => $key,
                    'award'  => $award, 
                    'sign'     => $sign,
                );
                array_push($list, $item);
            }
        }
        
        return $list;
    }
    
    /**
     * 签到列表
     *
     * @param int $uid
     */
    public function getSignList($uid)
    {
        include_once dirname(__FILE__).DIRECTORY_SEPARATOR. "task". DIRECTORY_SEPARATOR. "VerifyTask.php";
        include_once dirname(__FILE__).DIRECTORY_SEPARATOR. "task". DIRECTORY_SEPARATOR. "AwardTask.php";
        
        $level   = 0;
        $flags   = true;
        $list    = array();
        
        $DAOUserTaskDetails   = new DAOUserTaskDetails($uid);
        
        // 获取昨天签到
        $startime = date("Y-m-d", strtotime("-1 day"));
        $endtime = date("Y-m-d");
        $taskDetails = $DAOUserTaskDetails->getUserTaskDetailsSign($startime, $endtime);
        if(!empty($taskDetails) && $taskDetails['level']>0) {
            $level = $taskDetails['level'];
        }
        
        // 判断用户今日是否已经签到
        $startime = date("Y-m-d");
        $endtime = date("Y-m-d", strtotime("1 day"));
        $todayTaskDetails = $DAOUserTaskDetails->getUserTaskDetailsSign($startime, $endtime);
        if(!empty($todayTaskDetails) && $todayTaskDetails['level']>0) {
            $level = $todayTaskDetails['level'];
            $flags = false;
        }
        if($level>=7) {
            $level = 0;
            //$flags = false;
        }
        
        // 任务奖励
        $taskInfo = $this->getTaskInfo(VerifyTask::TASK_LADDER_SIGN_ID);
        $extend   = json_decode($taskInfo['extend'], true);
        
        $i = 0;
        foreach ($extend['award'] as $key => $val) {
            $item = $val;
            if ($level > $i) {
                $item['sign'] = true;
            }
            if (!empty($val['gift']['id'])) {
                $gift=new Gift();
                $gift_info=$gift->getGiftInfo($val['gift']['id']);
                if(!empty($gift_info)) {
                    $item['gift']['id']   = $val['gift']['id'];
                    $item['gift']['num']  = $val['gift']['num'];
                    $item['gift']['name'] = $gift_info['name'];
                }
            }
            if (!empty($val['ride']['id'])) {
                $product=new Product();
                $product_info=$product->getOne($val['ride']['id']);
                if(!empty($product_info)) {
                    $item['ride']['id']      = $val['ride']['id'];
                    $item['ride']['expire']  = $val['ride']['expire'];
                    $item['ride']['name']    = $product_info['name'];
                }
            }
            $list[] = $item;
            $i ++;
        }
        
        return array('list'=>$list,'signtimes'=>$level,'flags'=>$flags);
    }
    
    /**
     * 等级任务
     *
     * @param int $uid
     */
    public function levelTask($uid,$level)
    {
        include_once dirname(__FILE__).DIRECTORY_SEPARATOR. "task". DIRECTORY_SEPARATOR. "VerifyTask.php";
        include_once dirname(__FILE__).DIRECTORY_SEPARATOR. "task". DIRECTORY_SEPARATOR. "AwardTask.php";
        
        $DAOUserTaskDetails = new DAOUserTaskDetails($uid);
        
        // 任务超限判断
        $info = $DAOUserTaskDetails->getInfoByLevel(VerifyTask::TASK_LADDER_LEVER_ID, $level);
        Interceptor::ensureNotEmpty(empty($info), ERROR_BIZ_TASK_OVER_TIMES, VerifyTask::TASK_LADDER_LEVER_ID);

        $userLevel = UserExp::getLevelByExp(UserExp::getUserExp($uid));
        Interceptor::ensureNotFalse($userLevel>=$level, ERROR_BIZ_TASK_NOT_COMPLETE, VerifyTask::TASK_LADDER_LEVER_ID);
        
        // 获取任务奖励
        $taskInfo = $this->getTaskInfo(VerifyTask::TASK_LADDER_LEVER_ID);
        $extend   = json_decode($taskInfo['extend'], true);
        $award    = $extend['award'][$level];
        Interceptor::ensureNotEmpty(! empty($award), ERROR_BIZ_TASK_NOT_EXIST, VerifyTask::TASK_LADDER_LEVER_ID);
        
        try {
            $daoTask = new DAOTask();
            $daoTask->startTrans();
            
            if(isset($award['exp'])) {
                $this->addUserExp($uid, $award['exp']);
            }

            // 添加任务明细表
            $result = UserTaskDetails::addUserTaskDetails($uid, VerifyTask::TASK_LADDER_LEVER_ID, $taskInfo['type'], array('award'=>$award), $level);
        
            // 添加任务奖励表
            $awardId = UserTaskAward::AddUserTaskAward($uid, VerifyTask::TASK_LADDER_LEVER_ID, $award);
        
            $daoTask->commit();
        } catch (Exception $e) {
            Logger::log("task_err", "task execute exception kind", array());
            $daoTask->rollback();
            throw $e;
        }
        
        // 8,work分配星光,星钻, 送座驾,礼物
        include_once 'process_client/ProcessClient.php';
        $params = array(
            'uid'     => $uid,
            'task'    => VerifyTask::TASK_LADDER_LEVER_ID,
            'award'   => $award,
            'awardId' => $awardId
        );
        ProcessClient::getInstance("dream")->addTask("account_task_work", $params);
        
        $user = new User();
        $userInfo = $user->getUserInfo($uid);
        $starttime = time();
        return array(
            "uid" => $uid,
            "taskid" => VerifyTask::TASK_LADDER_LEVER_ID,
            "ticket" => self::makeTicket($uid, VerifyTask::TASK_LADDER_LEVER_ID, $starttime),
            "award" => $award,
            "level" => (int) $userInfo["level"],
            "exp" => (int) $userInfo["exp"],
            "starttime" => $starttime
        );
    }

    /**
     * 用户签到
     *
     * @param int $uid            
     */
    public function signTask($uid)
    {
        $cache  = Cache::getInstance("REDIS_CONF_CACHE");
        $key    = "sign_task_once_string_". $uid;
        $result = $cache->INCR($key);
        $cache->EXPIRE($key, 10);
        Interceptor::ensureNotEmpty(! ($result > 1), ERROR_BIZ_TASK_IS_SIGN, $uid);
        
        include_once dirname(__FILE__).DIRECTORY_SEPARATOR. "task". DIRECTORY_SEPARATOR. "VerifyTask.php";
        include_once dirname(__FILE__).DIRECTORY_SEPARATOR. "task". DIRECTORY_SEPARATOR. "AwardTask.php";
        
        $lastday = 1;
        
        $DAOUserTaskDetails = new DAOUserTaskDetails($uid);
        
        // 1,获取任务info
        $taskInfo = $this->getTaskInfo(VerifyTask::TASK_LADDER_SIGN_ID);
        $total    = isset($taskInfo['extend']['condition']['total']) ? $taskInfo['extend']['condition']['total'] : 7;
        
        // 2,判断用户今日是否已经签到
        $startime = date("Y-m-d");
        $endtime  = date("Y-m-d", strtotime("1 day"));
        $userTaskDetails = $DAOUserTaskDetails->getUserTaskDetailsSign($startime, $endtime);
        Interceptor::ensureNotEmpty(empty($userTaskDetails), ERROR_BIZ_TASK_IS_SIGN, 'uid');
        
        // 3,获取昨天签到
        $startime = date("Y-m-d", strtotime("-1 day"));
        $endtime  = date("Y-m-d");
        $yesterdayTaskDetails = $DAOUserTaskDetails->getUserTaskDetailsSign($startime, $endtime);
        if (! empty($yesterdayTaskDetails) && $yesterdayTaskDetails['level']) {
            if ($yesterdayTaskDetails['level'] < $total) {
                $lastday = $yesterdayTaskDetails['level'] + 1;
            } else {
                //$lastday = $yesterdayTaskDetails['level'];
                $lastday = 1;
            }
        }

        // 4,任务奖励
        $extend   = json_decode($taskInfo['extend'], true);
        $award    = $extend['award'][$lastday];
        Interceptor::ensureNotEmpty(! empty($award), ERROR_BIZ_TASK_SIGN_ERROR, 'uid');

        try {
            $daoTask = new DAOTask();
            $daoTask->startTrans();
        
            // 5,递归分配经验
            if (isset($award['exp']) && $award['exp'] > 0) {
                $userExp = $this->recursionAddUserExp($uid, $award['exp']);
                $award['starlight'] = $award['starlight'] + $userExp['starlight'];
                $award['diamonds']  = $award['diamonds']  + $userExp['diamonds'];
            }

            // 6,添加任务明细表
            $awardId = UserTaskDetails::addUserTaskDetails($uid, VerifyTask::TASK_LADDER_SIGN_ID, $taskInfo['type'], array('award'=>$award), $lastday);
            
            // 7,添加任务奖励表
            $awardId = UserTaskAward::AddUserTaskAward($uid, VerifyTask::TASK_LADDER_SIGN_ID, $award);
            
            $cache->delete($key);
            
            $daoTask->commit();
        } catch (Exception $e) {
            Logger::log("task_err", "task execute exception kind", array());
            $daoTask->rollback();
            throw $e;
        }
        

        // 8,work分配星光,星钻, 送座驾,礼物
        include_once 'process_client/ProcessClient.php';
        $params = array(
            'uid'     => $uid,
            'task'    => VerifyTask::TASK_LADDER_SIGN_ID,
            'award'   => $award,
            'awardId' => $awardId
        );
        ProcessClient::getInstance("dream")->addTask("account_task_work", $params);
        
        return true;
    }

    /**
     * 获取任务详情
     *
     * @param int $taskid
     */
    public function getTaskInfo($taskid)
    {
        $daoTask  = new DAOTask();
        $taskInfo = $daoTask->getTaskInfo($taskid);
        
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
     * 递归增加用户的经验
     *
     * @param int $uid            
     * @param int $exp            
     */
    public function recursionAddUserExp($uid, $exp,$liveid = 0, &$arrTemp = array())
    {
        $level = $this->addUserExp($uid, $exp, $liveid);
        Logger::log("task_exp", "exp5", [$uid, $exp]);
        if (! empty($level)) {
            $arrTemp = $this->getAwardByUpgradeLevel($uid, $level);
            if (isset($arrTemp['exp']) && $arrTemp['exp'] > 0) {
                $result = $this->recursionAddUserExp($uid, $arrTemp['award']['exp'], $liveid, $arrTemp);
                Logger::log("task_exp", "exp11", [$uid, $exp]);
                if (isset($result['starlight']) && $result['starlight'] > 0) {
                    $arrTemp['starlight'] = $arrTemp['starlight'] + $result['starlight'];
                }
                if (isset($award['diamonds']) && $result['diamonds'] > 0) {
                    $arrTemp['diamonds'] = $arrTemp['diamonds'] + $result['diamonds'];
                }
            }
        }
        User::reload($uid);
        return $arrTemp;
    }

    /**
     * 分配经验
     *
     * @param  int $uid                     
     * @param  int $exp            
     * @throws Exception
     */
    public function addUserExp($uid, $exp, $liveid = '')
    {
        $level = UserExp::getLevelByExp(UserExp::getUserExp($uid));
        Logger::log("task_exp", "exp1", [$uid, $level]);
        UserExp::addUserExp($uid, $exp);
        Logger::log("task_exp", "exp2", [$uid, $level]);
        $newlevel = UserExp::getLevelByExp(UserExp::getUserExp($uid));
        Logger::log("task_exp", "exp3", [$uid, $level]);
        //User::reload($uid);
        
        // 发勋章
        $consume_money = Counter::get(Counter::COUNTER_TYPE_CONSUME_MONEY, $uid);
        UserMedal::setTuhaoLevel($uid, $consume_money);
        Logger::log("task_exp", "exp4", [$uid, $level]);
        // 涨了多少级
        $arrTemp = array();
        for ($i = 1; $i <= $newlevel; $i ++) {
            if ($i > $level) {
                array_push($arrTemp, $i);
            }
        }
        //系统老的逻辑
        if (!empty($liveid) && $newlevel > $level) {
            $user = new User();
            $user_info = $user->getUserInfo($uid);
            
            $live = new Live();
            $liveinfo = $live->getLiveInfo($liveid);
            $user_guard = UserGuard::getUserGuardRedis($uid, $liveinfo['uid']);
            
            
            Messenger::sendUserLevelChange($liveid, $uid, sprintf(self::MSG_ROOM_UP, $user_info['nickname'], $newlevel), $uid, $user_info['nickname'], $user_info['avatar'], $newlevel, intval($user_guard));
        }
        return $arrTemp;
    }

    /**
     * 等级提成获取奖励
     *
     * @param array $level            
     */
    public function getAwardByUpgradeLevel($uid, $upgradeLevel)
    {
        if (empty($upgradeLevel)) {
            return array();
        }
        Logger::log("task_exp", "exp6", [$uid, $upgradeLevel]);
        // 获取提升等级奖励
        $taskInfo = $this->getTaskInfo(VerifyTask::TASK_LADDER_LEVER_ID);
        Logger::log("task_exp", "exp7", [$uid, $upgradeLevel]);
        $extend = json_decode($taskInfo['extend'], true);
        $award = $extend['award'];
        if (empty($award)) {
            return array();
        }
        Logger::log("task_exp", "exp8", [$uid, $upgradeLevel]);
        $awardTemp = array('exp'=>0,'starlight' => 0,'diamonds' => 0);
        $DAOUserTaskDetails = new DAOUserTaskDetails($uid);
        Logger::log("task_exp", "exp9", [$uid, $upgradeLevel]);
        foreach ($upgradeLevel as $item) {
            if(isset($award[$item]) || empty($award[$item])) {
                continue;
            }
            // 添加任务明细表
            $result = UserTaskDetails::addUserTaskDetails($uid, VerifyTask::TASK_LADDER_LEVER_ID, $taskInfo['type'], array('award'=>$award[$item]), $item);
            // 经验处理
            if (isset($award[$item]['exp']) && $award[$item]['exp'] > 0) {
                $awardTemp['exp'] = $awardTemp['exp'] + $award[$item]['exp'];
            }
            // 星光处理
            if (isset($award[$item]['starlight']) && $award[$item]['starlight'] > 0) {
                $awardTemp['starlight'] = $awardTemp['starlight'] + $award[$item]['starlight'];
            }
            // 星钻处理
            if (isset($award[$item]['diamonds']) && $award[$item]['diamonds'] > 0) {
                $awardTemp['diamonds'] = $awardTemp['diamonds'] + $award[$item]['diamonds'];
            }
        }
        Logger::log("task_exp", "exp10", [$uid, $upgradeLevel]);
        return $awardTemp;
    }
    
    

    public static function makeTicket($uid, $taskid, $time)
    {
        $array = array($uid,$taskid,self::TICKET_SECRET,$time + self::SECRET_TTL);
        return substr(md5(implode('_', $array)), 0, 8) . '_' . $time;
    }

    public static function checkTicket($ticket, $uid, $taskid, $time)
    {
        Interceptor::ensureNotFalse($ticket == Task::makeTicket($uid, $taskid, $time), ERROR_BIZ_TASK_BAD_TICKET, "uid:$uid task:$taskid ticket:$ticket");
        $now = Util::getTime();
        Interceptor::ensureNotFalse($now > $time + Task::SECRET_TTL - 30 && $now < $time + Task::SECRET_TTL + 100, ERROR_BIZ_TASK_BAD_TICKET, "time");
    }

    public function adminUpdateTask($taskid, $name , $active, $totallimit, $daylimit, $extend, $type, $status)
    {
        $daoTask = new DAOTask();

        return $daoTask->updateTask($taskid, $name, $active, $totallimit, $daylimit, $extend, $type, $status);
    }
}
?>
