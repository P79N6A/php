<?php
class Counter
{
    const COUNTER_TYPE_PASSPORT_USERID = "user_userid";
    const COUNTER_TYPE_FOLLOWERS       = "followers";
    const COUNTER_TYPE_FOLLOWINGS      = "followings";

    public static function increase($type, $relateid, $number = 1)
    {
        $key = self::getKey($type, $relateid);
        $redis = self::getRedis($key);

        $total = $redis->incrBy($key, $number);

        return $total;
    }

    public static function decrease($type, $relateid, $number = 1)
    {
        $key = self::getKey($type, $relateid);
        $redis = self::getRedis($key);

        $total = $redis->incrBy($key, - $number);

        return $total;
    }

    public static function set($type, $relateid, $number)
    {
        $key = self::getKey($type, $relateid);
        $redis = self::getRedis($key);

        $total = $redis->set($key, $number);

        return $total;
    }

    public static function setex($type, $relateid, $number, $expire)
    {
        $key = self::getKey($type, $relateid);
        $redis = self::getRedis($key);

        $total = $redis->setex($key, $expire, $number);

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

    public static function sync2db($product, $type, $relateid, $value, $microtime){
        $dao = new DAOCounter();
        return $dao->setCounter($product, $type, $relateid, $value, $microtime);
    }

    protected static function getKey($type, $relateid)
    {
        return 'ptshare' . '_' . $type . '_' . $relateid;
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


    public static function getBatchCount($type, array $arrTemp){
        if(empty($type) || empty($arrTemp)){
            return false;
        }
        $arrKey = $relateids = array();
        foreach($arrTemp as $relateid){
            if(!empty($relateid)){
                $key = 'ptshare' . '_' . $type . '_' . $relateid;
                array_push($arrKey, $key);
                array_push($relateids, $relateid);
            }
        }
        $cache = Cache::getInstance("REDIS_CONF_COUNTER",0);
        $list  = $cache->mget($arrKey);
        return array_combine($relateids,$list);
    }
}

