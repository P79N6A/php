<?php
class Counter
{
    const COUNTER_TYPE_FEEDS             = "feeds";// 直播作品自增器
    const COUNTER_TYPE_PASSPORT_FEEDS_NUM     = "user_feeds_num";
    const COUNTER_TYPE_PASSPORT_USERID         = "user_userid";
    const COUNTER_TYPE_REPLY_FLOOR             = "reply_floor";
    const COUNTER_TYPE_FOLLOWERS             = "followers";
    const COUNTER_TYPE_FOLLOWINGS             = "followings";
    const COUNTER_TYPE_LIVE_PRAISES         = "live_praises";
    const COUNTER_TYPE_IMAGE_PRAISES         = "image_praises";
    const COUNTER_TYPE_VIDEO_WATCHES         = "video_watches";
    const COUNTER_TYPE_IMAGE_WATCHES         = "image_watches";
    const COUNTER_TYPE_VIDEO_PRAISES         = "video_praises";
    const COUNTER_TYPE_LIVE_CHATS             = "live_chats";
    const COUNTER_TYPE_LIVE_REPLIES         = "live_replies";
    const COUNTER_TYPE_LIVE_WATCHES            = "live_watches";// 直播观看数
    const COUNTER_TYPE_LIVE_GIFT            = "live_gift";//直播间收礼数
    const COUNTER_TYPE_LIVE_TICKET          = "live_ticket";//直播间收入数
    const COUNTER_TYPE_LIVE_PRIVACY_TICKET  = "live_privacy_ticket";//直播间门票收益
    const COUNTER_TYPE_LIVE_PRIVACY_WATCHES = "live_privacy_watches";//直播间付费数

    const COUNTER_TYPE_ROUND_NUM            = "activity_round_card";//复活卡计数器

    const COUNTER_TYPE_CONSUME_MONEY        = "consum_diamond";//消耗钱数计数器
    const COUNTER_TYPE_STAKE_MONEY          = "stake_trackno";//赛道押注金额计数器

    const COUNTER_TYPE_PACKET_SHARE         = 'packet_share';   // 红包分享次数

    const COUNTER_TYPE_PAYMENT_SEND_GIFT    = "send_gift";
    const COUNTER_TYPE_PAYMENT_RECEIVE_GIFT    = "receive_gift";
    const COUNTER_TYPE_MATCH_RECEIVE_GIFT    = "match_gift";
    
    
    const COUNTER_TYPE_SHARE_LIVE            = "share_live";
    const COUNTER_TYPE_SHARE_IMAGE            = "share_image";
    const COUNTER_TYPE_SHARE_VIDEO            = "share_video";
    const COUNTER_TYPE_SHARE_USER            = "share_user";
    const COUNTER_TYPE_SHARE_REPLY            = "share_reply";
    const COUNTER_TYPE_WEB_CONTROLLER        = "web_content";
    

    public static function increase($type, $relateid, $number = 1)
    {
        $key = self::getKey($type, $relateid);
        $redis = self::getRedis($key);

        $total = $redis->incrBy($key, $number);

        if ($total !== false) {
            /*$base   =  dirname(dirname(dirname(dirname(dirname(__FILE__)))));
            require_once $base.'/process/ProcessClient.php';*/
            include_once 'process_client/ProcessClient.php';
            ProcessClient::getInstance('dream')->addTask('counter_change', array('type' => $type, 'relateid' => $relateid, 'value' => $total, 'microtime' => round(microtime(true) * 1000)));
        }

        return $total;
    }

    public static function decrease($type, $relateid, $number = 1)
    {
        $key = self::getKey($type, $relateid);
        $redis = self::getRedis($key);

        $total = $redis->incrBy($key, - $number);

        if ($total !== false) {
            /*$base   =  dirname(dirname(dirname(dirname(dirname(__FILE__)))));
            require_once $base.'/process/ProcessClient.php';*/
            include_once 'process_client/ProcessClient.php';
            ProcessClient::getInstance('dream')->addTask('counter_change', array('type' => $type, 'relateid' => $relateid, 'value' => $total, 'microtime' => round(microtime(true) * 1000)));
        }

        return $total;
    }

    public static function set($type, $relateid, $number)
    {
        $key = self::getKey($type, $relateid);
        $redis = self::getRedis($key);

        $total = $redis->set($key, $number);

        if ($total !== false) {
            include_once 'process_client/ProcessClient.php';
            ProcessClient::getInstance('dream')->addTask('counter_change', array('type' => $type, 'relateid' => $relateid, 'value' => $total, 'microtime' => round(microtime(true) * 1000)));
        }

        return $total;
    }

    public static function setex($type, $relateid, $number, $expire)
    {
        $key = self::getKey($type, $relateid);
        $redis = self::getRedis($key);

        $total = $redis->setex($key, $expire, $number);

        if ($total !== false) {
            include_once 'process_client/ProcessClient.php';
            ProcessClient::getInstance('dream')->addTask('counter_change', array('type' => $type, 'relateid' => $relateid, 'value' => $total, 'microtime' => round(microtime(true) * 1000)));
        }

        return $total;
    }

    public static function expire($type, $relateid, $expire)
    {
        $key = self::getKey($type, $relateid);
        $redis = self::getRedis($key);

        return $redis->expire($key, $expire);
    }

    public static function get($type, $relateid)
    {
        $key = self::getKey($type, $relateid);
        $redis = self::getRedis($key, true);
        $counter = $redis->get($key);
        return $counter!==false ? $counter : 0;
    }

    public static function gets($type, array $relateids)
    {
        foreach ($relateids as $relateid) {
            $key = self::getKey($type, $relateid);
            $redis = self::getRedis($key, true);

            $counter = $redis->get($key);
            $result[$relateid] = $counter!==false ? $counter : 0;
        }

        return $result;
    }

    public static function mixed($types, array $relateids)
    {
        $result = array();
        foreach ($types as $type) {
            foreach ($relateids as $relateid) {
                $key = self::getKey($type, $relateid);
                $redis = self::getRedis($key);

                $counter = $redis->get($key);
                $result[$relateid][$type] = $counter!==false ? $counter : 0;
            }
        }

        return $result;
    }

    public static function sync2db($product, $type, $relateid, $value, $microtime)
    {
        $dao = new DAOCounter();
        return $dao->setCounter($product, $type, $relateid, $value, $microtime);
    }

    protected static function getKey($type, $relateid)
    {
        return 'dreamlive' . '_' . $type . '_' . $relateid;
    }

    protected static function getRedis($key)
    {
        $hash = self::getRedisHash($key);

        return Cache::getInstance("REDIS_CONF_COUNTER", $hash);
    }

    protected static function getRedisHash($key)
    {
        return abs(crc32($key));
    }
    
    
    public static function getBatchCount($type, array $arrTemp)
    {
        if(empty($type) || empty($arrTemp)) {
            return false;
        }
        $arrKey = $relateids = array();
        foreach($arrTemp as $relateid){
            if(!empty($relateid)) {
                $key = 'dreamlive' . '_' . $type . '_' . $relateid;
                array_push($arrKey, $key);
                array_push($relateids, $relateid);
            }
        }
        $cache = Cache::getInstance("REDIS_CONF_COUNTER", 0);
        $list  = $cache->mget($arrKey);
        return array_combine($relateids, $list);
    }
}

