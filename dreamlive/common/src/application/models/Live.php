<?php
class Live
{
    const ACTIVING = 1; // 进行中
    const FINISHED = 2; // 正常结束
    const PAUSED   = 3; // 暂停
    const FORBIDED = 4; // 运营停播
    const DELETED  = 5; // 正常删除
    const CLEANED  = 6; // 运营删除
    const REPEATED = 7; // 重复清理
    const RECLICT  = 8; // 僵尸直播清残留
    const CRUMBLE  = 9; // 崩溃直播
    
    const SQUARE_CHATROOM_ID = 1;//广场直播间id 固定写死的
    const REPLAY_PRAISE_REDIS_KEY = 'replay_praise';

    public function getLiveInfoByUids($uids)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $dao_live = new DAOLive();
        $live_info_all = $dao_live->getLiveInfoByUids($uids);

        $activing_live = array();
        foreach ($live_info_all as $value) {
            $live_info = $value;
            $liveid = $value['liveid'];
            $watchs_total          = Counter::get(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid);
            $praises_total         = Counter::get(Counter::COUNTER_TYPE_LIVE_PRAISES, $liveid);
            $profit_total         = Counter::get(Counter::COUNTER_TYPE_LIVE_TICKET, $liveid);
            $key = "dreamlive_live_user_real_num_".$liveid;
            $result = json_decode($cache->get($key), true);
            $real_total = $result['num']? $result['num'] : 0;
            $userinfo             = User::getUserInfo($live_info["uid"]);

            $live_info['author'] = $userinfo;
            $live_info['watches'] = $watchs_total;
            $live_info['praises'] = (int)$praises_total;
            $live_info['profit'] = $profit_total ? (int)$profit_total : 0;
            $live_info['realaudience'] = $real_total;
            $live_info['extends']    = json_decode($live_info['extends'], true);
            $activing_live[$live_info['uid']] = $live_info;
        }

        foreach ($uids as $uid) {
            if (!in_array($uid, array_keys($activing_live))) {
                $activing_live[$uid] = false;
            }
        }

        return $activing_live;
    }

    public function getLiveInfoBySn($sn, $partner)
    {
        $super_popular_users = Context::getConfig("SUPER_POPULAR_USERS");
        // 加上本地cache
        $L1_cache_key = "L1_cache_live_".$sn."_".$partner;
        $live_info = LocalCache::get($L1_cache_key);

        if ($live_info) {
            $live_info = json_decode($live_info, true);
            $liveid = $live_info['liveid'];
            //房间人数自动加1
            $watchs_total          = Counter::get(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid);
            $praises_total         = Counter::get(Counter::COUNTER_TYPE_LIVE_PRAISES, $liveid);
            $profit_total         = Counter::get(Counter::COUNTER_TYPE_LIVE_TICKET, $liveid);

            $live_info['watches'] = $watchs_total;
            $live_info['praises'] = (int)$praises_total;
            $live_info['profit'] = $profit_total ? (int)$profit_total : 0;
            $live_info["L1_cached"] = true;

            return $live_info;
        }

        $L2_cache_key = "L2_cache_live_".$sn."_".$partner;
        $cache = Cache::getInstance("REDIS_CONF_CACHE", $sn.$partner);

        if (false !== ($live_info = $cache->get($L2_cache_key))) {
            $live_info = json_decode($live_info, true);

            if (empty($live_info)) {
                return array();
            }
            $liveid = $live_info['liveid'];
            //房间人数自动加1
            $watchs_total          = Counter::get(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid);
            $praises_total         = Counter::get(Counter::COUNTER_TYPE_LIVE_PRAISES, $liveid);
            $profit_total         = Counter::get(Counter::COUNTER_TYPE_LIVE_TICKET, $liveid);

            if (in_array($live_info["uid"], $super_popular_users)) {
                LocalCache::set($L1_cache_key, $live_info, 60);
            }

            $live_info['watches'] = $watchs_total;
            $live_info['praises'] = (int)$praises_total;
            $live_info['profit'] = $profit_total ? (int)$profit_total : 0;
            $live_info["L2_cached"] = true;
            $live_info['province']     = str_replace('市', '', $live_info['province']);
            $live_info['city']         = str_replace('市', '', $live_info['city']);
            $live_info['district']     = str_replace('市', '', $live_info['district']);

            return $live_info;
        }
        $dao_live = new DAOLive();
        $live_info = $dao_live->getLiveInfoBySn($sn, $partner);

        if (!empty($live_info)) {
            $liveid = $live_info['liveid'];
            //房间人数自动加1
            $watchs_total          = Counter::get(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid);
            $praises_total         = Counter::get(Counter::COUNTER_TYPE_LIVE_PRAISES, $liveid);
            $profit_total         = Counter::get(Counter::COUNTER_TYPE_LIVE_TICKET, $liveid);

            $user = new User();
            $user_info = $user->getUserInfo($live_info["uid"]);
            if (!empty($user_info)) {
                $live_info["author"] = $user_info;
            }
            $live_info['extends']    = json_decode($live_info['extends'], true);
            $live_info['watches'] = $watchs_total;
            $live_info['praises'] = (int)$praises_total;
            $live_info['province']     = str_replace('市', '', $live_info['province']);
            $live_info['city']         = str_replace('市', '', $live_info['city']);
            $live_info['city']         = str_replace('土家族', '', $live_info['city']);
            $live_info['city']         = str_replace('苗族', '', $live_info['city']);
            $live_info['city']         = str_replace('自治州', '', $live_info['city']);
            $live_info['district']     = str_replace('市', '', $live_info['district']);

            $cache->add($L2_cache_key, json_encode($live_info), 86400);
        }

        if (in_array($live_info["uid"], $super_popular_users)) {
            LocalCache::set($L1_cache_key, json_encode($live_info), 60);
        }

        return $live_info;
    }

    public function getLiveInfo($liveid)
    {
        $super_popular_users = Context::getConfig("SUPER_POPULAR_USERS");

        $watchs_total      = Counter::get(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid);
        $praises_total     = Counter::get(Counter::COUNTER_TYPE_LIVE_PRAISES, $liveid);
        $profit_total = Counter::get(Counter::COUNTER_TYPE_LIVE_TICKET, $liveid);
        // 加上本地cache
        $L1_cache_key = "L1_cache_live_$liveid";
        $live_info = LocalCache::get($L1_cache_key);

        if ($live_info) {
            $live_info = json_decode($live_info, true);
            $live_info['watches'] = $watchs_total;
            $live_info['praises'] = (int)$praises_total;
            $live_info['profit'] = $profit_total ? (int)$profit_total : 0;
            if(!empty($live_info['cover'])) {
                $live_info['cover'] = str_replace("dreamlive.tv", "dreamlive.com", $live_info['cover']);
            }
            if (strpos($live_info['cover'], 'http://') === false) {
                $live_info['cover'] = STATIC_DOMAIN_NAME. $live_info['cover'];
            }
            $live_info["L1_cached"] = true;

            return $live_info;
        }

        $L2_cache_key = "L2_cache_live_$liveid";
        $cache = Cache::getInstance("REDIS_CONF_CACHE", $liveid);

        if (false !== ($live_info = $cache->get($L2_cache_key))) {
            $live_info = json_decode($live_info, true);

            if (empty($live_info)) {
                return array();
            }

            if (in_array($live_info["uid"], $super_popular_users)) {
                LocalCache::set($L1_cache_key, $live_info, 60);
            }

            $live_info['watches'] = $watchs_total;
            $live_info['praises'] = (int)$praises_total;
            $live_info['profit'] = $profit_total ? (int)$profit_total : 0;
            $live_info["L2_cached"] = true;
            $live_info['province']     = str_replace('市', '', $live_info['province']);
            $live_info['city']         = str_replace('市', '', $live_info['city']);
            $live_info['city']         = str_replace('土家族', '', $live_info['city']);
            $live_info['city']         = str_replace('苗族', '', $live_info['city']);
            $live_info['city']         = str_replace('自治州', '', $live_info['city']);
            $live_info['city']         = str_replace('自治区', '', $live_info['city']);
            $live_info['city']         = str_replace('维吾尔', '', $live_info['city']);
            $live_info['city']         = str_replace('省', '', $live_info['city']);
            $live_info['city']        = empty($live_info['city']) ? '火星' : $live_info['city'];
            $live_info['province']     = str_replace('省', '', $live_info['province']);
            $live_info['province']         = str_replace('自治区', '', $live_info['province']);
            $live_info['province']         = str_replace('维吾尔', '', $live_info['province']);
            $live_info['district']     = str_replace('市', '', $live_info['district']);

            if(!empty($live_info['cover'])) {
                $live_info['cover'] = str_replace("dreamlive.tv", "dreamlive.com", $live_info['cover']);
            }
            if (strpos($live_info['cover'], 'http://') === false) {
                $live_info['cover'] = STATIC_DOMAIN_NAME. $live_info['cover'];
            }
            return $live_info;
        }

        $dao_live = new DAOLive();
        $live_info = $dao_live->getLiveInfo($liveid);

        if (!empty($live_info)) {
            $user = new User();
            $user_info = $user->getUserInfo($live_info["uid"]);
            if (!empty($user_info)) {
                $live_info["author"] = $user_info;
            }
            
            $live_info['privacy']    = json_decode($live_info["privacy"], true);
            $live_info['extends']    = json_decode($live_info['extends'], true);
            $live_info['watches'] = $watchs_total;
            $live_info['praises'] = (int)$praises_total;
            $live_info['province']     = str_replace('市', '', $live_info['province']);
            $live_info['city']         = str_replace('市', '', $live_info['city']);
            $live_info['city']         = str_replace('土家族', '', $live_info['city']);
            $live_info['city']         = str_replace('苗族', '', $live_info['city']);
            $live_info['city']         = str_replace('自治州', '', $live_info['city']);
            $live_info['city']         = str_replace('自治区', '', $live_info['city']);
            $live_info['city']         = str_replace('维吾尔', '', $live_info['city']);
            $live_info['city']         = str_replace('省', '', $live_info['city']);
            $live_info['province']     = str_replace('省', '', $live_info['province']);
            $live_info['province']         = str_replace('自治区', '', $live_info['province']);
            $live_info['province']         = str_replace('维吾尔', '', $live_info['province']);
            $live_info['district']     = str_replace('市', '', $live_info['district']);
            if(!empty($live_info['cover'])) {
                $live_info['cover'] = str_replace("dreamlive.tv", "dreamlive.com", $live_info['cover']);
            }
            if (strpos($live_info['cover'], 'http://') === false) {
                $live_info['cover'] = STATIC_DOMAIN_NAME. $live_info['cover'];
            }
            $cache->add($L2_cache_key, json_encode($live_info), 86400);
        }

        if (isset($live_info["uid"]) && in_array($live_info["uid"], $super_popular_users)) {
            LocalCache::set($L1_cache_key, json_encode($live_info), 60);
        }

        return $live_info;
    }

    public function add($liveid, $uid, $sn, $partner, $title, $width, $height, $point, $province, $city, $district, $location, $virtual, $request_extends,$replay,$cover, $pullurl)
    {
        // clean residual live
        $dao_live = new DAOLive();
        $user_live_list = $dao_live->getUserActiveLives($uid);
        
        
        
        foreach ($user_live_list as $user_live_info) {
            $extends = json_decode($user_live_info["extends"], true);
            
            //同一个账户不允许多个设备开播
            if (!empty($user_live_info)) {
                //Interceptor::ensureNotFalse(false, ERROR_BIZ_CHATROOM_ONLY_OPEN_ONE);
                $this->repeat($user_live_info["liveid"]);
            }
            
            if (isset($extends["deviceid"]) && $extends["deviceid"] == Context::get("deviceid")) {
                $this->repeat($user_live_info["liveid"]);
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
        //$record = $this->isPeplayPermissions($uid) ? 'Y' : 'N';
        $record  = 'N';
        if($this->isPeplayPermissions(Context::get("userid")) || Privacy::getPrivacyReplay(Context::get("userid"))) {
            $record = 'Y';
        }

        $dao_live = new DAOLive();
        try {
            $dao_live->startTrans();

            $dao_live->addLive($liveid, $uid, $sn, $partner, $title, $width, $height, $point, $province, $city, $district, $location, $virtual, $extends, $replay, $region, $cover, $record, $pullurl);

            $dao_user_feeds = new DAOUserFeeds($uid);
            $dao_user_feeds->addUserFeeds($uid, Feeds::FEEDS_LIVE, $liveid, date("Y-m-d H:i:s"));

            $dao_feed = new DAOFeeds;
            $dao_feed->addFeeds(Context::get("userid"), $liveid, Feeds::FEEDS_LIVE);

            $dao_live->commit();
        } catch (MySQLException $e) {
            $dao_live->rollback();
            throw new BizException(ERROR_SYS_DB_SQL);
        }

        $total = $dao_user_feeds->getUserFeedsTotal($uid);
        Counter::set(Counter::COUNTER_TYPE_PASSPORT_FEEDS_NUM, $uid, $total);

        $result = array(
            "live" => array(
                "liveid" => $liveid
            )
        );

        // flush cache
        $live_info = $this->getLiveInfo($liveid);
        
        self::setRedisUserLive($uid, $liveid);
        // sync to admin

        return $result;
    }

    public function _reload($liveid)
    {
        $L2_cache_key = "L2_cache_live_$liveid";
        $cache = Cache::getInstance("REDIS_CONF_CACHE", $liveid);
        $cache->delete($L2_cache_key);

        $live_info = $this->getLiveInfo($liveid);

        // 信息改变发送同步到运营后台
        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("live_sync_control", $live_info);

        return true;
    }
    
    public function repeat($liveid, $reason = '上一次崩溃,重复开播')
    {
        /*{{{主播短时间重复开播，删除上一个，只保留一个正在直播的数据*/
        
        $dao_live = new DAOLive();
        $live_info = $dao_live->getLiveInfo($liveid);
        
        
        self::remRedisUserLive($live_info['uid']);
        
        if (empty($live_info) || $live_info['status'] == Live::FINISHED) {
            include_once 'process_client/ProcessClient.php';
            ProcessClient::getInstance("dream")->addTask("live_stop", $live_info);
            ProcessClient::getInstance("dream")->addTask("delete_feeds_worker", array('relateid' => $liveid, 'type' => Feeds::FEEDS_LIVE, 'uid' => Context::get("userid")));
            return true;
        }
         
        $result = $dao_live->setLiveStatus($liveid, Live::CRUMBLE);
         
        
        if ($result) {
            
            $dao_user_feeds = new DAOUserFeeds(Context::get("userid"));
            $dao_user_feeds->delUserFeeds($liveid);
            
            $dao_feed = new DAOFeeds;
            $dao_feed->delFeeds(Context::get("userid"), $liveid, Feeds::FEEDS_LIVE);
            
            $this->_reload($liveid);
            
            // 发送消息到客户端
            $userinfo = User::getUserInfo(Context::get("userid"));
            $watchs_total          = Counter::get(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid);
            $praise_total          = Counter::get(Counter::COUNTER_TYPE_LIVE_PRAISES, $liveid);
            $receive_gift        = Counter::get(Counter::COUNTER_TYPE_LIVE_TICKET, $liveid);
            if (Context::get("userid")) {
                Messenger::sendLiveStop($liveid, "直播结束", Context::get("userid"), $userinfo['nickname'], $userinfo['avatar'], 0, $praise_total, 0, '直播结束', $userinfo['exp'], $userinfo['level'], $watchs_total, $receive_gift);
                Messenger::sendUserLiveInterrupt(Context::get("userid"), $liveid, '重复直播被强制下播', $reason);
                
            } else {
                Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $userinfo["uid"], '很遗憾，您的回放视频'.$reason.'，已经被删除。', '很遗憾，您的回放视频'.$reason.'，已经被删除。', $liveid);
            }
            
            $live = new Live();
            include_once 'process_client/ProcessClient.php';
            $live_info = $live->getLiveInfo($liveid);
            $live_info['status'] = Live::FINISHED;
            $live_info['endtime'] = date("Y-m-d H:i:s");
            ProcessClient::getInstance("dream")->addTask("live_stop", $live_info);
            ProcessClient::getInstance("dream")->addTask("delete_feeds_worker", array('relateid' => $liveid, 'type' => Feeds::FEEDS_LIVE, 'uid' => Context::get("userid")));
            
            //发送处理回放字幕work消息
            if (!empty($live_info) && $live_info['replay'] == 'Y') {
                
                $wrapper = array();
                $wrapper['content']['type'] = Messenger::MESSAGE_TYPE_LIVE_STOP;
                $wrapper['addtime'] = date("Y-m-d H:i:s");
                include_once 'process_client/ProcessClient.php';
                ProcessClient::getInstance("dream")->addTask("push_replay_title", array('liveid' => $liveid, 'sender' => Context::get("userid") ,'content' => json_encode($wrapper)));
            }
        }
        
        return true;
    }
    
    public function clean($liveid, $reason = '后台删除')
    {
        /*{{{主播短时间重复开播，删除上一个，只保留一个正在直播的数据*/
        
        $dao_live = new DAOLive();
        $live_info = $dao_live->getLiveInfo($liveid);
        //         if (empty($live_info) || $live_info['status'] == Live::FINISHED) {
        //             require_once ('process_client/ProcessClient.php');
        //             ProcessClient::getInstance("dream")->addTask("live_stop", $live_info);
        //             ProcessClient::getInstance("dream")->addTask("delete_feeds_worker", array('relateid' => $liveid, 'type' => Feeds::FEEDS_LIVE, 'uid' => Context::get("userid")));
        //             return true;
        //         }

        $result = $dao_live->setLiveDeleteStatus($liveid, Live::CLEANED);
        
        //连麦结束
        Link::finishLinkMember($liveid, Link::LINK_MEMBER_FORBIDED);
        
        self::remRedisUserLive($live_info['uid']);
        
        if ($result) {

            $dao_user_feeds = new DAOUserFeeds(Context::get("userid"));
            $dao_user_feeds->delUserFeeds($liveid);

            $dao_feed = new DAOFeeds;
            $dao_feed->delFeeds(Context::get("userid"), $liveid, Feeds::FEEDS_LIVE);

            $this->_reload($liveid);

            // 发送消息到客户端
            $userinfo = User::getUserInfo(Context::get("userid"));
            $watchs_total          = Counter::get(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid);
            $praise_total          = Counter::get(Counter::COUNTER_TYPE_LIVE_PRAISES, $liveid);
            $receive_gift        = Counter::get(Counter::COUNTER_TYPE_LIVE_TICKET, $liveid);
            if (Context::get("userid")) {
                Messenger::sendLiveStop($liveid, "直播结束", Context::get("userid"), $userinfo['nickname'], $userinfo['avatar'], 0, $praise_total, 0, '直播结束', $userinfo['exp'], $userinfo['level'], $watchs_total, $receive_gift);
                Messenger::sendUserLiveInterrupt(Context::get("userid"), $liveid, '重复直播被强制下播', $reason);

            } else {
                Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $userinfo["uid"], '很遗憾，您的回放视频'.$reason.'，已经被删除。', '很遗憾，您的回放视频'.$reason.'，已经被删除。', $liveid);
            }
            
            //Messenger::sendSquareChatroomStop($liveid);//通知广场大厅直播结束
            
            
            $live = new Live();
            include_once 'process_client/ProcessClient.php';
            $live_info = $live->getLiveInfo($liveid);
            $live_info['endtime'] = date("Y-m-d H:i:s");
            ProcessClient::getInstance("dream")->addTask("live_stop", $live_info);
            ProcessClient::getInstance("dream")->addTask("delete_feeds_worker", array('relateid' => $liveid, 'type' => Feeds::FEEDS_LIVE, 'uid' => Context::get("userid")));
            
            //发送处理回放字幕work消息
            if (!empty($live_info) && $live_info['replay'] == 'Y') {
                
                $wrapper = array();
                $wrapper['content']['type'] = Messenger::MESSAGE_TYPE_LIVE_STOP;
                $wrapper['addtime'] = date("Y-m-d H:i:s");
                include_once 'process_client/ProcessClient.php';
                ProcessClient::getInstance("dream")->addTask("push_replay_title", array('liveid' => $liveid, 'sender' => Context::get("userid") ,'content' => json_encode($wrapper)));
            }
        }

        return true;
    }

    public function interrupt($liveid, $clean, $reason)
    {
        /*{{{ 后台强制下播*/
        $dao_live = new DAOLive();
        $live_info = $dao_live->getLiveInfo($liveid);

        if ($clean == "Y") {
            $dao_live->setLiveStatus($liveid, Live::FORBIDED);
            //$dao_live->delLive($liveid);

            //连麦结束
            Link::finishLinkMember($liveid, Link::LINK_MEMBER_FORBIDED);
            
            $dao_user_feeds = new DAOUserFeeds($live_info["uid"]);
            $total = $dao_user_feeds->getUserFeedsTotal($live_info["uid"]);
            Counter::set(Counter::COUNTER_TYPE_PASSPORT_FEEDS_NUM, $live_info["uid"], $total);
        } else {
            $dao_live->setLiveStatus($liveid, Live::FORBIDED);
        }
        
        self::remRedisUserLive($live_info['uid']);
        
        $dao_user_feeds = new DAOUserFeeds($live_info["uid"]);
        $dao_user_feeds->delUserFeeds($liveid);

        $dao_feed = new DAOFeeds;
        $dao_feed->delFeeds($live_info['uid'], $liveid, Feeds::FEEDS_LIVE);

        $this->_reload($liveid);

        // 发送消息到客户端
        $userinfo = User::getUserInfo($live_info["uid"]);
        $watchs_total          = Counter::get(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid);
        $praise_total          = Counter::get(Counter::COUNTER_TYPE_LIVE_PRAISES, $liveid);
        $receive_gift        = Counter::get(Counter::COUNTER_TYPE_LIVE_TICKET, $liveid);
        $live_privacy_ticket     = Counter::get(Counter::COUNTER_TYPE_LIVE_PRIVACY_TICKET, $liveid);//付费收益
        $privacy_watches         = Counter::get(Counter::COUNTER_TYPE_LIVE_PRIVACY_WATCHES, $liveid);//付费人数
        Messenger::sendLiveInterrupt($liveid, "直播结束", $reason, Messenger::MESSAGE_SYSTERM_BRODCAST_USER, $userinfo['nickname'], $userinfo['avatar'], $userinfo['exp'], $userinfo['level'], $praise_total, $watchs_total, $receive_gift, $live_privacy_ticket, $privacy_watches);
        Messenger::sendUserLiveInterrupt($live_info["uid"], $liveid, '您被运营强制下播', $reason);
        //Messenger::sendSquareChatroomStop($liveid);//通知广场大厅直播结束
        
        $live_info = $dao_live->getLiveInfo($liveid);
        
        
        //发送处理回放字幕work消息
        if (!empty($live_info) && $live_info['replay'] == 'Y') {

            $wrapper = array();
            $wrapper['content']['type'] = Messenger::MESSAGE_TYPE_LIVE_STOP;
            $wrapper['addtime'] = date("Y-m-d H:i:s");
            include_once 'process_client/ProcessClient.php';
            ProcessClient::getInstance("dream")->addTask("push_replay_title", array('liveid' => $liveid, 'sender' => Context::get("userid") ,'content' => json_encode($wrapper)));

        }

        return true;
    }

    public function reclict($liveid)
    {
        /*{{{心跳清残留*/
        $dao_live = new DAOLive();
        $live_info = $dao_live->getLiveInfo($liveid);
        if (empty($live_info)) {
            return false;
        }
        
        self::remRedisUserLive($live_info['uid']);
        
        if ($live_info['status'] == Live::FINISHED) {
            return false;
        }
        
        $dao_live->setLiveStatus($liveid, Live::RECLICT);
        
        Link::finishLinkMember($liveid, Link::LINK_MEMBER_RECLICT);

        $dao_user_feeds = new DAOUserFeeds($live_info['uid']);
        $dao_user_feeds->delUserFeeds($liveid);

        $dao_feed = new DAOFeeds;
        $dao_feed->delFeeds($live_info['uid'], $liveid, Feeds::FEEDS_LIVE);

        $this->_reload($liveid);

        $userinfo = User::getUserInfo($live_info["uid"]);

        if (empty($userinfo)) {
            return false;
        }
        $watchs_total          = Counter::get(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid);
        $praise_total          = Counter::get(Counter::COUNTER_TYPE_LIVE_PRAISES, $liveid);
        $receive_gift        = Counter::get(Counter::COUNTER_TYPE_LIVE_TICKET, $liveid);
        //Messenger::sendSquareChatroomStop($liveid);//通知广场大厅直播结束
        //Messenger::sendLiveInterrupt($liveid, "直播结束", "残留直播", $live_info['uid'], $userinfo['nickname'], $userinfo['avatar'], $userinfo['exp'], $userinfo['level'], $praise_total, $watchs_total, $receive_gift);
        Messenger::sendUserLiveInterrupt($live_info["uid"], $liveid, '残留直播', '残留直播');
        //发送处理回放字幕work消息
        include_once 'process_client/ProcessClient.php';
        $live_info['status'] = Live::FINISHED;
        $live_info['endtime'] = date("Y-m-d H:i:s");
        ProcessClient::getInstance("dream")->addTask("live_stop", $live_info);
        ProcessClient::getInstance("dream")->addTask("delete_feeds_worker", array('relateid' => $liveid, 'type' => Feeds::FEEDS_LIVE, 'uid' => $live_info['uid']));
        
        
        
        if (!empty($live_info) && $live_info['replay'] == 'Y') {
            
            $wrapper = array();
            $wrapper['content']['type'] = Messenger::MESSAGE_TYPE_LIVE_STOP;
            $wrapper['addtime'] = date("Y-m-d H:i:s");
            include_once 'process_client/ProcessClient.php';
            ProcessClient::getInstance("dream")->addTask("push_replay_title", array('liveid' => $liveid, 'sender' => 1000 ,'content' => json_encode($wrapper)));
        
        }
        return true;
    }

    public function stop($liveid)
    {
        // 发送消息到客户端
        $userinfo = User::getUserInfo(Context::get("userid"));
        $watchs_total          = Counter::get(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid);
        $praise_total          = Counter::get(Counter::COUNTER_TYPE_LIVE_PRAISES, $liveid);
        $receive_gift        = Counter::get(Counter::COUNTER_TYPE_LIVE_TICKET, $liveid);
        
        $dao_live = new DAOLive();
        $dao_live->setLiveStatus($liveid, Live::FINISHED);
        
        //连麦结束
        Link::finishLinkMember($liveid, Link::LINK_MEMBER_STOP);
        
        $live_info = $dao_live->getLiveInfo($liveid);
        //$privacy   = Privacy::hasPrivacyLive($live_info["uid"], $live_info["addtime"], $live_info["endtime"], $liveid);
        $privacy   = Privacy::getPrivacyInfoByLiveInfo($live_info['privacy']);
        if (!empty($privacy) && isset($privacy['privacyid'])) {
            $watchs_total         = Counter::get(Counter::COUNTER_TYPE_LIVE_PRIVACY_WATCHES, $privacy['privacyid']);//付费人数
            $live_privacy_ticket     = Counter::get(Counter::COUNTER_TYPE_LIVE_PRIVACY_TICKET, $privacy['privacyid']);//付费收益
            $receive_gift = ($receive_gift + $live_privacy_ticket);
        }
         
        Messenger::sendLiveStop($liveid, "直播结束", Context::get("userid"), $userinfo['nickname'], $userinfo['avatar'],  0, $praise_total, 0, '直播结束', $userinfo['exp'], $userinfo['level'], $watchs_total, $receive_gift, $live_privacy_ticket);
        //Messenger::sendSquareChatroomStop($liveid);//通知广场大厅直播结束
        
        $dao_feed = new DAOFeeds;

        $dao_feed->delFeeds(Context::get("userid"), $liveid, Feeds::FEEDS_LIVE);
        //$dao_feed->addFeeds(Context::get("userid"), $liveid, Feeds::FEEDS_REPLAY);

        $dao_user_feeds = new DAOUserFeeds(Context::get("userid"));
        $dao_user_feeds->delUserFeeds($liveid);
        //$dao_user_feeds ->modLiveFeedType($liveid, Feeds::FEEDS_REPLAY, 0);

        $this->_reload($liveid);
        
        self::remRedisUserLive($live_info['uid']);
        
        //发送处理回放字幕work消息
        if (!empty($live_info) && $live_info['replay'] == 'Y') {
            //发送处理回放字幕work消息
            $wrapper = array();
            $wrapper['content']['type'] = Messenger::MESSAGE_TYPE_LIVE_STOP;
            $wrapper['addtime'] = date("Y-m-d H:i:s");
            include_once 'process_client/ProcessClient.php';
            ProcessClient::getInstance("dream")->addTask("push_replay_title", array('liveid' => $liveid, 'sender' => Context::get("userid") ,'content' => json_encode($wrapper)));
        }
        return true;
    }

    public function pause($liveid)
    {
        $dao_live = new DAOLive();
        $dao_live->setLiveStatus($liveid, Live::PAUSED);

        // 发送消息到客户端
        $userinfo = User::getUserInfo(Context::get("userid"));
        Messenger::sendLivePause($liveid, "直播暂停", Context::get("userid"), $userinfo['nickname'], $userinfo['avatar'], 0, 0, 0, '直播暂停', $userinfo['exp'], $userinfo['level']);

        $this->_reload($liveid);

        return true;
    }

    public function resume($liveid)
    {
        $dao_live = new DAOLive();
        $dao_live->setLiveStatus($liveid, Live::ACTIVING);

        // 发送消息到客户端
        $userinfo = User::getUserInfo(Context::get("userid"));
        Messenger::sendLiveResume($liveid, "直播恢复", Context::get("userid"), $userinfo['nickname'], $userinfo['avatar'], 0, 0, 0, '直播恢复', $userinfo['exp'], $userinfo['level']);

        $this->_reload($liveid);

        return true;
    }

    public function restart($liveid, $sn, $partner)
    {
        $dao_live = new DAOLive();
        $dao_live->setStream($liveid, $sn, $partner);

        $stream = new Stream();
        $flv = $stream->getFLVUrl($sn, $partner);

        Messenger::sendLiveRestart($liveid, "直播重启", Context::get("userid"), $sn, $partner, $flv);

        $this->_reload($liveid);

        return true;
    }

    public function delete($liveid)
    {
        /*{{{删除直播*/
        $dao_live = new DAOLive();
        $live_info = $dao_live->getLiveInfo($liveid);
        $dao_live->delLive($liveid);

        self::remRedisUserLive($live_info['uid']);
        
        $dao_feed = new DAOFeeds;
        $dao_feed->delFeeds(Context::get("userid"), $liveid, Feeds::FEEDS_LIVE);

        $this->_reload($liveid);

        $dao_user_feeds = new DAOUserFeeds(Context::get("userid"));
        $dao_user_feeds->delUserFeeds($liveid);

        $dao_user_feeds = new DAOUserFeeds(Context::get("userid"));
        $total = $dao_user_feeds->getUserFeedsTotal(Context::get("userid"));
        Counter::set(Counter::COUNTER_TYPE_PASSPORT_FEEDS_NUM, Context::get("userid"), $total);

        // 发送消息到客户端
        $userinfo = User::getUserInfo(Context::get("userid"));
        //Messenger::sendSquareChatroomStop($liveid);//通知广场大厅直播结束
        
        Messenger::sendLiveStop($liveid, "直播结束", Context::get("userid"), $userinfo['nickname'], $userinfo['avatar'], 0, 0, 0, '直播结束', $userinfo['exp'], $userinfo['level']);
        
        //发送处理回放字幕work消息
        if (!empty($live_info) && $live_info['replay'] == 'Y') {
            //发送处理回放字幕work消息
            $wrapper = array();
            $wrapper['content']['type'] = Messenger::MESSAGE_TYPE_LIVE_STOP;
            $wrapper['addtime'] = date("Y-m-d H:i:s");
            include_once 'process_client/ProcessClient.php';
            ProcessClient::getInstance("dream")->addTask("push_replay_title", array('liveid' => $liveid, 'sender' => Context::get("userid") ,'content' => json_encode($wrapper)));
        }
        
        return true;
    }

    /**
     * 删除回放
     *
     * @param  int $liveid
     * @return boolean
     */
    public function delRepaly($liveid)
    {
        $live = new Live();
        $live->deleteRepaly($liveid);

        $this->_reload($liveid);

        $dao_user_feeds = new DAOUserFeeds(Context::get("userid"));
        $dao_user_feeds->delUserFeeds($liveid);

        return true;
    }

    public function heartbeat($liveid)
    {
        /*{{{添加心跳*/
        $key   = "heartbeat_live_cache";
        $value = time();

        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $cache->zAdd($key, $value, $liveid);

        return true;
    }/*}}}*/
    

    public function addReplay($sn, $replayurl, $partner,$stime,$etime)
    {
        /*{{{添加回放*/

        $dao_live = new DAOLive();
        try {
            $DAOLive = new DAOLive();
            $liveInfo = $DAOLive -> getLiveInfoBySn($sn, $partner);
            Interceptor::ensureNotEmpty($liveInfo, ERROR_BIZ_VIDEO_NOT_EXIST, "sn");

            
            $dao_live->setReplay($liveInfo['liveid'], $replayurl, $partner, $stime, $etime);

            $this->_reload($liveInfo['liveid']);
            
            if($liveInfo['status']==Feeds::FEEDS_REPLAY) {
                $dao_user_feeds = new DAOUserFeeds($liveInfo['uid']);
                $dao_user_feeds->addUserFeeds($liveInfo['uid'], Feeds::FEEDS_REPLAY, $liveInfo['liveid'], $liveInfo['addtime']);
            }
            

            //$dao_feeds = new DAOFeeds();
            //$dao_feeds->addFeeds($liveInfo['uid'], $liveInfo['liveid'], Feeds::FEEDS_REPLAY);
        } catch (MySQLException $e) {
            $dao_live->rollback();
            return $e;
        }

        return true;
    }
    /* }}} */

    public function setCover($liveid, $cover)
    {
        $dao_live = new DAOLive();
        try {
            $dao_live->setCover($liveid, $cover);
            $this->_reload($liveid);
        } catch (MySQLException $e) {
            return false;
        }

        return true;
    }

    public function setPartnerSn($liveid, $sn, $partner)
    {
        $dao_live = new DAOLive();
        try {
            $dao_live->setPartnerSn($liveid, $sn, $partner);
            $this->_reload($liveid);
        } catch (MySQLException $e) {
            return false;
        }
        
        return true;
    }
    
    public function setLiveinfo($liveid, $cover, $title)
    {
        $dao_live = new DAOLive();
        try {
            $dao_live->setLiveInfo($liveid, $cover, $title);
            $this->_reload($liveid);
        } catch (MySQLException $e) {
            return false;
        }
        
        return true;
    }

    /**
     * 删除回放视频
     *
     * @param int $liveid
     */
    public function deleteRepaly($liveid)
    {
        $dao_live = new DAOLive();
        $dao_live->delLive($liveid);

        $L2_cache_key = "L2_cache_live_$liveid";
        $cache = Cache::getInstance("REDIS_CONF_CACHE", $liveid);
        $cache->delete($L2_cache_key);
    }

    /**
     * 判断是否具有录制的权限
     */
    public function isPeplayPermissions($userid)
    {
        //黄v判断
        $replay    = false;
        $user      = new User();
        $userInfo = $user->getUserInfo($userid);
        if (! empty($userInfo['medal'])) {
            foreach ($userInfo['medal'] as $item) {
                if($item['kind']=='v' && $item['medal']=='yellow') {
                    $replay = true;
                }
            }
        }
        return $replay;
    }
    
    /**
     * 获取回放url
     *
     * @param  array $liveInfo
     * @return multitype:string
     */
    public function getReplayurlList($liveInfo)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $rediskey = "replayurl_details_string_" . $liveInfo['liveid'];
        $result = $cache->get($rediskey);
        if(!empty($result)) {
            return json_decode($result, true);
        }
        
        $arrTemp = array();
        $stream = new Stream();
        $DAOReplayurlDetails = new DAOReplayurlDetails();
        $list = $DAOReplayurlDetails->getReplayurlListByLiveid($liveInfo['liveid']);
        if (!empty($list)) {
            foreach ($list as $key=>$val) {
                $arrTemp[] = $stream->getRepalyUrl($liveInfo['partner'], $liveInfo['region'], trim($val['replayurl']));
            }
        }
        $cache->SET($rediskey, json_encode($arrTemp));
        return $arrTemp;
    }

    /**
     * 直播列表
     *
     * @param int  $uid
     * @param date $startime
     * @param date $endtime
     * @param int  $num
     * @param int  $offset
     */
    public function getUserLiveList($uid, $startime, $endtime, $num, $offset)
    {
        $rediskey = 'user_list_'.$uid.'_string_'.md5($uid.$startime.$endtime.$num.$offset);
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        if (false !== ($result = $cache->get($rediskey))) {
            return json_decode($result, true);
        }
        
        $DAOLive = new DAOLive();
        $DAOJournal = new DAOJournal($uid);
        $arrTemp = array();
        $arrTemp['total']     = intval($DAOLive->getUserLiveTotal($uid, $startime, $endtime));
        $arrTemp['longtime']  = intval($DAOLive->getUserLiveLong($uid, $startime, $endtime));
        
        $stime = ($startime != '') ? $startime : '2016-01-01 00:00:00';
        $etime = ($endtime  != '') ? $endtime  : date('Y-m-d H:i:s');
        $anchorIncome       = AccountInterface::getAnchorIncomeByDate($uid, $stime, $etime);
        $arrTemp['amount']  = $anchorIncome['all'];
        
        $list = $DAOLive->getUserLiveList($uid, $startime, $endtime, $num, $offset);
        foreach($list as $key=>$val){
            $endtime = ($val['endtime'] != '0000-00-00 00:00:00') ? $val['endtime'] : date('Y-m-d H:i:s');
            $anchorIncome = AccountInterface::getAnchorIncomeByDate($uid, $val['addtime'], $endtime); 
            $item = array(
                'liveid'  => $val['liveid'],
                'uid'     => $val['uid'],
                'addtime' => $val['addtime'],
                'endtime' => $endtime,
                'longtime'    => strtotime($endtime) - strtotime($val['addtime']),
                'amount'  => $anchorIncome['all']
            );

            $offset = $val['liveid'];
            $arrTemp['list'][$key] = $item;
        }
        $arrTemp['offset'] = $offset;
        $cache->SET($rediskey, json_encode($arrTemp));
        $cache->expire($rediskey, 60);
        return $arrTemp;
    }
    
    /**
     * 修改连麦类型
     *
     * @param int $liveid
     * @param int $linktype
     */
    public function setUserLiveLink($liveid,$linktype,$streamtype)
    {
        $DAOLive = new DAOLive();
        $DAOLive->setUserLiveLink($liveid, $linktype, $streamtype);
        return $this->_reload($liveid);
    }

    /**
     * 
     * 获取在线主播列表
     */
    public static function getUserLiveAnchorList()
    {
        $key = "User_living_cache";
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        return $cache->ZREVRANGE($key, 0, - 1);
    }
    
    
    /**
     * 是否正在直播
     *
     * @param int $uid
     */
    public static function isUserLive($uid)
    {
        $key = "User_living_cache";
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        if (($liveid = $cache->ZSCORE($key, $uid)) > 0) {
            return $liveid;
        }
        return false;
    }
    
    /**
     * 获取用户在谁的直播间
     *
     * @param int $uid
     */
    public static function getLiveRoomByUser($uid)
    {
        $key = "dreamlive_online_users_redis_key";
        $cache = Cache::getInstance("REDIS_CONF_COUNTER");
        if (($liveid = $cache->ZSCORE($key, $uid)) > 0) {
            return $liveid;
        }
        return $cache->get($key);
    }
    
    static public function setRedisUserLive($userid, $liveid)
    {
        $key   = "User_living_cache";
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $cache->zAdd($key, $liveid, $userid);
    }
    
    static public function remRedisUserLive($userid)
    {
        $key   = "User_living_cache";
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $cache->zRem($key, $userid);
    }
}
?>
