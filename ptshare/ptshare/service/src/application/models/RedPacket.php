<?php
class RedPacket
{
    const REDPACKET_NUM                = 20;        // 红包数量
    const REDPACKET_EXPIRE             = 86400 * 3; // 红包过期时间
    const REDPACKET_EXPIRE_TIMES       = 120;       // 红包过期时间
    const REDPACKET_REDIS_SCORE_STEP   = 1000;      // redis有序集合score步长
    const REDPACKET_USER_DAYTIMES      = 5;         // 用户每天可领取红包次数


    const REDPACKET_SEND               = 'send';    // 我发的红包被哪些人领取
    const REDPACKET_RECEIVE            = 'receive'; // 我领取哪些人发的红包

    const REDPACKET_TYPE_RAND          = 1;         // 红包类型:随机红包

    // 正常领取的随机数
    private static $redPacketTypeRandNormal = array(
        'min' => 3,
        'max' => 5
    );

    // 新人领取红包随机数
    private static $redPacketTypeRandNew = array(
        'min' => 8,
        'max' => 10
    );

    // 手气最佳
    public static $redPacketTypeRandBest = array(
        'min' => 6,
        'max' => 8
    );

    /**
     * 创建分享红包
     * @param int $uid
     * @return boolean
     */
    public static function createShareRedPacket($userid)
    {
    	$userinfo= User::getUserInfo($userid);
    	Interceptor::ensureNotEmpty($userinfo, ERROR_USER_NOT_EXIST);

        $DAORedPacket = new DAORedPacket();
        if (($redid = $DAORedPacket->isExistRedPacket($userid, self::REDPACKET_TYPE_RAND)) > 0) {
            return $redid;
        }

        try {
            $redid = $DAORedPacket->addRedPacket($userid, self::REDPACKET_TYPE_RAND, self::REDPACKET_NUM);
            $cache = Cache::getInstance("REDIS_CONF_CACHE");
            $cache->add(self::getRedPacketStringKey($userid, $redid), self::REDPACKET_NUM, self::REDPACKET_EXPIRE);
        } catch (Exception $e) {
            throw new BizException($e->getMessage());
        }
        return $redid;
    }

    /**
     * 红包分享成功回调
     * @param int $userid
     * @param int $redid
     * @throws BizException
     * @return boolean
     */
    public static function groupRedPacketSuccess($uid, $redid)
    {
        try {
            $DAORedPacket = new DAORedPacket();
            $DAORedPacket->updateRedPacketStatus($redid);

            $cache = Cache::getInstance("REDIS_CONF_CACHE");
            $cache->EXPIRE(self::getRedPacketStringKey($uid, $redid), self::REDPACKET_EXPIRE);
        } catch (Exception $e) {
            throw new BizException($e->getMessage());
        }
        return true;
    }

    /**
     * 领取群红包
     * @param int $userid
     * @param int $uid
     * @param int $redid
     * @return array
     */
    public static function receiveGroupRedPacket($userid, $uid, $redid, $newUser)
    {
    	//$dao_user = new DAOUser();
   		//$userinfo = $dao_user->getUserInfo($uid);
    	$userinfo= User::getUserInfo($uid);
    	Interceptor::ensureNotEmpty($userinfo, ERROR_USER_NOT_EXIST);

        $cache = Cache::getInstance("REDIS_CONF_CACHE");

        // 用户抢红包今日次数验证
        $times = $cache->get(self::getRedPacketStringDayTimesKey($userid));
        Interceptor::ensureNotFalse(($times !== false || ! ($times > self::REDPACKET_USER_DAYTIMES)), ERROR_BIZ_PACKET_RCEIVE_DAYTIMES, "userid");

        // 是否领取过当前红包
        Interceptor::ensureNotFalse((false == $cache->ZSCORE(self::getRedPacketSortedSetKey($uid, $redid), $userid)), ERROR_BIZ_PACKET_RCEIVE_FINISH, "uid");

        // 红包是否过期
        Interceptor::ensureNotFalse($cache->EXISTS(self::getRedPacketStringKey($uid, $redid)), ERROR_BIZ_PACKET_IS_EXPIRE, "redid");

        // 红包是否抢完
        Interceptor::ensureNotFalse(($sort = $cache->decr(self::getRedPacketStringKey($uid, $redid)) >= 0), ERROR_BIZ_PACKET_RCEIVE_EMPTY, "redid");

        // 生成红包金额
        $best = false;
        if (! $cache->get(self::getRedPacketStringBestKey($uid, $redid))) {
            if (true != $newUser  && 1 == rand(1, $cache->get(self::getRedPacketStringKey($uid, $redid)))) {
                $best = true;
                $cache->add(self::getRedPacketStringBestKey($uid, $redid), $userid);
            }
        }
        $redPacketRand = self::$redPacketTypeRandNormal;
        $flags = 0;
        if ($best) {
            $redPacketRand = self::$redPacketTypeRandBest;
            $flags = 2;
        }
        if ($newUser) {
            $redPacketRand = self::$redPacketTypeRandNew;
            $flags = 1;
        }
        $amount = rand($redPacketRand['min'], $redPacketRand['max']);

        try {
            $daoTask = new DAORedPacket();
            $daoTask->startTrans();

            // 我领取哪些人发的红包
            $redPacketLogId = RedPacketLog::addRedPacketLog($userid, $uid, $redid, self::REDPACKET_RECEIVE, $amount, $flags);

            // 我发的红包被哪些人领取
            RedPacketLog::addRedPacketLog($uid, $userid, $redid, self::REDPACKET_SEND, $amount, $flags);

            $daoTask->commit();
        } catch (Exception $e) {
            $daoTask->rollback();
            throw new BizException($e->getMessage());
        }

        // 添加领取过红包redis用户列表
        self::addRedPacketSortedSet($userid, $uid, $redid, $amount, $newUser, $best);

        // 用户今日抢红包次数
        $cache->INCR(self::getRedPacketStringDayTimesKey($userid));
        $cache->EXPIRE(self::getRedPacketStringDayTimesKey($userid), self::REDPACKET_EXPIRE);

        // 领完红包发红包奖励
        try {
            $userInfo = User::getUserInfo($uid);
            $remark  = "每日群红包";
            $orderid = Account::addGrapeRedPacket($userid, $amount, $remark);
            RedPacketLog::updateRedPacketLog($userid, $redPacketLogId, $orderid);
        } catch (Exception $e) {
            throw new BizException($e->getMessage());
        }

        return array($uid,$redid,$amount,$flags);
    }

    /**
     * 获取抢红包用户列表
     * @param int $uid
     * @param int $redid
     * @return array
     */
    public static function getRedPacketList($uid, $redid)
    {
        $arrTemp = $uids = array();

        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $list = $cache->ZRANGE(self::getRedPacketSortedSetKey($uid, $redid), 0, - 1, 'WITHSCORES');
        foreach ($list as $key => $val) {
            array_push($uids, $key);
        }
        $user = new User();
        $userInfos = $user->getUserInfos($uids);

        $best = false;
        if(count($list) == 20){
            $best = true;
        }

        foreach ($list as $key => $val) {
            $temp = explode('.', $val);
            $decimal = intval($temp[1]);
            $type    = in_array(intval($decimal % 100), array(1,2)) ? intval($decimal % 100) : 0;
            if ($type == 2) {
                if ($best) {
                    $type = 2;
                } else {
                    $type = 0;
                }
            }
            $redPacket = array(
                'amount'   => intval($decimal/100),
                'datetime' => date('Y-m-d H:i:s',$temp[0]),
                'redid'    => $redid,
                'type'     => $type,
            );
            $arrTemp[] = array(
                'redPacket' => $redPacket,
                'userInfo'  => $userInfos[$key]
            );
        }
        return $arrTemp;
    }

    /**
     * 抢红包程序列表
     * @param int $userid
     * @param int $uid
     * @param int $redid
     * @param int $amount
     * @param int $sort
     * @return int
     */
    private static function addRedPacketSortedSet($userid, $uid, $redid, $amount, $newUser, $best)
    {
        $decimal = 0.0009;
        if($newUser){
            $decimal = 0.0001;
        }
        if($best){
            $decimal = 0.0002;
        }
        $decimal = $amount/100 + $decimal;

        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $score = time() + $decimal;
        return $cache->ZADD(self::getRedPacketSortedSetKey($uid, $redid), $score, $userid);
    }

    /**
     * 红包计数器redis的key
     * @param int $uid
     * @param int $redid
     * @return string
     */
    private static function getRedPacketStringKey($uid, $redid)
    {
        return "red_packet_string_" . $uid . "_" . $redid;
    }

    private static function getRedPacketStringBestKey($uid, $redid)
    {
        return "red_packet_string_best_" . $uid . "_" . $redid;
    }

    /**
     * 抢红包用户redis有序集合key
     * @param int $uid
     * @param int $redid
     * @return string
     */
    private static function getRedPacketSortedSetKey($uid, $redid)
    {
        return "red_packet_sortedset_" . $uid . "_" . $redid;
    }

    /**
     * 用户抢红包每天限制次数
     * @param int $uid
     * @return string
     */
    private static function getRedPacketStringDayTimesKey($uid)
    {
        $day = date('Ymd');
        return "red_packet_string_" . $day . "_". $uid;
    }
}