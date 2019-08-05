<?php
class Privacy
{
    const FREETIME = 30;//免费预览时间
    const DISCOUNT = 0.9; //守护买卖私密房间折扣
    
    const PRIVAACY_CACHE = 'privacy_cache_';

    public static function getPrivateRoomConfig()
    {
        try {
            $cache = Cache::getInstance("REDIS_CONF_CACHE");
            $key = "private_room_config";
            $privateRoomConfig = $cache->get($key);
            $privateRoomConfig = json_decode($privateRoomConfig, true);
            $arrTemp = array();
            if (! empty($privateRoomConfig)) {
                foreach ($privateRoomConfig as $key => $val) {
                    $long = floor(60 * 60 * $val['charge_time']);
                    $arrTemp[$long] = $val;
                }
            }
            return $arrTemp;
        } catch (Exception $e) {
            return $e;
        }
    }
    
    /**
     * 获取私密房间配置
     *
     * @param  int $uid
     * @return multitype:
     */
    public static function getConfig($uid)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key = "private_room_permissions_".$uid;
        $privateRoomPermissions = $cache->get($key);
        Interceptor::ensureNotEmpty(!empty($privateRoomPermissions), ERROR_BIZ_PRIVACY_PERMISSION);
        $privateRoomPermissions = json_decode($privateRoomPermissions, true);
        
        $key = "private_room_config";
        $privateRoomConfig = $cache->get($key);
        $privateRoomConfig = json_decode($privateRoomConfig, true);
        
        if(empty($privateRoomPermissions) || empty($privateRoomConfig)) {
            return array();
        }
        
        $price = array();
        foreach ($privateRoomConfig as $key => $val) {
            
            if (empty($val['replay_price'])) {
                $price[$key]['replay_price'] = 50;
            } else {
                $price[$key]['replay_price'] = intval($val['replay_price']);
            }
            if (in_array($uid, array(23519677))) {
                $price[$key]['live_price']   = 999999;
                $price[$key]['replay_price'] = 999999;
            } else {
                $price[$key]['live_price']   = intval($val['live_price']);
            }
            $price[$key]['seconds']      = floor(60 * 60 * $val['charge_time']);
            $price[$key]['freetime']     = intval($val['preview_time']);
        }
        
        $DAOPrivacy = new DAOPrivacy();
        $privacy  = $DAOPrivacy->liveExists($uid, time());
        $force = (! empty($privacy)) ? true : false;
        if($force) {
            $previous = array(
            'live_price' => $privacy['live_price'],
            'replay_price' => $privacy['replay_price'],
            'seconds' => strtotime($privacy['endtime']) - strtotime($privacy['startime']),
            'startime' => $privacy['startime'],
            'endtime' => $privacy['endtime'],
            'paylong' => strtotime($privacy['endtime']) - strtotime($privacy['startime']),
            'paytime' => $privacy['startime'],
            'preview' => ($privacy['preview'] == 'Y') ? true : false,
            'freetime' => $privacy['freetime'],
            );
        }else{
            $previous = new stdClass();
        }
        
        return array(
        'custom'   => $force ? false : $privateRoomPermissions['room_custom_ticket'] ? true : false,
        'force'    => $force,
        'previous' => $previous,
        'price'    => $price,
        "preview"  => true,//预览开关
        );
    }
    
    /**
     * 获取私密直播权限
     *
     * @param int $uid
     */
    public static function getPrivacy($uid)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key = "private_room_permissions_" . $uid;
        $privateRoomPermissions = $cache->get($key);
        $privateRoomPermissions = json_decode($privateRoomPermissions, true);
        if (! empty($privateRoomPermissions) && $privateRoomPermissions['room_type'] == 1) {
            return true;
        }
        return false;
    }
    
    /**
     * 回放权限
     *
     * @param  int $uid
     * @return boolean
     */
    public static function getPrivacyReplay($uid)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key = "private_room_permissions_" . $uid;
        $privateRoomPermissions = $cache->get($key);
        $privateRoomPermissions = json_decode($privateRoomPermissions, true);
        if (! empty($privateRoomPermissions) && $privateRoomPermissions['room_permissions'] == 1) {
            return true;
        }
        return false;
    }
    
    /**
     * 自定义回放价格上线
     *
     * @param  unknown $startime
     * @param  unknown $endtime
     * @return multitype:|mixed
     */
    public static function getReplayPrice($paylong)
    {
        $chargetime = round((($paylong) /3600), 1);
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key = "private_room_config";
        $privateRoomConfig = $cache->get($key);
        $privateRoomConfig = json_decode($privateRoomConfig, true);
        
        if(empty($privateRoomPermissions) || empty($privateRoomConfig)) {
            return 1000;
        }
        
        $price = 1000;
        foreach ($privateRoomConfig as $key => $val) {
            if ($chargetime == $val['charge_time']) {
                $price = $val['replay_price'];
                break;
            }
        }
        
        return $price;
    }
    
    /**
     * 是否具有自定义价格权限
     *
     * @param  int $uid
     * @return bool
     */
    public static function hasCustomPrice($uid)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key = "private_room_permissions_".$uid;
        $privateRoomPermissions = $cache->get($key);
        //Interceptor::ensureNotEmpty(!empty($privateRoomPermissions), ERROR_BIZ_PRIVACY_PERMISSION);
        $privateRoomPermissions = json_decode($privateRoomPermissions, true);
        $bool = false;
        
        if (!empty($privateRoomPermissions)) {
            $bool = $privateRoomPermissions['room_custom_ticket'] ? true : false;
        }
        
        return $bool;
    }
    
    /**
     * 购买私密房间
     *
     * @param int $privacyid
     * @param int $author
     * @param int $type
     * @param int $buyer
     */
    public static function buy($privacyid, $buyer, $liveid, $user_ip, $deviceid)
    {
        //1,验证privacyid
        $dao_privacy = new DAOPrivacy();
        $privacyInfo = $dao_privacy->getPrivacyInfoById($privacyid);
        Interceptor::ensureNotEmpty($privacyInfo, ERROR_BIZ_PRIVACY_LIVE_NOT_EXIST);
        
        //红V直接看不用买
        if(self::isPreviewPermissions($buyer)) {
            return true;
        }
        
        //2,验证直播类型$type
        $pay_start_time = strtotime($privacyInfo['startime']);
        $pay_end_time    = strtotime($privacyInfo['endtime']);
        $now             = time();
        $type = 1;
        if (($now >= $pay_start_time && $now <= $pay_end_time ) || $now < $pay_start_time ) {
            $price = $privacyInfo['live_price'];
            $type = 1;
            $account_type = Account::TRADE_TYPE_DOOR_TICKET;
        } else {
            if ($now > $pay_end_time) {
                $type = 2;
                $price = $privacyInfo['replay_price'];
                $account_type = Account::TRADE_TYPE_DOOR_TICKET_VIDEO;
            } else {
                $price = $privacyInfo['live_price'];
                $type = 1;
                $account_type = Account::TRADE_TYPE_DOOR_TICKET;
            }
        }
        
        $author = $privacyInfo['uid'];
        
        
        //判断是否是守护，守护购买9折
        $userGuard = UserGuard::getUserGuardRedis($buyer, $author);
        if(!empty($userGuard)) {
            $price = intval($price * self::DISCOUNT);
        }
        
        
        //3,验证重复购买
        $dao_privacy_journal = new DAOPrivacyJournal();
        Interceptor::ensureNotFalse(!($dao_privacy_journal->exists($privacyid, $buyer)), ERROR_BIZ_ERROR_HAS_BUYED_PRIVACY_LIVE);
        
        //4,用户余额判断
        $account = new Account();
        $balance = $account->getBalance($buyer, $account::CURRENCY_DIAMOND);
        Interceptor::ensureFalse($balance < $price, ERROR_BIZ_PAYMENT_ACCOUNT_BALANCE_LACK);
        
        //重复购买拦截器(并发)
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $buy_only_once_redis_key = "privacy_buy_". $buyer. '_' . $privacyid . '_' . $liveid;
        
        if ($cache->INCR($buy_only_once_redis_key) > 1) {
            return true;
        }
        
        try {
            //5,扣减星钻
            //$orderid = AccountInterface::minus($buyer, Account::ACTIVE_ACCOUNT9, Account::TRADE_TYPE_DOOR_TICKET, $price, Account::CURRENCY_DIAMOND, '门票收入', $privacyInfo);
            $orderid = AccountInterface::minusGift($buyer, $author, $account_type, $price, Account::CURRENCY_DIAMOND, '门票收入', $privacyInfo);
            if (! $orderid) {
                Interceptor::ensureFalse(true, ERROR_BUY_PRIVACY_ROOM_NOORDERID, $buyer);
            }
        } catch (Exception $e) {
            Logger::log("privacy_error", "AccountInterface::minusGift", array("privacyid" => $privacyid, "buyer" => $buyer, "errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
            Interceptor::ensureFalse(true, ERROR_BUY_PRIVACY_ROOM, $buyer);
        }
        
        $receive_ticket         = Counter::increase(Counter::COUNTER_TYPE_PAYMENT_RECEIVE_GIFT, $author, $price);
        $live_privacy_ticket     = Counter::increase(Counter::COUNTER_TYPE_LIVE_PRIVACY_TICKET, $privacyid, $price);//付费收益
        $buyers                 = Counter::increase(Counter::COUNTER_TYPE_LIVE_PRIVACY_WATCHES, $privacyid, 1);//付费人数
        //$watches                = Counter::get(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid);
        $watches                = Counter::increase(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid, 1);
        
        Logger::log("privacy_buy", "start", array("author"=> $privacyInfo["uid"],"privacyid" => $privacyid, "buyer" => $buyer, "liveid" => $liveid));
        if (!empty($liveid) && $type == 1) {
            $user = new User();
            $buyer_info = $user->getUserInfo($buyer);
            
            $matchid = MatchMessage::getRedisMatchId($author);
            $match_score = 0;
            if (!empty($matchid)) {
                $match_score = MatchMessage::sendMatchMemberScore($buyer, $author, $price, 'ticket', $matchid);
            }
            
            Messenger::sendBuyPrivacyRoom($liveid, "购买门票", $buyer, $buyer_info['nickname'], $buyer_info['avatar'], $buyer_info['level'], $author, $receive_ticket, $watches, $buyers, $match_score);
            
            //用户购买门票后，用户收到购买门票的主播一条消息
            $user = new User();
            $userinfo = $user->getUserInfo($privacyInfo["uid"]);
            $relation = Follow::relation($buyer, $privacyInfo["uid"]);//接收者和发送者的关系
            $json = ['contentType' => 0, "content" => '感谢亲的支持哦～', "description" => ""];
            Messenger::sendPrivateMessage($buyer, $privacyInfo["uid"], $userinfo["nickname"], $userinfo["avatar"], $userinfo["level"], $relation,  json_encode($json), $match_score, $user_ip, $deviceid, 1);
            Logger::log("privacy_buy", "sended ok", array("author"=> $privacyInfo["uid"],"privacyid" => $privacyid, "buyer" => $buyer, "liveid" => $liveid, "send_result" => $send_bool));
            
        }
        
        include_once "process_client/ProcessClient.php";
        ProcessClient::getInstance("dream")->addTask("live_rank_generate", array("type" => "protect", "action" => "increase", "userid" => $buyer, "score" => $price, "relateid" => $author));
        ProcessClient::getInstance("dream")->addTask("live_rank_generate", array("type" => "sendgift", "action" => "increase", "userid" => $buyer, "score" => $price, "relateid" => $buyer));
        ProcessClient::getInstance("dream")->addTask("live_rank_generate", array("type" => "receivegift", "action" => "increase", "userid" => $author, "score" => $price, "relateid" => $author, "sender" => $buyer, "liveid" => $liveid));
        
        //6,写记录明细
        try {
            
            $write_result = $dao_privacy_journal->addPrivacyJournal($privacyid, $privacyInfo['uid'], $buyer, $type, $price, $orderid);
            
            $buy_privacy_cache_key = "privacy_room_buyeduser_" . $privacyid;
            
            if ($write_result) {
                $cache->zAdd($buy_privacy_cache_key, time(), $buyer);
            }
        } catch (Exception $e) {
            Logger::log("privacy_error", "privacy_room_buyeduser", array("privacyid" => $privacyid, "buyer" => $buyer, "errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
            //Interceptor::ensureFalse(true, ERROR_BUY_PRIVACY_ROOM_ADDLOG, $buyer);
        }
        
        return true;
    }
    
    /**
     * 获取私密直播信息
     *
     * @param  array $liveinfo
     * @return multitype:
     */
    public static function getPrivacyInfo($uid, $startime, $endtime, $liveid, $privacyInfo)
    {
        $userid = Context::get("userid");
        //$privacyInfo = self::hasPrivacyLive($uid, $startime, $endtime, $liveid);
        
        //if (! empty($privacyInfo)) {
        //$dao_privacy_journal = new DAOPrivacyJournal();
        $privacyid = $privacyInfo['privacyid'];
        //$has_buyed = $dao_privacy_journal->exists($privacyid, $userid);
            
        $has_buyed = self::buyedExist($privacyid, $userid);
        $privacyInfo['preview'] = false;//($privacyInfo['preview'] == 'Y') ? true : false;
            
        if (empty($privacyInfo['freetime'])) {
            $privacyInfo['preview'] = false;
        }
            
        //观看权限(购买过或具备观看权限)
        if ($has_buyed || self::isPreviewPermissions($userid) || self::checkIsCanWatchPrivateRoom($privacyInfo['uid'], $userid) || self::checkIsOperation($privacyInfo['uid'], $userid)) {
            $privacyInfo['buyed'] = true;
            $privacyInfo['previewed'] = true;
        } else {
            //$dao_privacy_preview = new DAOPrivacyPreview();
            $privacyInfo['buyed'] = false;
            //$has_previewed = $dao_privacy_preview->exists($privacyid, $userid);
            $privacyInfo['previewed'] = true;
        }
        //}
        
        return $privacyInfo;
    }
    
    /**
     * 观看权限
     *
     * @param  int $userid
     * @return boolean
     */
    public static function isPreviewPermissions($userid)
    {
        $flags    = false;
        $user      = new User();
        $userInfo = $user->getUserInfo($userid);
        if (! empty($userInfo['medal'])) {
            foreach ($userInfo['medal'] as $item) {
                if($item['kind']=='v' && $item['medal']=='red') {
                    $flags = true;
                }
            }
        }
        return $flags;
    }
    
    /**
     * 是否是私密直播
     *
     * @param int $uid
     * @param int $startime
     * @param int $endtime
     */
    public static function hasPrivacyLive($uid, $startime, $endtime, $liveid)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $privacy_cache_key = self::PRIVAACY_CACHE . $liveid;
        $dao_privacy = new DAOPrivacy();
        $privacyInfo = array();
        
        $privacyid = $cache->get($privacy_cache_key);
        
        if ($endtime == '0000-00-00 00:00:00' && empty($privacyid)) {
            return $privacyInfo;
        }
        
        if (! empty($privacyid)) {
            $privacy_info_redis_key = "hmset_privacy_info_" . $privacyid;
            $privacyInfo = $cache->hgetall($privacy_info_redis_key);
            //$privacyInfo = $dao_privacy->getPrivacyInfoById($privacyid);
            return $privacyInfo;
        } else {
            if ($endtime == '0000-00-00 00:00:00') {
                // 直播
                $privacyInfo = $dao_privacy->exists($uid, $startime);
            } else {
                // 回放
                $privacyInfo = $dao_privacy->getPrivacy($uid, $startime, $endtime);
            }
            
            if (! empty($privacyInfo)) {
                $cache->set($privacy_cache_key, $privacyInfo['privacyid']);
            }
        }
        
        return $privacyInfo;
    }
    
    /**
     * 延长收费时间
     *
     * @param  int $privacyid
     * @param  int $uid
     * @param  int $time
     * @return array:
     */
    public static function addTime($privacyid, $uid, $time)
    {
        $dao_privacy = new DAOPrivacy();
        
        $privacyInfo = $dao_privacy->getPrivacyInfoById($privacyid);
        Interceptor::ensureNotFalse(strtotime($privacyInfo['endtime']) > time(), ERROR_BIZ_PRIVACY_DELAYTIME);
        Interceptor::ensureNotEmpty($privacyInfo, ERROR_BIZ_PRIVACY_LIVE_NOT_EXIST);
        Interceptor::ensureNotFalse($privacyInfo['uid'] == $uid, ERROR_BIZ_PRIVACY_LIVE_NOT_YOURSELF);
        
        $ends = strtotime($privacyInfo['endtime']);
        $times = ($ends + $time);
        
        try {
            $result = $dao_privacy->updateData($privacyid, $times);
            
            if ($result) {
                self::setPrivacyToRedis($privacyid);
            }
            $privacyInfo = $dao_privacy->getPrivacyInfoById($privacyid);
            $DAOLive = new DAOLive();
            $privacyList = $DAOLive->getListByPrivacy($uid, $privacyInfo['startime'], $privacyInfo['endtime']);
            $live = new Live();
            foreach($privacyList as $item){
                self::setPrivacyToLive($privacyid, json_decode($item['privacy'], true), $item['liveid']);
                $live->_reload($item['liveid']);
            }
        } catch (Exception $e) {
            Interceptor::ensureFalse(true, ERROR_DELAY_PRIVACY_ROOM, $uid);
        }
        
        return true;
        
    }
    
    /**
     * 预览
     *
     * @param int $privacyid
     * @param int $uid
     */
    public static function preview($privacyid,$uid)
    {
        //1,验证用户是否预览
        $dao_privacy_preview = new DAOPrivacyPreview();
        Interceptor::ensureNotFalse(!$dao_privacy_preview->exists($privacyid, $uid), ERROR_BIZ_PRIVACY_LIVE_HAS_PREVIEWED);
        
        //2,写预览记录
        return $dao_privacy_preview->addPrivacyPreview($privacyid, $uid);
    }
    
    /**
     * 主动结束私密直播
     *
     * @param int $privacyid
     * @param int $uid
     * @param int $liveid
     */
    public static function stop($privacyid, $uid, $liveid)
    {
        $dao_privacy = new DAOPrivacy();
        $privacyInfo = $dao_privacy->getPrivacyInfoById($privacyid);
        Interceptor::ensureNotFalse($privacyInfo['uid'] == $uid, ERROR_BIZ_PRIVACY_LIVE_NOT_YOURSELF);
        
        return $dao_privacy->stop($privacyid);
    }
    
    /**
     * 修改回放价格
     *
     * @param int   $privacyid
     * @param int   $uid
     * @param float $price
     */
    public static function modifyPrice($privacyid, $uid, $replay_price)
    {
        $dao_privacy = new DAOPrivacy();
        
        $privacyInfo = $dao_privacy->getPrivacyInfoById($privacyid);
        Interceptor::ensureNotEmpty($privacyInfo, ERROR_BIZ_PRIVACY_LIVE_NOT_EXIST);
        Interceptor::ensureNotFalse($privacyInfo['uid'] == $uid, ERROR_BIZ_PRIVACY_LIVE_NOT_YOURSELF);
        
        try {
            $result = $dao_privacy->modifyReplayPrice($privacyid, $replay_price);
            
            if ($result) {
                self::setPrivacyToRedis($privacyid);
            }
        } catch (Exception $e) {
            Interceptor::ensureFalse(true, ERROR_MODIFY_PRICE_PRIVACY_ROOM, $uid);
        }
        
        return true;
    }
    
    /**
     * 添加私密直播
     *
     * @param  int     $liveid
     * @param  int     $uid
     * @param  unknown $sn
     * @param  unknown $partner00
     * @param  unknown $title
     * @param  unknown $width
     * @param  unknown $height
     * @param  unknown $point
     * @param  unknown $province
     * @param  unknown $city
     * @param  unknown $district
     * @param  unknown $location
     * @param  unknown $virtual
     * @param  unknown $request_extends
     * @param  unknown $replay
     * @param  unknown $cover
     * @param  unknown $live_price
     * @param  unknown $replay_price
     * @param  unknown $paytime
     * @param  unknown $paylong
     * @param  unknown $preview
     * @throws BizException
     * @return multitype:multitype:unknown
     */
    public function add($liveid, $uid, $sn, $partner, $title, $width, $height, $point, $province, $city, $district, $location, $virtual, $request_extends, $replay, $cover, $live_price, $replay_price, $paytime, $paylong, $preview, $freetime)
    {
        $live = new Live();
        // clean residual live
        $dao_live = new DAOLive();
        $user_live_list = $dao_live->getUserActiveLives($uid);
        
        foreach ($user_live_list as $user_live_info) {
            $extends = json_decode($user_live_info["extends"], true);
            //同一个账户不允许多个设备开播
            if (!empty($user_live_info)) {
                Interceptor::ensureNotFalse(false, ERROR_BIZ_CHATROOM_ONLY_OPEN_ONE);
                //$live->clean($user_live_info["liveid"], '重复直播删除');
                
            }
            
            if (isset($extends["deviceid"]) && $extends["deviceid"] == Context::get("deviceid")) {
                $live->clean($user_live_info["liveid"], '重复直播删除');
            }
        }
        
        Interceptor::ensureNotFalse($liveid > 0, ERROR_BIZ_COUNTER_BUSY_RETRY);
        
        $extends = array(
        "network"   => Context::get("network"),
        "brand"     => Context::get("brand"),
        "model"     => Context::get("model"),
        "version"   => Context::get("version"),
        "platform"  => Context::get("platform"),
        "deviceid"  => Context::get("deviceid"),
        "ip"        => Util::getIP()
        );
        
        if (is_array($request_extends)) {
            $extends = array_merge($extends, $request_extends);
        }
        
        $user = new User();
        $user_info = $user->getUserInfo($uid);
        
        $region = Context::get("region");
        $cover = $cover ? $cover :$user_info['avatar'];
        
        //是否带录制
        $record  = 'N';
        if($live->isPeplayPermissions(Context::get("userid")) || Privacy::getPrivacyReplay(Context::get("userid"))) {
            $record = 'Y';
        }
        
        $dao_live = new DAOLive();
        try {
            $dao_live->startTrans();
            
            $dao_privacy = new DAOPrivacy();
            
            $privacyInfo = $dao_privacy->repeatPrivacyLive($uid, $paytime);
            
            if (empty($privacyInfo)) {
                $privacyid = $dao_privacy->addPrivacy($uid, $live_price, $replay_price, $paytime, $paylong, $preview, $freetime);
                self::setPrivacyToRedis($privacyid);
            } else {
                $privacyid = $privacyInfo['privacyid'];
                self::setPrivacyToRedis($privacyid, $privacyInfo);
            }
            $cache = Cache::getInstance("REDIS_CONF_CACHE");
            $privacy_cache_key = self::PRIVAACY_CACHE . $liveid;
            $cache->set($privacy_cache_key, $privacyid);
            
            
            
            $dao_live->addLive($liveid, $uid, $sn, $partner, $title, $width, $height, $point, $province, $city, $district, $location, $virtual, $extends, $replay, $region, $cover, $record, '');
            
            $dao_user_feeds = new DAOUserFeeds($uid);
            $dao_user_feeds->addUserFeeds($uid, Feeds::FEEDS_LIVE, $liveid, date("Y-m-d H:i:s"));
            
            $dao_feed = new DAOFeeds;
            $dao_feed->addFeeds(Context::get("userid"), $liveid, Feeds::FEEDS_LIVE);
            
            self::setPrivacyToLive($privacyid, array(), $liveid);
            $dao_live->commit();
        } catch (MySQLException $e) {
            $dao_live->rollback();
            throw new BizException(ERROR_SYS_DB_SQL);
        }
        
        $total = $dao_user_feeds->getUserFeedsTotal($uid);
        Counter::set(Counter::COUNTER_TYPE_PASSPORT_FEEDS_NUM, $uid, $total);
        
        $result = array(
        "live" => array(
                        "liveid"     => $liveid,
                        "privacyid"    => $privacyid
        )
        );
        // flush cache
        $live_info = $live->getLiveInfo($liveid);
        
        // sync to admin
        
        return $result;
    }
    /**
     * 判断是否是运营
     *
     * @param  int $author
     * @param  int $userid
     * @return boolean
     */
    static public function checkIsOperation($author, $userid)
    {
        //取消官方运营账号
        return false;
        //运营人员帐号
        $uid_array_users = Operation::getUidsArray();
        
        if (!empty($uid_array_users) && in_array($userid, $uid_array_users)) {
            
            return true;
        }
        
        return false;
    }
    
    /**
     * 查看是否可以免费观看私密直播
     *
     * @param  author 主播
     * @param  userid 用户
     * @return true 可以 false 不可以
     */
    static public function checkIsCanWatchPrivateRoom($author, $userid)
    {
        $cache = new Cache("REDIS_CONF_CACHE");
        $white_live_uid_key = "private_room_white_users_keys";
        
        $white_user_info = json_decode($cache->get($white_live_uid_key), true);
        
        if (empty($white_user_info)) {
            return false;
        }
        
        if (in_array($author, array_keys($white_user_info))) {//此类型主播私密房间有白名单
            $detail_info     = $white_user_info[$author];
            
            $starttime         = !empty($detail_info['starttime']) ? strtotime($detail_info['starttime']) : 0 ;
            $endtime        = !empty($detail_info['endtime']) ? strtotime($detail_info['endtime']) : 0 ;
            $white_user_ids = !empty($detail_info['relateids']) ? explode(',', $detail_info['relateids']) : array();
            
            $new_white_user_list = [];
            foreach ($white_user_ids as $item) {
                $new_white_user_list[] = trim($item);
            }
            
            $new_white_user_list[] = $author;
            
            $white_user_ids = $new_white_user_list;
            
            $now = time();
            if (empty($starttime) && empty($endtime) && empty($white_user_ids)) {
                return false;
            }
            
            if ($now >= $starttime && $now <= $endtime) {//有时间范围设置
                if (!empty($white_user_ids) && in_array($userid, $white_user_ids)) {//有白名单设置
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
        
        return false;
    }
    
    /**
     * 验证是否购买
     *
     * @param int $privacyid
     * @param int $userid
     */
    static public function buyedExist($privacyid, $userid)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $buy_privacy_cache_key = "privacy_room_buyeduser_" . $privacyid;
        
        $exist = $cache->zScore($buy_privacy_cache_key, $userid);
        if (empty($exist)) {
            return false;
        } else {
            return true;
        }
    }
    
    /**
     * hash私密直播信息
     *
     * @param  int   $privacyid
     * @param  array $privacyInfo
     * @return boolean
     */
    static public function setPrivacyToRedis($privacyid, $privacyInfo = array())
    {
        
        try{
            $cache = Cache::getInstance("REDIS_CONF_CACHE");
            if (empty($privacyInfo)) {
                $dao_privacy = new DAOPrivacy();
                $privacyInfo = $dao_privacy->getPrivacyInfoById($privacyid);
            }
            $privacy_info_redis_key = "hmset_privacy_info_" . $privacyid;
            $cache->hmset($privacy_info_redis_key, $privacyInfo);
            
        } catch (Exception $e) {
            Logger::log("privacy_error", "setPrivacyToRedis", array("privacyid" => $privacyid, "privacyinfo" => json_encode($privacyInfo), "errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
        }
        
        return true;
    }
    /**
     * 修改私密直播字段
     *
     * @param array $liveInfo
     * @param int   $privacyid
     */
    public static function setPrivacyToLive($privacyid,$privacy,$liveid)
    {
        $dao_privacy = new DAOPrivacy();
        $privacyInfo = $dao_privacy->getPrivacyInfoById($privacyid);
        if(!empty($privacy)) {
            $flags = false;
            foreach($privacy as $key=>$val){
                if($val['privacyid'] == $privacyInfo['privacyid']) {
                    $privacy[$key] = $privacyInfo;
                    $flags = true;
                }
            }
            if(!$flags) {
                $privacy[] = $privacyInfo;
            }
        }else{
            $privacy = array($privacyInfo);
        }

        $DAOLive = new DAOLive();
        return $DAOLive->setPrivacy($liveid, json_encode($privacy));
    }
    
    public function remove($privacyid)
    {
        $dao_privacy = new DAOPrivacy();
        return $dao_privacy->remove($privacyid);
    }
    
    /**
     * 获取私密详情
     *
     * @param  array $privacy
     * @return boolean|multitype:
     */
    public static function getPrivacyInfoByLiveInfo($privacy)
    {
        if(empty($privacy)) {
            return array();
        }
        $time = date('Y-m-d H:i:s');
        $privacyInfo = array();
        foreach($privacy as $item){
            //$privacyInfo = $item;
            if($item['startime']<=$time && $item['endtime']>=$time) {
                $privacyInfo = $item;
            }
        }
        return $privacyInfo;
    }
    
    public static function getReplayPrivacyInfoByLiveInfo($privacy)
    {
        if(empty($privacy)) {
            return array();
        }
        return $privacy[0];
    }
    
    public function refund($id, $reason)
    {
        $dao_privacy_journal = new DAOPrivacyJournal();
        
        $option = [];
        $option['isrefund'] = 'Y';
        $option['modtime'] = date('Y-m-d H:i:s');
        $option['reason']  = $reason;
        return $dao_privacy_journal->updateData($id, $option);
    }
}
