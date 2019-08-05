<?php

/**
 * @desc   守护
 * @author yangqing
 */
class UserGuard
{

    const GUARD_TYPE_YEAR  = 10;
    const GUARD_TYPE_MONTH = 5;
    const KEY_PREFIX = 'DREAM_USER_GUARD_';

    public static $guard = array(
        self::GUARD_TYPE_YEAR => array(
            'type'    => 10,
            'image'   => 'http://static.dreamlive.com/images/01e8ef12c6cf566eaa8c8437dddd07e4.png',
            'title'   => '黄金守护',
            'desc'    => '专属座位,进场特效,守护勋章,防踢防禁言
付费房间门票9折',
            'price'   => 52000,
            'expires' => 365
        ),
        self::GUARD_TYPE_MONTH => array(
            'type'    => 5,
            'image'   => 'http://static.dreamlive.com/images/e0a131ddb9e76f10468d95cb86934080.png',
            'title'   => '白银守护',
            'desc'    => '专属座位,进场特效,守护勋章,防踢防禁言
付费房间门票9折',
            'price'   => 5200,
            'expires' => 30
        )
    );

    /**
     * 守护列表
     *
     * @param int $uid            
     */
    public static function getGuard($uid, $relateid)
    {
        $flags = false;
        $time = date("Y-m-d H:i:s");
        $arrTemp = array();
        
        $DAOUserGuard = new DAOUserGuard();
        $userGuardInfo = $DAOUserGuard->getInfoByUidRelateid($uid, $relateid);
        
        $list = self::$guard;
        foreach ($list as $key => $val) {
            if (isset($userGuardInfo['endtime']) && $userGuardInfo['endtime'] > $time && $userGuardInfo['type'] == $list[$key]['type']) {
                $val['flags']   = true;
                $val['endtime'] = $userGuardInfo['endtime'];
            }
            $arrTemp[] = $val;
        }
        return $arrTemp;
    }
    
    /**
     * 获取我守护的人的列表
     *
     * @param int $uid
     */
    public static function getGuardingList($uid)
    {
        $DAOUserGuard = new DAOUserGuard();
        $list_1 = $DAOUserGuard->getGuardingList($uid);
        $list_2 = array(); //年过期
        $list_3 = array(); //月过期

        $user = new User();
        foreach($list_1 as $key=>$val){
            
            $list_1[$key]['time'] = date('Y-m-d H:i:s');
            $list_1[$key]['user'] = $user->getUserInfo($list_1[$key]['relateid']);
            
            $timestamp = strtotime($val['endtime']);
            if($timestamp < time()) { //如果过期
                
                $temp = $list_1[$key];
                
                //删除过期的数据
                unset($list_1[$key]);
                
                //如果是年则排在月上面
                if($val['type'] == 10) {
                    $list_2[] = $temp;
                }else{
                    $list_3[] = $temp;
                }
            }

        }
        
        $list = array_merge($list_1, $list_2, $list_3);
        
        return $list;
    }
    
    
    
    /**
     * 获取守护我的人的列表
     *
     * @param int $uid
     */
    public static function getGuardedList($uid)
    {
        $list_1 = self::_getGuardedList($uid);
        $list_2 = array(); //年过期
        $list_3 = array(); //月过期
        $user = new User();
        foreach($list_1 as $key=>$val){
            
            $list_1[$key]['time'] = date('Y-m-d H:i:s');
            $list_1[$key]['user'] = $user->getUserInfo($list_1[$key]['uid']);
            
            $timestamp = strtotime($val['endtime']);
            if($timestamp < time()) { //如果过期
            
                $temp = $list_1[$key];
                
                //删除过期的数据
                unset($list_1[$key]);
                
                //如果是年则排在月上面
                if($val['type'] == 10) {
                    $list_2[] = $temp;
                }else{
                    $list_3[] = $temp;
                }
            }

        }
        
        $list = array_merge($list_1, $list_2, $list_3);
        return $list;
    }
    
    /**
     * 获取守护我的人的列表（直播间）
     *
     * @param int $uid    主播uid
     * @param int $liveid 直播id
     */
    public static function getLiveGuardedList($uid, $liveid)
    {
        $list_1 = self::_getGuardedList($uid);
        $list_2 = array(); //年过期
        $list_3 = array(); //月过期
        $user = new User();
        $patroller = new Patroller();
        foreach($list_1 as $key=>$val){

            $list_1[$key]['time'] = date('Y-m-d H:i:s');
            $list_1[$key]['user'] = $user->getUserInfo($list_1[$key]['uid']);
            $list_1[$key]['isPatroller'] = (int)$patroller->isPatroller($list_1[$key]['uid'], $uid, $liveid);
            
            $timestamp = strtotime($val['endtime']);
            if($timestamp < time()) { //如果过期
            
                $temp = $list_1[$key];
                
                //删除过期的数据
                unset($list_1[$key]);
                
                //如果是年则排在月上面
                if($val['type'] == 10) {
                    $list_2[] = $temp;
                }else{
                    $list_3[] = $temp;
                }
            }

        }
        
        $list = array_merge($list_1, $list_2, $list_3);
        return $list;
    }

    /**
     * 购买守护
     *
     * @param int $uid            
     * @param int $relateid            
     * @param int $type            
     */
    public static function buy($uid, $relateid, $type,$liveid, &$endtime = false, &$receiveTicket = false)
    {
        $guard   = self::$guard[$type];
        Interceptor::ensureFalse(empty($guard),   ERROR_GUARD_NOT_EXIST);

        // 1,用户余额判断
        $account = new Account();
        $balance = $account->getBalance($uid, $account::CURRENCY_DIAMOND);
        Interceptor::ensureFalse($balance < $guard['price'], ERROR_BIZ_PAYMENT_ACCOUNT_BALANCE_LACK, $uid);
            
        // 2,判断是否购买过主播的守护
        $DAOUserGuard  = new DAOUserGuard();
        
        $userGuardInfo = $DAOUserGuard->getInfoByUidRelateid($uid, $relateid);
        $option = self::getUserGuard($userGuardInfo, $guard['type'], $guard['expires']);
        Interceptor::ensureFalse(empty($option),   ERROR_GUARD_DIAMONDS, $uid);
        
        // 3,守护数据处理
        $expires = strtotime($option['endtime']) - strtotime($option['addtime']);
        $result = self::addRedisBySet($uid, $relateid, $type, $expires);
        //echo $result;
        //exit;
        if ($result) {
            $userGuard = array(
                'uid' => $uid,
                'relateid' => $relateid,
                'type' => $guard['type'],
                'msg_status' => 0,
                'addtime' => $option['addtime'],
                'endtime' => $option['endtime']
            );
            $insertId = $DAOUserGuard->addData($userGuard);
            if (empty($insertId)) {
                self::delRedisBySet($uid, $relateid);
                Interceptor::ensureFalse(true, ERROR_GUARD_DIAMONDS, $uid);
            }
        }
        
        //返回守护到期时间
        if($endtime) {
            $endtime = $option['endtime'];    
        }
        
        // 4,扣减星钻
        $orderid = AccountInterface::minusGift($uid, $relateid, Account::TRADE_TYPE_GUARD, $guard['price'], Account::CURRENCY_DIAMOND, '购买守护', $userGuard);
        if (! $orderid) {
            
            //如果购买
            if(empty($userGuardInfo['endtime'])) {                
                self::delRedisBySet($uid, $relateid);
                $DAOUserGuard->del($insertId);
            }else{
                //更新内存时间
                $expires = strtotime($option['oldendtime']) - time();
                self::expireRedisBySet($uid, $relateid, $expires);
                
                //更新数据库时间
                $data = array();
                $data['addtime'] = $option['oldaddtime'];
                $data['endtime'] = $option['oldendtime'];
                $DAOUserGuard->updateData($insertId, $data);
            }
            Interceptor::ensureFalse(true, ERROR_BUY_GUARD, $uid);  
        }

        // 5,经验处理
        try {
            include_once dirname(__FILE__).DIRECTORY_SEPARATOR. "task". DIRECTORY_SEPARATOR. "VerifyTask.php";
            include_once dirname(__FILE__).DIRECTORY_SEPARATOR. "task". DIRECTORY_SEPARATOR. "AwardTask.php";
            $Task      = new Task();
            $taskInfo  = $Task->getTaskInfo(VerifyTask::TASK_TIIMES_GUARD_ID);
            $award     = AwardTask::getTaskAward($uid, VerifyTask::TASK_TIIMES_GUARD_ID, Task::TASK_TYPE_TIMES, 1, json_decode($taskInfo['extend'], true), array('price'=>$guard['price']));
            $result    = UserTaskAward::AddUserTaskAward($uid, VerifyTask::TASK_TIIMES_GUARD_ID, $award);
            $Task->addUserExp($uid, $award['exp'], $liveid);
            if($result) {
                $status = 'Y';
            }
        } catch (Exception $e) {
            $status = 'N';
        }

        // 6,写日志明细开始
        $DAOUserGuardDetail = new DAOUserGuardDetail();
        $userGuardDetail = array(
            'uid'      => $uid,
            'relateid' => $relateid,
            'type'     => $guard['type'],
            'price'    => $guard['price'],
            'status'   => $status,
            'orderid' => isset($orderid) ? $orderid : 0,
            'expires'  => $option['expires'],
            'addtime'  => date("Y-m-d H:i:s"),
            'endtime' => $option['endtime']
        );
        $DAOUserGuardDetail->addData($userGuardDetail);
        
        //返回主播星票余额
        $receiveTicket = Counter::increase(Counter::COUNTER_TYPE_PAYMENT_RECEIVE_GIFT, $relateid, $guard['price']);
        $live_ticket = Counter::increase(Counter::COUNTER_TYPE_LIVE_TICKET, $liveid, $guard['price']);
        // 刷新
        self::_reload($relateid);
        // 7,发消息
        $user = new User();
        $patroller = new Patroller();
        
        $sendUserInfo = $user->getUserInfo($uid); //购买者信息
        $receiveUserInfo = $user->getUserInfo($relateid); //主播信息
        $option = array(
            'send_uid' => $uid,
            'isPatroller' => (int)$patroller->isPatroller($uid, $relateid, $liveid),
            'send_nickname' => $sendUserInfo['nickname'],
            'receive_uid' => $relateid,
            'receiveTicket'=>$receiveTicket, //返回主播星票余额
            'receive_nickname' => $receiveUserInfo['nickname'],
            'medal' => $sendUserInfo['medal'],
            'avatar' => $sendUserInfo['avatar'], //购买者头像
            'level' => $sendUserInfo['level'], //购买者等级
            'type' => $guard['type'],
            'expires' => $guard['expires'],
        );
        
        $matchid = MatchMessage::getRedisMatchId($relateid);
        $match_score = 0;
        if (!empty($matchid)) {
            $match_score = MatchMessage::sendMatchMemberScore($uid, $relateid, $guard['price'], 'guard', $matchid);
        }
        
        if($guard['type']==10) {
            if ($liveid > 0) {
                $option['liveid'] = $liveid;
            }
            $option['match_score'] = $match_score;
            Messenger::sendGuardAll("购买守护", $option);
        }else{
            if ($liveid > 0) {
                $option['match_score'] = $match_score;
                Messenger::sendGuard($liveid, $uid, '守护', $option); //使用购买者uid发送
            }
        }
        
        
        // 8,写入榜单
        include_once "process_client/ProcessClient.php";
        ProcessClient::getInstance("dream")->addTask("live_rank_generate", array("type" => "protect", "action" => "increase", "userid" => $uid, "score" => $guard['price'], "relateid" => $relateid));
        ProcessClient::getInstance("dream")->addTask("live_rank_generate", array("type" => "sendgift", "action" => "increase", "userid" => $uid, "score" => $guard['price'], "relateid" => $uid));
        ProcessClient::getInstance("dream")->addTask("live_rank_generate", array("type" => "receivegift", "action" => "increase", "userid" => $relateid, "score" => $guard['price'], "relateid" => $relateid, "sender" => $uid, "liveid" => $liveid));
        ProcessClient::getInstance("dream")->addTask("user_guard_activity_add_worker", array("type" => $guard['type'], "uid" => $uid, "relateid" => $relateid, "liveid" => $liveid));
        
        //购买成功给购买者、主播发送消息
        Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $uid,  '守护', '恭喜您,在('.$receiveUserInfo['nickname'].')房间开通'.$guard['expires'].'天守护', 0, $extends = array());
        Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $relateid,  '守护', '('.$sendUserInfo['nickname'].')为您开通了'.$guard['expires'].'天守护', 0, $extends = array());

        //发送跑道消息
        Track::showTrackGuard($uid, $relateid, $type);
        
        return true;
    }
    
    

    /**
     * 是否购买过某个用户的守护
     *
     * @param  int $uid
     * @param  int $relateid
     * @param  int $expires
     * @return array
     */
    public static function getUserGuard($userGuardInfo,$type,$expires)
    {
        $time   = date("Y-m-d H:i:s");
        $option = array();
        if (empty($userGuardInfo)) {
            // 未购买守护
            $option['addtime'] = $time;
            $option['endtime'] = date("Y-m-d H:i:s", strtotime($option['addtime'] . " +$expires day"));
            $option['expires'] = round((strtotime($option['endtime']) - strtotime($option['addtime'])) / 3600 / 24);
        } else {
            // 购买过守护年守护且未到期,不允许购买月守护
            if ($userGuardInfo['type'] == self::GUARD_TYPE_YEAR && ($userGuardInfo['endtime'] > $time)) {
                Interceptor::ensureFalse($type == self::GUARD_TYPE_MONTH, ERROR_GUARD_TYPE_MONTH);
            }
            // 购买过守护,且已到期
            if ($userGuardInfo['endtime'] <= $time) {
                $option['oldaddtime'] = $userGuardInfo['addtime'];
                $option['oldendtime'] = $userGuardInfo['endtime'];
                $option['addtime'] = $time;
                $option['endtime'] = date("Y-m-d H:i:s", strtotime($option['addtime'] . " +$expires day"));
                $option['expires'] = round((strtotime($option['endtime']) - strtotime($option['addtime'])) / 3600 / 24);
            }
            // 购买过守护,未到期
            if ($userGuardInfo['endtime'] > $time) {
                $option['oldaddtime'] = $userGuardInfo['addtime'];
                $option['oldendtime'] = $userGuardInfo['endtime'];
                $option['addtime'] = $userGuardInfo['addtime'];
                $option['endtime'] = date("Y-m-d H:i:s", strtotime($userGuardInfo['endtime'] . " +$expires day"));
                $option['expires'] = round((strtotime($option['endtime']) - strtotime($option['addtime'])) / 3600 / 24);
            }
        }
        return $option;
    }
    
    /**
     * 写redis有序集合
     *
     * @param int $uid
     * @param int $relateid
     * @param int $type
     * @param int $expires
     */
    public static function addRedisBySet($uid,$relateid,$type,$expires)
    {
        $key   = self::_getKey($uid, $relateid);
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        if($cache->SET($key, $type)) {
            return $cache->EXPIRE($key, $expires);
        }
        return false;
    }
    
    /**
     * 获取用户守护
     *
     * @param int  $uid      用户uid
     * @param int  $relateid 主播uid
     * @param bool $rtime    是否返回过期时间
     *                       
     * @return int
     */ 
    public static function getUserGuardRedis($uid, $relateid, $rtime=false)
    {
        $key = self::_getKey($uid, $relateid);
        $cache = Cache::getInstance('REDIS_CONF_CACHE');
        $data['ttl'] = $cache->ttl($key);
        $data['type'] = $cache->get($key);
        
        if($rtime) {
            return $data;
        }else{
            return $data['type'];    
        }
        
    }
    
    /**
     * 更新内存有效期
     */ 
    public static function expireRedisBySet($uid, $relateid, $expires)
    {
        $key = self::_getKey($uid, $relateid);
        $cache = Cache::getInstance('REDIS_CONF_CACHE');
        return $cache->EXPIRE($key, $expires);
    }
    
    /**
     * 删除redis有序集合
     *
     * @param int $uid
     * @param int $relateid
     */
    public static function delRedisBySet($uid,$relateid)
    {
        $key   = self::_getKey($uid, $relateid);
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        return $cache->del($key);
    }
    
    /**
     * 获取redis的key
     *
     * @param int $uid      用户
     * @param int $relateid 主播
     */
    private static function _getKey($uid,$relateid)
    {
        return "user_guard_string_".$relateid."_".$uid;
    }

    public static function _getGuardedList($uid)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key = self::KEY_PREFIX . $uid;
        if (!($guardedList = $cache->get($key))) {
            $DAOUserGuard = new DAOUserGuard();
            $guardedList = $DAOUserGuard->getGuardedList($uid);

            $cache->setex($key, 600, json_encode($guardedList));
        }else{
            $guardedList = json_decode($guardedList, true);
        }

        return $guardedList;
    }
    public static function _reload($uid)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key = self::KEY_PREFIX . $uid;
        $cache->delete($key);

        self::_getGuardedList($uid);

        return true;
    }
}
