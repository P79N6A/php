<?php
class Feeds
{
    const FEEDS_LIVE         = 1; // 直播
    const FEEDS_REPLAY         = 2; // 回放
    const FEEDS_IMAGE        = 3; // 图片
    const FEEDS_VIDEO        = 4; // 小视频
    const FEEDS_USER         = 5; // 用户
    const FEEDS_VIDEO_SCREEN = 6; // 录屏
    const FEEDS_IMAGE_SCREEN = 7; // 截屏
    const FEEDS_TOPIC         = 8; // 话题



    /**
     * 批量获取feed数据
     *
     * @param array $relateids
     */
    public function getBatchFeedList($relateids)
    {
        //获取分组
        $group = self::getGroupRelateids($relateids);
        //根据分组获取数据
        $groupFeedList = self::getGroupFeedList($group);
        //排序
        return self::getSortFeedList($relateids, $groupFeedList);

    }

    /**
     * 排序
     *
     * @param  array $relateids
     * @param  array $groupFeedList
     * @return boolean|multitype:NULL
     */
    public static  function getSortFeedList($relateids,$groupFeedList)
    {
        if(empty($relateids) || empty($groupFeedList)) {
            return false;
        }

        $arrTemp = array();
        foreach($relateids as $item){
            $arrTemp[$item['relateid']] = $groupFeedList[$item['type']][$item['relateid']];
        }
        return $arrTemp;
    }

    /**
     * 获取分组relateids
     *
     * @param  array $arrTemp
     * @return multitype:
     */
    public static  function getGroupRelateids($relateids)
    {
        $arrTemp = array();
        foreach ($relateids as $item) {
            switch ($item['type']) {
            case self::FEEDS_LIVE:
                $arrTemp[self::FEEDS_LIVE][] = $item;
                break;
            case self::FEEDS_REPLAY:
                $arrTemp[self::FEEDS_REPLAY][] = $item;
                break;
            case self::FEEDS_IMAGE:
                $arrTemp[self::FEEDS_IMAGE][] = $item;
                break;
            case self::FEEDS_VIDEO:
                $arrTemp[self::FEEDS_VIDEO][] = $item;
                break;
            case self::FEEDS_USER:
                $arrTemp[self::FEEDS_USER][] = $item;
                break;
            case self::FEEDS_VIDEO_SCREEN:
                $arrTemp[self::FEEDS_VIDEO_SCREEN][] = $item;
                break;
            case self::FEEDS_IMAGE_SCREEN:
                $arrTemp[self::FEEDS_IMAGE_SCREEN][] = $item;
                break;
            case self::FEEDS_TOPIC:
                $arrTemp[self::FEEDS_TOPIC][] = $item;
                break;
            }
        }
        return $arrTemp;
    }

    public static function getGroupFeedList($group)
    {
        $arrTemp = array();
        foreach($group as $key=>$item){
            switch ($key) {
            case self::FEEDS_LIVE:
                $arrTemp[self::FEEDS_LIVE] = self::getBatchLives($item);
                break;
            case self::FEEDS_REPLAY:
                $arrTemp[self::FEEDS_REPLAY] = self::getBatchReplay($item);
                break;
            case self::FEEDS_IMAGE:
                $arrTemp[self::FEEDS_IMAGE] = self::getBatchImages($item);
                break;
            case self::FEEDS_VIDEO:
                $arrTemp[self::FEEDS_VIDEO] = self::getBatchVideo($item);
                break;
            case self::FEEDS_USER:
                $arrTemp[self::FEEDS_USER] = self::getBatchUsersList($item);
                break;
            case self::FEEDS_VIDEO_SCREEN:
                $arrTemp[self::FEEDS_VIDEO_SCREEN] =  self::getBatchVideoScreen($item);
                break;
            case self::FEEDS_IMAGE_SCREEN:
                $arrTemp[self::FEEDS_IMAGE_SCREEN] =  self::getBatchImageScreen($item);
                break;
            case self::FEEDS_TOPIC:
                $arrTemp[self::FEEDS_TOPIC] =  self::getBatchTopic($item);
                break;
            }
        }
        return $arrTemp;
    }

    public static function getBatchLives($list)
    {
        $arrTemp = $arrLive = $arrKey = $uids = $liveIds = array();
        //拼接redis的key
        foreach ($list as $item) {
            if(!empty($item)) {
                $key = "L2_cache_live_" . $item['relateid'];
                array_push($arrKey, $key);
            }
        }
        //批量读取redis数据
        $cache = Cache::getInstance("REDIS_CONF_CACHE", 0);
        $list  = $cache->mget($arrKey);
        foreach ($list as $item) {
            if(!empty($item)) {
                $liveInfo = json_decode($item, true);
                array_push($uids, $liveInfo['uid']);
                array_push($liveIds, $liveInfo['liveid']);
                $arrLive[$liveInfo['liveid']] = $liveInfo;
            }
        }

        $watchsTotals   = Counter::getBatchCount(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveIds);
        //$praisesTotals  = Counter::getBatchCount(Counter::COUNTER_TYPE_LIVE_PRAISES, $liveIds);
        //$profitTotals   = Counter::getBatchCount(Counter::COUNTER_TYPE_LIVE_TICKET, $liveIds);

        //批量获取用户
        $userList = self::getBatchUsers($uids);
        if(!empty($arrLive)) {
            foreach($arrLive as $key=>$val){
                $feeds = self::getLiveInfoFormat($val, $userList[$val['uid']]);
                if($feeds===false) {
                    continue;
                }
                if (! $feeds['feed']['privacy']) {
                    $feeds['feed']['watches'] = intval($watchsTotals[$val['liveid']]);
                }
                $feeds['feed']['praises'] = 0;
                $feeds['feed']['profit']  = 0;
                $arrTemp[$key] = $feeds;
            }
        }
        return $arrTemp;
    }

    /**
     * 批量获取回放
     *
     * @param array $relateids
     */
    public static function getBatchReplay($list)
    {
        $arrTemp = $arrLive = $arrKey = $uids = $liveIds = array();
        //拼接redis的key
        foreach ($list as $item) {
            if(!empty($item)) {
                $key = "L2_cache_live_" . $item['relateid'];
                array_push($arrKey, $key);
            }
        }
        //批量读取redis数据
        $cache = Cache::getInstance("REDIS_CONF_CACHE", $item['relateid']);
        $list  = $cache->mget($arrKey);
        foreach ($list as $item) {
            if(!empty($item)) {
                $liveInfo = json_decode($item, true);
                array_push($uids, $liveInfo['uid']);
                array_push($liveIds, $liveInfo['liveid']);
                $arrLive[$liveInfo['liveid']] = $liveInfo;
            }
        }

        $watchsTotals   = Counter::getBatchCount(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveIds);
        //$praisesTotals  = Counter::getBatchCount(Counter::COUNTER_TYPE_LIVE_PRAISES, $liveIds);
        //$profitTotals   = Counter::getBatchCount(Counter::COUNTER_TYPE_LIVE_TICKET, $liveIds);

        //批量获取用户
        $userList = self::getBatchUsers($uids);
        if(!empty($arrLive)) {
            foreach($arrLive as $key=>$val){
                $feeds = self::getReplayInfoFormat($val, $userList[$val['uid']]);
                if($feeds===false) {
                    continue;
                }
                if (! ($feeds['feed']['privacy'] && $feeds['feed']['privacyid'] > 0)) {
                    $feeds['feed']['watches'] = intval($watchsTotals[$val['liveid']]);
                }
                $feeds['feed']['praises'] = 0;
                $feeds['feed']['profit']  = 0;
                $arrTemp[$key] = $feeds;
            }
        }
        return $arrTemp;
    }

    /**
     * 批量获取图片
     *
     * @param array $relateids
     */
    public static function getBatchImages($list)
    {
        $arrTemp = $arrImage = $arrKey = $uids = $imageIds = array();
        //拼接redis的key
        foreach ($list as $item) {
            if(!empty($item)) {
                $key = "image_cache_" . $item['relateid'];
                array_push($arrKey, $key);
            }
        }
        //批量读取redis数据
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $list  = $cache->mget($arrKey);
        foreach ($list as $item) {
            if(!empty($item)) {
                $imageInfo = json_decode($item, true);
                array_push($uids, $imageInfo['uid']);
                array_push($imageIds, $imageInfo['imageid']);
                $arrImage[$imageInfo['imageid']] = $imageInfo;
            }
        }

        $watchsTotals   = Counter::getBatchCount(Counter::COUNTER_TYPE_IMAGE_WATCHES, $imageIds);
        //$praisesTotals  = Counter::getBatchCount(Counter::COUNTER_TYPE_IMAGE_PRAISES, $imageIds);

        //批量获取用户
        $userList = self::getBatchUsers($uids);
        if(!empty($arrImage)) {
            foreach($arrImage as $key=>$val){
                $feeds = self::getImageInfoFormat($val, $userList[$val['uid']]);
                if($feeds===false) {
                    continue;
                }
                $feeds['feed']['watches'] = intval($watchsTotals[$val['imageid']]);
                $feeds['feed']['praises'] = 0;
                $arrTemp[$key] = $feeds;
            }
        }
        return $arrTemp;
    }

    /**
     * 批量获取短视频
     *
     * @param array $list
     */
    public static function getBatchVideo($list)
    {
        $arrTemp = $arrVideo = $arrKey = $uids = $videoIds = $arrTop = array();
        //拼接redis的key
        foreach ($list as $item) {
            if(!empty($item)) {
                $key = "video_cache_" . $item['relateid'];
                array_push($arrKey, $key);
                if(!empty($item['extends']['top'])) {
                    $arrTop[$item['relateid']] = $item['extends']['top'];
                }
            }
        }
        //批量读取redis数据
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $list  = $cache->mget($arrKey);
        foreach ($list as $item) {
            if(!empty($item)) {
                $videoInfo = json_decode($item, true);
                array_push($uids, $videoInfo['uid']);
                array_push($videoIds, $videoInfo['videoid']);
                $arrVideo[$videoInfo['videoid']] = $videoInfo;
            }
        }

        $watchsTotals   = Counter::getBatchCount(Counter::COUNTER_TYPE_VIDEO_WATCHES, $videoIds);
        $praisesTotals  = Counter::getBatchCount(Counter::COUNTER_TYPE_VIDEO_PRAISES, $videoIds);

        //批量获取用户
        $userList = self::getBatchUsers($uids);
        if(!empty($arrVideo)) {
            foreach($arrVideo as $key=>$val){
                $feeds = self::getVideoInfoFormat($val, $userList[$val['uid']]);
                if($feeds===false) {
                    continue;
                }
                $feeds['feed']['watches'] = intval($watchsTotals[$val['videoid']])*11;//20180119 观看 x11
                $feeds['feed']['praises'] = intval($praisesTotals[$val['videoid']]);
                $feeds['feed']['istop']   = ($arrTop[$val['videoid']]=='Y') ? true : false;
                $arrTemp[$key] = $feeds;
            }
        }
        return $arrTemp;
    }

    /**
     * 批量获取话题
     *
     * @param array $list
     */
    public static function getBatchTopic($list)
    {
        $arrTemp =  array();
        foreach ($list as $item) {
            if(!empty($item) && $item['extends']['online'] == 1 && $item['extends']['position'] > 0) {
                $feedInfo = $item['extends'];
                $feedInfo['relateid'] = $item['extends']['topicid'];
                $feedInfo['online']   = $item['extends']['online'];
                $feedInfo['position'] = $item['extends']['position'];
                $feedInfo['istop']    = false;

                $userinfo = array('type'=>self::FEEDS_TOPIC);
                $arrTemp[$item['relateid']] = array("feed"=>$feedInfo, "author"=>$userinfo);
            }
        }
        return $arrTemp;
    }


    /**
     * 直播数据format
     *
     * @param array $liveInfo
     * @param array $userInfo
     */
    public static function getLiveInfoFormat($liveInfo,$userInfo)
    {
        if (empty($liveInfo) || empty($userInfo)) {
            return false;
        }
        if (! (in_array($liveInfo['status'], array(Live::ACTIVING,Live::PAUSED)))) {
            return false;
        }

        $userid   = intval(Context::get("userid"));
        $replay   = ($liveInfo['record'] == 'Y') ? true : false;
        //$privacy  = Privacy::hasPrivacyLive($liveInfo["uid"], $liveInfo["addtime"], $liveInfo["endtime"], $liveInfo['liveid']);
        $privacy  = Privacy::getPrivacyInfoByLiveInfo($liveInfo["privacy"]);
        $stream   = new Stream();
        $flv      = $stream->getFLVUrl($liveInfo['sn'], ($liveInfo['partner']), $liveInfo['region'], $replay);
        //istudio合作
        if (!empty($liveInfo['pullurl'])) {
            $flv = $liveInfo['pullurl'];
        }

        if (strpos($liveInfo['cover'], 'http://') === false) {
            $liveInfo['cover'] = STATIC_DOMAIN_NAME. $liveInfo['cover'];
        }

        $feedInfo = array(
            'type'      => self::FEEDS_LIVE,
            'cover'        => ($liveInfo['cover'] != '') ? $liveInfo['cover'] : $userInfo['avatar'],
            'relateid'    => $liveInfo['liveid'],
            'addtime'    => $liveInfo['addtime'],
            'endtime'    => $liveInfo['endtime'],
            'title'        => $liveInfo['title'],
            'width'        => $liveInfo['width'],
            'height'    => $liveInfo['height'],
            'city'        => empty($liveInfo['city']) ? '火星' : $liveInfo['city'],
            'extends'    => $liveInfo['extends'],
            'virtual'    => $liveInfo['virtual'] == 'Y' ? true : false,
            'region'    => $liveInfo['region'],
            'flv'        => empty($flv) ? "" : $flv,
            'privacy'   => ! empty($privacy) ? true : false,
            'privacyid' => !empty($privacy['privacyid']) ? $privacy['privacyid'] : '0',
            'privacys'  => $privacy
        );

        if (!empty($privacy) && isset($privacy['privacyid'])) {
            $feedInfo['watches'] = Counter::get(Counter::COUNTER_TYPE_LIVE_PRIVACY_WATCHES, $privacy['privacyid']);
            if (empty($feedInfo['watches'])) {
                $feedInfo['watches'] = "0";
            }
        }

        if (!empty($privacy) && isset($privacy['privacyid']) && Context::get("version") >= '2.6.2') {
            $aes = new AES;
            $aes_iv = substr(md5($liveInfo['liveid'].$userid), 0, 16);
            $aes_key = substr(md5($userid), 0, 16);
            $flv = $aes->aes128cbcEncrypt($flv, $aes_iv, $aes_key);

            $feedInfo['iv']  = $aes_iv;
            $feedInfo['k']   = $aes_key;
            $feedInfo['flv'] = $flv;
        }
        return array("feed"=>$feedInfo, "author"=>$userInfo);
    }

    /**
     * 回放数据format
     *
     * @param array $liveInfo
     * @param array $userInfo
     */
    public static function getReplayInfoFormat($liveInfo,$userInfo)
    {
        if (empty($liveInfo) || empty($liveInfo)) {
            return false;
        }
        if (! ($liveInfo['replay'] == 'Y' && $liveInfo['replayurl'] != '')) {
            return false;
        }

        //私密直播
        //$privacy  = Privacy::hasPrivacyLive($liveInfo["uid"], $liveInfo["addtime"], $liveInfo["endtime"], $liveInfo['liveid']);
        //$privacy  = Privacy::getPrivacyInfoByLiveInfo($liveInfo["privacy"]);
        $privacy  = Privacy::getReplayPrivacyInfoByLiveInfo($liveInfo["privacy"]);
        $userid   = intval(Context::get("userid"));
        $cache    = Cache::getInstance("REDIS_CONF_CACHE");
        $praise_redis_key = Live::REPLAY_PRAISE_REDIS_KEY. $liveInfo['liveid'] . "_" . $userid;
        $praise_status    = $cache->get($praise_redis_key) > 0 ? 1 : 0;

        if (strpos($liveInfo['cover'], 'http://') === false) {
            $liveInfo['cover'] = STATIC_DOMAIN_NAME. $liveInfo['cover'];
        }

        $stream = new Stream();
        $subtitleUrl = Context::getConfig("STATIC_DOMAIN")."/" . $liveInfo['liveid']."/replay/index.txt";
        $feedInfo = array(
            'type'      => self::FEEDS_REPLAY,
            'cover'     => ($liveInfo['cover'] != '') ? $liveInfo['cover'] : $userInfo['avatar'],
            'relateid'  => $liveInfo['liveid'],
            'addtime'   => $liveInfo['addtime'],
            'endtime'   => $liveInfo['endtime'],
            'stime'     => $liveInfo['stime'],
            'etime'     => $liveInfo['etime'],
            'title'     => $liveInfo['title'],
            'width'     => $liveInfo['width'],
            'height'    => $liveInfo['height'],
            'praise_status' => $praise_status,
            'city'        => empty($liveInfo['city']) ? '火星' : $liveInfo['city'],
            'extends'   => $liveInfo['extends'],
            'replayurl' => ($liveInfo['replayurl'] != '') ? $stream->getRepalyUrl($liveInfo['partner'], $liveInfo['region'], $liveInfo['replayurl']) : '',
            'virtual'   => $liveInfo['virtual'] == 'Y' ? true : false,
            'region'    => $liveInfo['region'],
            'subtitleUrl' => ($liveInfo['replayurl'] != '') ? $subtitleUrl : '',
            'privacy'   => ! empty($privacy) ? true : false,
            'privacyid' => ! empty($privacy['privacyid']) ? $privacy['privacyid'] : '0',
            'privacys'  => $privacy,
            'has_custom_price' => Privacy::hasCustomPrice($userid),
            'max_replay_price' => isset($privacy['paylong']) ? Privacy::getReplayPrice($privacy['paylong']) : 1000,
            'price'     => ! empty($privacy['replay_price']) ? $privacy['replay_price'] : 0
        );

        if (!empty($privacy) && isset($privacy['privacyid'])) {
            $feedInfo['watches'] = Counter::get(Counter::COUNTER_TYPE_LIVE_PRIVACY_WATCHES, $privacy['privacyid']);
            if (empty($feedInfo['watches'])) {
                $feedInfo['watches'] = "0";
            }
        }

        if (!empty($privacy) && isset($privacy['privacyid']) && Context::get("version") >= '2.6.2') {
            $aes = new AES;
            $aes_iv = substr(md5($liveInfo['liveid'].$userid), 0, 16);
            $aes_key = substr(md5($userid), 0, 16);
            $flv = $aes->aes128cbcEncrypt($flv, $aes_iv, $aes_key);

            $feedInfo['iv'] = $aes_iv;
            $feedInfo['k']  = $aes_key;
            $feedInfo['flv'] = $flv;
        } else {
            $feedInfo['flv'] = "";
        }
        $live = new Live();
        $feedInfo['replayurls'] = $live->getReplayurlList($liveInfo);
        return array("feed"=>$feedInfo, "author"=>$userInfo);
    }

    /**
     * 图片数据format
     *
     * @param array $liveInfo
     * @param array $userInfo
     */
    public static function getImageInfoFormat($imageInfo,$userInfo)
    {
        if (empty($imageInfo) || empty($imageInfo['url']) || empty($userInfo)) {
            return false;
        }

        if (strpos($imageInfo['url'], 'http://') === false) {
            $imageInfo['url'] = STATIC_DOMAIN_NAME. $imageInfo['url'];
        }

        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $praises_status = $cache->get(Image::PRAISE_REDIS_KEY. $imageInfo['imageid'] . "_" . Context::get("userid"));
        $feedInfo = array(
            'type'        => self::FEEDS_IMAGE,
            'cover'        => $imageInfo['url'],
            'relateid'    => $imageInfo['imageid'],
            'addtime'    => $imageInfo['addtime'],
            'width'        => $imageInfo['width'],
            'height'    => $imageInfo['height'],
            'praise_status'=> (int) empty($praises_status) ? 0 : 1,
        );
        return array("feed"=>$feedInfo, "author"=>$userInfo);
    }

    /**
     * 短视频数据format
     *
     * @param array $liveInfo
     * @param array $userInfo
     */
    public static function getVideoInfoFormat($videoInfo,$userInfo)
    {
        if (empty($videoInfo) || empty($videoInfo['cover']) || empty($userInfo)) {
            return false;
        }

        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $praises_status = $cache->get(Video::PRAISE_REDIS_KEY. $videoInfo['videoid'] . "_" . Context::get("userid"));

        if (strpos($videoInfo['cover'], 'http://') === false) {
            $videoInfo['cover'] = STATIC_DOMAIN_NAME. $videoInfo['cover'];
        }

        if (strpos($videoInfo['mp4'], 'http://') === false) {
            $videoInfo['mp4'] = STATIC_DOMAIN_NAME. $videoInfo['mp4'];
        }

        $feedInfo = array(
            'type'        => self::FEEDS_VIDEO,
            'cover'        => $videoInfo['cover'],
            'relateid'    => $videoInfo['videoid'],
            'addtime'    => $videoInfo['addtime'],
            'mp4'        => $videoInfo['mp4'],
            'width'        => $videoInfo['width'],
            'height'    => $videoInfo['height'],
            'duration'    => $videoInfo['duration'],
            'praise_status'=> (int) empty($praises_status) ? 0 : 1,
            'topic'     => $videoInfo['topic'],
            'city'        => empty($videoInfo['city']) ? '火星' : $videoInfo['city'],
        );

        return array("feed"=>$feedInfo, "author"=>$userInfo);
    }

    public static function getUserInfoFormat($userInfo)
    {
        $feedInfo = array(
            'relateid'    => $userInfo['uid'],
            'followers'    => Follow::countFollowers($userInfo['uid']),
            'followings'=> Follow::countFollowings($userInfo['uid']),
            "signature"  => (string)$userInfo["signature"],
        );
        return array("feed"=>$feedInfo, "author"=>$userInfo);
    }

    public static function getBatchUsersList($relateids)
    {
        $ids = array();
        foreach ($relateids as $item) {
            if(!empty($item)) {
                $ids[] = $item['relateid'];
            }
        }

        $arrTemp = array();
        $list = self::getBatchUsers($ids);
        foreach($list as $key=>$val){
            $feeds = self::getUserInfoFormat($val);
            if($feeds===false) {
                continue;
            }
            $arrTemp[$key] = $feeds;
        }
        return $arrTemp;
    }

    /**
     * 批量回去用户
     *
     * @param array $relateids
     */
    public static function getBatchUsers($relateids)
    {
        $arrTemp = $arrKey = array();
        foreach ($relateids as $uid) {
            if(!empty($uid)) {
                $key = "USER_CACHE_" . $uid;
                array_push($arrKey, $key);
            }
        }
        $cache = Cache::getInstance("REDIS_CONF_USER", $uid);
        $list = $cache->mget($arrKey);
        if(!empty($list)) {
            foreach($list as $item){
                $userInfo = json_decode($item, true);
                if(!empty($userInfo['avatar'])) {
                    $userInfo['avatar'] = str_replace("dreamlive.tv", "dreamlive.com", $userInfo['avatar']);
                }

                if (strpos($userInfo['avatar'], 'http://') === false) {
                    $userInfo['avatar'] = STATIC_DOMAIN_NAME. $userInfo['avatar'];
                }
                $arrTemp[$userInfo['uid']] = $userInfo;
            }
        }
        return $arrTemp;
    }


    public function getFeedInfo($relateid, $type, $extends = [])
    {
        switch ($type) {
        case self::FEEDS_LIVE:
            $live = new Live();
            $live_info = $live->getLiveInfo($relateid);
            if (!(!empty($live_info) && in_array($live_info['status'], array(Live::ACTIVING, Live::PAUSED)) )) {
                 return false;
            }
                $userid   = intval(Context::get("userid"));
                /**
                $live = new Live();
                $replay = $live->isPeplayPermissions($live_info['uid']);
                */
                $replay = ($live_info['record'] == 'Y') ? true : false;
                //$privacy   = Privacy::hasPrivacyLive($live_info["uid"], $live_info["addtime"], $live_info["endtime"], $relateid);
                $privacy = Privacy::getPrivacyInfoByLiveInfo($live_info["privacy"]);
                $stream = new Stream();
                $flv = $stream->getFLVUrl($live_info['sn'], ($live_info['partner']), $live_info['region'], $replay);
                //istudio合作
            if (!empty($live_info['pullurl'])) {
                $flv = $live_info['pullurl'];
            }

                $user = new User();
                $userinfo = $user->getUserInfo($live_info["uid"]);

                $feed_info = array(
                'cover'        => ($live_info['cover'] != '') ? $live_info['cover'] : $userinfo['avatar'],
                'relateid'    => $relateid,
                'addtime'    => $live_info['addtime'],
             'endtime'    => $live_info['endtime'],
                'title'        => $live_info['title'],
                'width'        => $live_info['width'],
                'height'    => $live_info['height'],
                'watches'    => empty($live_info['watches']) ? 1 : $live_info['watches'],
                'praises'    => $live_info['praises'],
             'city'        => empty($live_info['city']) ? '火星' : $live_info['city'],
                'extends'    => $live_info['extends'],
                'virtual'    => $live_info['virtual'] == 'Y' ? true : false,
             'region'    => $live_info['region'],
                'flv'        => empty($flv) ? "" : $flv,
             'privacy'   => ! empty($privacy) ? true : false,
             'privacyid' => !empty($privacy['privacyid']) ? $privacy['privacyid'] : '0',
             'privacys'  => $privacy
                );

                if (!empty($privacy) && isset($privacy['privacyid'])) {
                    $feed_info['watches'] = Counter::get(Counter::COUNTER_TYPE_LIVE_PRIVACY_WATCHES, $privacy['privacyid']);
                    if (empty($feed_info['watches'])) {
                        $feed_info['watches'] = "0";
                    }
                }

                if (!empty($privacy) && isset($privacy['privacyid']) && Context::get("version") >= '2.6.2') {
                    $aes = new AES;
                    $aes_iv = substr(md5($relateid.$userid), 0, 16);
                    $aes_key = substr(md5($userid), 0, 16);
                    $flv = $aes->aes128cbcEncrypt($flv, $aes_iv, $aes_key);

                    $feed_info['iv'] = $aes_iv;
                    $feed_info['k']  = $aes_key;
                    $feed_info['flv'] = $flv;
                }
            break;
        case self::FEEDS_REPLAY:
            $live = new Live();
            $live_info = $live->getLiveInfo($relateid);
            if (!(!empty($live_info) &&  $live_info['replay']=='Y' && $live_info['replayurl'] !='')) {
                return false;
            }

            $user = new User();
            $userinfo = $user->getUserInfo($live_info["uid"]);
            //$privacy   = Privacy::hasPrivacyLive($live_info["uid"], $live_info["addtime"], $live_info["endtime"], $relateid);
            //$privacy = Privacy::getPrivacyInfoByLiveInfo($live_info["privacy"]);
            $privacy = Privacy::getReplayPrivacyInfoByLiveInfo($live_info["privacy"]);
            $userid  = intval(Context::get("userid"));
            $cache = Cache::getInstance("REDIS_CONF_CACHE");
            $praise_redis_key = Live::REPLAY_PRAISE_REDIS_KEY. $relateid . "_" . $userid;

            $praise_status = $cache->get($praise_redis_key) > 0 ? 1 : 0;

            $stream = new Stream();
            $subtitleUrl = Context::getConfig("STATIC_DOMAIN")."/" . $live_info['liveid']."/replay/index.txt";
            $feed_info = array(
                'cover' => ($live_info['cover'] != '') ? $live_info['cover'] : $userinfo['avatar'],
                'relateid' => $relateid,
                'addtime' => $live_info['addtime'],
                'endtime' => $live_info['endtime'],
                'stime' => $live_info['stime'],
                'etime' => $live_info['etime'],
                'title' => $live_info['title'],
                'width' => $live_info['width'],
                'height' => $live_info['height'],
                'watches' => $live_info['watches'],
                'praises' => $live_info['praises'],
             'praise_status' => $praise_status,
                'city'        => empty($live_info['city']) ? '火星' : $live_info['city'],
                'extends' => $live_info['extends'],
                'replayurl' => ($live_info['replayurl'] != '') ? $stream->getRepalyUrl($live_info['partner'], $live_info['region'], $live_info['replayurl']) : '',
                'virtual' => $live_info['virtual'] == 'Y' ? true : false,
                'region' => $live_info['region'],
                'subtitleUrl' => ($live_info['replayurl'] != '') ? $subtitleUrl : '',
                'privacy'   => ! empty($privacy) ? true : false,
             'privacyid' => !empty($privacy['privacyid']) ? $privacy['privacyid'] : '0',
                'privacys'  => $privacy,
             'has_custom_price' => Privacy::hasCustomPrice($userid),
             'max_replay_price' => isset($privacy['paylong']) ? Privacy::getReplayPrice($privacy['paylong']) : 1000,
                'price' => ! empty($privacy['replay_price']) ? $privacy['replay_price'] : 0
            );

            if (!empty(privacy) && isset($privacy['privacyid'])) {
                $feed_info['watches'] = Counter::get(Counter::COUNTER_TYPE_LIVE_PRIVACY_WATCHES, $privacy['privacyid']);
                if (empty($feed_info['watches'])) {
                     $feed_info['watches'] = "0";
                }
            }

            if (!empty(privacy) && isset($privacy['privacyid']) && Context::get("version") >= '2.6.2') {
                $aes = new AES;
                $aes_iv = substr(md5($relateid.$userid), 0, 16);
                $aes_key = substr(md5($userid), 0, 16);
                $flv = $aes->aes128cbcEncrypt($flv, $aes_iv, $aes_key);

                $feed_info['iv'] = $aes_iv;
                $feed_info['k']  = $aes_key;
                $feed_info['flv'] = $flv;
            }
            $feed_info['replayurls'] = $live->getReplayurlList($live_info);
            break;
        case self::FEEDS_IMAGE:
            $image = new Image();
            $image_info = $image->getImageInfo($relateid);

            if (empty($image_info) || empty($image_info['url'])) {
                 return false;
            }

                $user = new User();
                $userinfo = $user->getUserInfo($image_info["uid"]);

                $feed_info = array(
                'cover'        => $image_info['url'],
                'relateid'    => $relateid,
                'addtime'    => $image_info['addtime'],
                'width'        => $image_info['width'],
                'height'    => $image_info['height'],
                'praises'    => $image_info['praises'],
                'watches'    => $image_info['watches'],
                'praise_status'=> $image_info['praise_status'],
                );
            break;
        case self::FEEDS_VIDEO:
            $video = new Video();
            $video_info = $video->getVideoInfo($relateid);

            if (empty($video_info) || empty($video_info['cover'])) {
                return false;
            }

            $user = new User();
            $userinfo = $user->getUserInfo($video_info["uid"]);
            $feed_info = array(
            'cover'        => $video_info['cover'],
            'relateid'    => $relateid,
            'addtime'    => $video_info['addtime'],
            'mp4'        => $video_info['mp4'],
            'width'        => $video_info['width'],
            'height'    => $video_info['height'],
            'duration'    => $video_info['duration'],
            'praises'    => $video_info['praises'],
            'watches'    => $video_info['watches'],
            'praise_status'=> $video_info['praise_status'],
             'topic'     => $video_info['topic'],
             'city'        => empty($video_info['city']) ? '火星' : $video_info['city'],
             'istop'     => $extends['top'] == 'Y' ? true : false

                );
            break;
        case self::FEEDS_USER:
            $user = new User();
            $userinfo = $user->getUserInfo($relateid);

            $feed_info = array(
            'followers'    => Follow::countFollowers($relateid),//Counter::get(Counter::COUNTER_TYPE_FOLLOWERS, $relateid),
            'followings'=> Follow::countFollowings($relateid),//Counter::get(Counter::COUNTER_TYPE_FOLLOWINGS, $relateid),
            "signature"  => (string)$userinfo["signature"],
                );
            break;
        case self::FEEDS_VIDEO_SCREEN:
            $video = new Video();
            $video_info = $video->getVideoInfo($relateid);

            if (empty($video_info) || empty($video_info['cover'])) {
                return false;
            }

            $user = new User();
            $userinfo = $user->getUserInfo($video_info["uid"]);

            $feed_info = array(
                'cover'        => $video_info['cover'],
                'relateid'    => $relateid,
                'addtime'    => $video_info['addtime'],
                'mp4'        => $video_info['mp4'],
                'width'        => $video_info['width'],
                'height'    => $video_info['height'],
                'duration'    => $video_info['duration'],
                'praises'    => $video_info['praises'],
                'watches'    => $video_info['watches'],
                'praise_status'=> $video_info['praise_status'],
                'topic'     => $video_info['topic'],
                'city'      => $video_info['city'],
            );
            break;
        case self::FEEDS_IMAGE_SCREEN:
            $image = new Image();
            $image_info = $image->getImageInfo($relateid);

            if (empty($image_info) || empty($image_info['url'])) {
                return false;
            }

            $user = new User();
            $userinfo = $user->getUserInfo($image_info["uid"]);

            $feed_info = array(
             'cover'        => $image_info['url'],
            'relateid'    => $relateid,
            'addtime'    => $image_info['addtime'],
            'width'        => $image_info['width'],
             'height'    => $image_info['height'],
             'praises'    => $image_info['praises'],
             'watches'    => $image_info['watches'],
             'praise_status'=> $image_info['praise_status'],
            );
            break;
        case self::FEEDS_TOPIC:
            if (empty($extends)) {
                return false;
            }
            if (!($extends['online'] == 1 && $extends['position'] > 0)) {
                return false;
            }

            $extends['relateid'] = $extends['topicid'];
            $extends['istop'] = false;
            $feed_info = $extends;

            $userinfo = array('type'=>self::FEEDS_TOPIC);
            break;
        }

        $feed_info['type'] = $type;
        if (empty($userinfo)) {
            $userinfo = new stdClass();
        }
        return array("feed"=>$feed_info, "author"=>$userinfo);
    }

    public function getActivingLiveUser($uids)
    {
        /*{{{通过用户id获取正在直播的用户*/
        $dao_feeds = new DAOFeeds();

        $live_list = $dao_feeds->getActivingLiveByUserId($uids);

        $activing_live = array();
        foreach ($live_list as $value) {
            $activing_live[$value['uid']] = $value['relateid'];
        }

        foreach ($uids as $uid) {
            if (!in_array($uid, array_keys($activing_live))) {
                $activing_live[$uid] = false;
            }
        }

        return $activing_live;
    }/*}}}*/

    public function getBucketFeeds($bucket_name, $offset, $num)
    {
        //大主播列表放入云空array('是否地域过滤','是否大主播过滤','大主播列表')
        $cache = Cache::getInstance("REDIS_CONF_CACHE");

        $cache_forbidden = Cache::getInstance("REDIS_CONF_FORBIDDEN");
        $isRegionHide = $cache_forbidden->get('forbidden_region_biglive_key');
        $platform = Context::get("platform");
        $platform = strtolower($platform);
        $big_live = $cache_forbidden->get("forbidden_biglive_key_{$platform}");
        $part_big_live = $cache_forbidden->get("forbidden_part_biglive_key_{$platform}");

        $big_liver_keys = $cache->get("big_liver_keys");
        $big_liveids = $big_live_list = explode(',', $big_liver_keys);

        $user_ip = Util::getIP();
        list($province, $city, $district) = Util::ip2Location($user_ip);

        $lng = Context::get("lng");
        $lat = Context::get("lat");
        if(!empty($lng) && !empty($lat)) {
            $dispatch = new DispatchStream();
            $distance = $dispatch->getDistance(39.9150483, 116.3908177, $lat, $lng);
            if($distance<=30) {
                $city="北京";
            }
        }
        //东莞深圳也视为北京
        if($city=="东莞" || $city=="深圳" || $city=="上海") {
            $city="北京";
        }

        $userid = Context::get("userid");
        if(in_array($userid, array(23081060, 10132738))) {
            $city = 'test';
        }

        $show_hot_back = array('ux001','ux002','ux003','ux004','ux005','ux006','ux007','ux008','ux009','ux010','ux011','ux012','ux013','ux014','ux015','ux016','ux017','ux018','ux019','ux020','ux021','ux022','ux023','ux024','ux025','ux026','ux027','ux028','ux029','ux030','ux031','ux032','ux033','ux034','ux035','2001','2002','2003','2004','2005','2006','2007','2008','2009','2010','2011','2012','2013','2014','2015','2016','2017','2018','2019','2020','2021','2022','2023','2024','2025','2026','2027','2028','2029','2030','2031','2032','2033','2034','2035','cpa00001','cpa00002','cpa00003','cpa00004','cpa00005','cpa00006','cpa00007','cpa00008','cpa00009','cpa00010','cpa00011','cpa00012','cpa00013','cpa00014','cpa00015','cpa00016','cpa00017','cpa00018','cpa00019','cpa00020','cpa00021','cpa00022','cpa00023','cpa00025','cpa00026','cpa00027','cpa00028','cpa00029','cpa00030','cpa00031','cpa00032','cpa00033','cpa00034','cpa00035','cpa00036','cpa00037','cpa00038','cpa00039','cpa00040','cpa00041','cpa00042','cpa00043','cpa00044','cpa00045','cpa00046','cpa00047','cpa00048','cpa00049','cpa00050','cpa00051','cpa00052','cpa00053','cpa00054','cpa00055','cpa00056','cpa00057','cpa00058','cpa00059','cpa00060','cpa00061','cpa00062','cpa00063','cpa00064','cpa00065','cpa00066','cpa00067','cpa00068','cpa00069','cpa00070','cpa00071','cpa00072','cpa00073','cpa00074','cpa00075','cpa00076','cpa00077','cpa00078','cpa00079','cpa00080','cpa00081','cpa00082','cpa00083','cpa00084','cpa00085','cpa00086','cpa00087','cpa00088','cpa00089','cpa00090','cpa00091','cpa00092','cpa00093','cpa00094','cpa00095','cpa00096','cpa00097','cpa00098','cpa00099','cpa00100','as001as002','as003','as004','as005','as006','as007','as008','as009','as010','as011','as012','as013','as014','as015','as016','as017','as018','as019','as020','as021','as022','as023','as024','as025','as026','as027','as028','as029','as030','as031','as032','as033','as034','as035','as036','as037','as038','as039','as040','as041','as042','as043','as044','as045','as046','as047','as048','as049','as050','as051','as052','as053','as054','as055','as056','as057','as058','as059','as060','as061','as062','as063','as064','as065','as066','as067','as068','as069','as070','as071','as072','as073','as074','as075','as076','as077','as078','as079','as080','as081','as082','as083','as084','as085','as086','as087','as088','as089','as090','as091','as092','as093','as094','as095','as096','as097','as098','as099','as100','jzcpa001','jzcpa002','jzcpa003','jzcpa004','jzcpa005','jzcpa006','jzcpa007','jzcpa008','jzcpa009','jzcpa010','jzcpa011','jzcpa012','jzcpa013','jzcpa014','jzcpa015','jzcpa016','jzcpa017','jzcpa018','jzcpa019','jzcpa020','jzcpa021','jzcpa022','jzcpa023','jzcpa024','jzcpa025','jzcpa026','jzcpa027','jzcpa028','jzcpa029','jzcpa030','xwccpa001','xwccpa002','xwccpa003','xwccpa004','xwccpa005','xwccpa006','xwccpa007','xwccpa008','xwccpa009','xwccpa010','xwccpa011','xwccpa012','xwccpa013','xwccpa014','xwccpa015','xwccpa016','xwccpa017','xwccpa018','xwccpa019','xwccpa020','chcpa001','chcpa002','chcpa003','chcpa004','chcpa005','chcpa006','chcpa007','chcpa008','chcpa009','chcpa010','TPcpa001','TPcpa002','TPcpa003','TPcpa004','TPcpa005','TPcpa006','TPcpa007','TPcpa008','TPcpa009','TPcpa010','zycpa001','zycpa002','zycpa003','zycpa004','zycpa005','zycpa006','zycpa007','zycpa008','zycpa009','zycpa010','kkcpa001','kkcpa002','kkcpa003','kkcpa004','kkcpa005','kkcpa006','kkcpa007','kkcpa008','kkcpa009','kkcpa010','kkcpa011','kkcpa012','kkcpa013','kkcpa014','kkcpa015','kkcpa016','kkcpa017','kkcpa018','kkcpa019','kkcpa020','kkcpa021','kkcpa022','kkcpa023','kkcpa024','kkcpa025','kkcpa026','kkcpa027','kkcpa028','kkcpa029','kkcpa030','ztcpa001','ztcpa002','ztcpa003','ztcpa004','ztcpa005','ztcpa006','ztcpa007','ztcpa008','ztcpa009','ztcpa010','yhcpa001','yhcpa002','yhcpa003','yhcpa004','yhcpa005','yhcpa006','yhcpa007','yhcpa008','yhcpa009','yhcpa010','wscpa001','wscpa002','wscpa003','wscpa004','wscpa005','wscpa006','wscpa007','wscpa008','wscpa009','wscpa010','qmcpa001','qmcpa002','zycpa011','zycpa012','zycpa013','zycpa014','zycpa015','zycpa016','zycpa017','zycpa018','zycpa019','zycpa020', 'wscpa001', 'wscpa002', 'wscpa003', 'wscpa004', 'wscpa005', 'wscpa006', 'wscpa007', 'wscpa008', 'wscpa009', 'wscpa010', 'yhcpa003', 'yhcpa011', 'yhcpa012', 'yhcpa013', 'yhcpa014', 'yhcpa015', 'yhcpa016', 'yhcpa017', 'yhcpa018', 'yhcpa019', 'yhcpa020', 'wukucpa001', 'wukucpa002');
        $channel = Context::get("channel");


        if(in_array($userid, $big_liveids)) {
            $city = 'xs';
            $channel = '2001';
        }

        // $medal = new UserMedal();
        // $vip_level = $medal->getUserMedal($userid, 'vip');
        // if(!empty($vip_level)){
        //     $channel = '2001';
        // }

        if($cache_forbidden->sIsMember('forbidden_hot_white_key', $userid)) {
            $city = '北京';
            $channel = 'ux000';
        }

        $app_name = Context::get("app_name");
        if($app_name=='zhuiMeng') {
            $city = '北京';
            $channel = 'ux000';
        }

        if(in_array($userid, $big_liveids)) {
            $city = 'xs';
            $channel = '2001';
        }

        if(in_array($userid, array(22859939, 20014669, 20025370, 23018122, 20883682, 22711542, 23105508, 10282739, 10205989, 10029380, 10183864, 11213956, 10155027, 10055868, 23081060, 10003164, 10748471, 10000023))) {
            $city = 'test';
            $channel = 'ux001';
            $platform=='ios';
        }

        //'mx10001', 'mx10014', 'mx10021', 'mx10002', 'mx10003', 'mx10004', 'mx10005', 'mx10006', 'mx10007', 'mx10008', 'mx10009', 'mx10010', 'mx10011', 'mx10012', 'mx10013', 'mx10015', 'mx10056', 'mx10029', 'mx10037'
        if(Config::convertVersion(Context::get("version")) >= Config::convertVersion('2.9.9') && in_array($channel, array('mx10001', 'mx10014', 'mx10021', 'mx10002', 'mx10003', 'mx10004', 'mx10005', 'mx10006', 'mx10007', 'mx10008', 'mx10009', 'mx10010', 'mx10011', 'mx10012', 'mx10013', 'mx10015', 'mx10056', 'mx10029', 'mx10037'))) {
            $city = '北京';
            $channel = 'ux000';
        }

        $region_cache = $city=='北京' ? 'beijing' : 'other';

        if($bucket_name=='china_live_hot' && in_array($channel, $show_hot_back)) {
            $bucket_name = 'china_live_hot_back';
        }

        if($bucket_name=='china_live_hot_back' && !empty($isRegionHide) && $city=='北京') {
            $bucket_name = 'china_live_hot';
        }

        //云控版本
         $config = new Config();
         $config_info = $config->getConfig(Context::get("region"), 'recommend_hide_b', Context::get("platform"), Context::get("version"));

        $cache_key = "bucket_feeds_{$region_cache}_{$platform}_{$bucket_name}_{$offset}";
        //if(Config::convertVersion(Context::get("version")) >= Config::convertVersion('2.9.11') && $platform=='ios'){
        if(!empty($config_info['value'])) {
             $cache_key = "bucket_feeds_{$region_cache}_{$platform}_{$bucket_name}_{$offset}_2.9.1";
        }
        $cache_info = $cache->get($cache_key);
        if($cache_info && $num!=1) {
            $cache_info = json_decode($cache_info, true);

            if($cache_info['feeds']) {
                foreach ($cache_info['feeds'] as $k => $cache_info_list) {
                    if (empty($cache_info_list['author'])) {
                        $cache_info['feeds'][$k]['author'] = new stdClass();
                    }
                }
            }

            return $cache_info;
        }
        $bucket     = new Bucket();
        $bucket_info = $bucket->getBucket($bucket_name);
        Interceptor::ensureNotEmpty($bucket_info, ERROR_PARAM_DATA_NOT_EXIST, 'bucket_name');

        $original_bucket_name = $bucket_name;
        if ($bucket_info["forward"]) {
            $bucket_name = $bucket_info["forward"];
            $bucket_info = $bucket->getBucket($bucket_name);
        }

        if($original_bucket_name=='china_anchor_hot' || $original_bucket_name=='abroad_anchor_hot') {
            $anchor_hot_num = $num;
            $num = 200;
        }else{
            $back_num = $num;
            $num = 100;
        }

        $bucket_element = new BucketElement();
        list ($list, $next) = $bucket_element->fetch($bucket_name, $offset, $num, DAOBucketElement::PADING_OFFSET);

        if (is_array($list)) {
            if($original_bucket_name=='china_anchor_hot' || $original_bucket_name=='abroad_anchor_hot') {
                $next = 0;

                $uid_list = array();
                $uid = Context::get("userid");
                $follow = new Follow();

                foreach ($list as $key=>$value) {
                    if(!empty($uid)) {
                        $is_follow = $follow->isFollowed($uid, array($value['relateid']));
                        if($is_follow[$value['relateid']] || $value['relateid']==$uid || count($uid_list)>=$anchor_hot_num+10) {
                            continue;
                        }
                    }
                    $uid_list[] = $value;
                }

                shuffle($uid_list);
                $list = array_slice($uid_list, 0, 9);
            }

            $feeds = new Feeds();
            $feed_list = $feeds->getBatchFeedList($list);
            $bucket_list = array();

            foreach ($list as $key=>$value) {
                $feed_info = $feed_list[$value['relateid']];
                if(!empty($feed_info)) {
                    if ($feed_info['feed']['privacy'] == true && Context::get("version") <= '2.6.3') {
                        continue;
                    }
                    if(empty($feed_info['author']) && $value['type']!=Feeds::FEEDS_TOPIC) {
                        continue;
                    }
                    if($bucket_name=='china_anchor_hot' || $bucket_name=='abroad_anchor_hot') {
                        if(count($bucket_list)>=$anchor_hot_num) {
                            continue;
                        }
                    }

                    if(Defriend::isDefriend($feed_info['author']['uid'])) {
                        continue;
                    }

                    if($original_bucket_name=='china_live_hot') {
                        if($big_live) {
                            if(in_array($feed_info['author']['uid'], $big_live_list)) {
                                continue;
                            }
                        }
                    }

                    //if($original_bucket_name=='china_live_latest' && !in_array($channel, $show_hot_back)){
                    if($original_bucket_name=='china_live_latest') {
                        if($big_live) {
                            if(in_array($feed_info['author']['uid'], $big_live_list)) {
                                continue;
                            }
                        }
                    }

                    if(!empty($isRegionHide) && $city=='北京') {
                        if(in_array($feed_info['author']['uid'], $big_liveids)) {
                            continue;
                        }
                    }

                    //if(Config::convertVersion(Context::get("version")) >= Config::convertVersion('2.9.11') && $platform=='ios'){
                    if(!empty($config_info['value'])) {
                        if(in_array($feed_info['author']['uid'], $big_liveids)) {
                            continue;
                        }
                    }

                    $bucket_list[] = $feed_info;
                }
                if(!empty($back_num) && $back_num==count($bucket_list)) {
                    $next = $value['score'];
                    break;
                }
            }

        }

        $bucket_feeds = array(
            'feeds' => $bucket_list,
            'total' => $bucket_info["total"],
            'offset' => (string)$next,
            'extends' => ''
        );
        $cache->setex($cache_key, 10, json_encode($bucket_feeds));

        return $bucket_feeds;
    }

    public function getCityBucketFeeds($bucket_name, $offset, $num)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $cache_key = "bucket_feeds_city_".md5($bucket_name.$offset.$num);

        $cache_info = $cache->get($cache_key);
        if($cache_info && $num!=1) {
            $bucket_feeds = json_decode($cache_info, true);
        }else{
            $bucket     = new Bucket();
            $bucket_info = $bucket->getBucket($bucket_name);
            Interceptor::ensureNotEmpty($bucket_info, ERROR_PARAM_DATA_NOT_EXIST, 'bucket_name');

            $original_bucket_name = $bucket_name;
            if ($bucket_info["forward"]) {
                $bucket_name = $bucket_info["forward"];
                $bucket_info = $bucket->getBucket($bucket_name);
            }

            $bucket_element = new BucketElement();
            list ($list, $next) = $bucket_element->fetch($bucket_name, $offset, $num, DAOBucketElement::PADING_LIMIT);
            $bucket_list = array();

            if (is_array($list)) {
                foreach($list as $k=>$v){
                    $liveids[]['relateid'] = $v['extends']['liveid'];
                    $addiences[] = $v['relateid'];
                }
                // foreach($list as $k=>$v){
                //     $onlineUsers[$v['relateid']] = $v['extends']['liveid'];
                // }
                // $feed_list = OnlineUsers::genUserInfos($onlineUsers, Context::get("userid"), 1);
                $feed_list = Feeds::getBatchLives($liveids);

                $user = new User();
                $userinfos = $user->getUserInfos($addiences);

                foreach ($list as $k=>$v) {
                    if($feed_list[$v['extends']['liveid']]) {
                        $feed_info = $feed_list[$v['extends']['liveid']];
                        $userinfo = $userinfos[$v['relateid']];

                        $feed_info['online'] = array(
                            "uid"       => $userinfo['uid'],
                            "nickname"  => $userinfo['nickname'],
                            "level"     => intval($userinfo['level']),
                            "exp"       => intval($userinfo['exp']),
                            "gender"    => $userinfo['gender'],
                            "avatar"    => $userinfo['avatar'],
                            "vip"       => (int) $userinfo['vip'],
                            "onliveing" => $v['relateid'] == $feed_info['author']['uid']?1:0,
                            "title"     => $v['relateid'] == $feed_info['author']['uid']?$feed_info['author']['nickname'] . "直播中":$title = $feed_info['author']['nickname'] . "的直播间",
                            "liveid"    => $feed_info['feed']['relateid'],
                            "medal"     => $userinfo['medal'],
                            "watches"   => empty($feed_info['feed']['watches']) ? 0: $feed_info['feed']['watches'],
                            "distance"  => 0,
                            "lng"       => $v['extends']['lng'],
                            "lat"       => $v['extends']['lat'],
                            "anchor_token"=>$userinfo['anchor_token'],
                        );

                        $bucket_list[] = $feed_info;
                    }
                }
            }
            $config     = new Config();
            $city_list  = $config->getConfig("china", "same_city_list", "server", "1.0.0");
            $city_list  = json_decode($city_list['value'], true);

            $bucket_feeds = array(
                'feeds' => $bucket_list,
                'citys' => $city_list,
                'offset' => $next,
                'extends' => ''
            );
            $cache->setex($cache_key, 10, json_encode($bucket_feeds));
        }

        if($bucket_feeds['feeds']) {
            $lng = Context::get("lng");
            $lat = Context::get("lat");

            foreach ($bucket_feeds['feeds'] as $k => $v) {
                $online = $v['online'];
                if($lng && $lat && $online['lat'] && $online['lng']) {
                    $distance = DispatchStream::getDistance($online['lat'], $online['lng'], $lat, $lng);
                    $online['distance'] = ceil($distance*10)/10;
                    if($online['distance']>10) {
                        $online['distance'] = (int) $online['distance'];
                    }
                }else{
                    $online['distance'] = -1;
                }

                unset($online['lng']);
                unset($online['lat']);

                $bucket_feeds['feeds'][$k]['online'] = $online;
            }
        }

        return $bucket_feeds;
    }
}
?>
