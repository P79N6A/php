<?php

class Topic
{

    /**
     * 添加话题
     *
     * @param string $name            
     * @param int    $relateid            
     */
    public static function addTopic($name, $region,$relateid, $type)
    {
        $topic = new DAOTopic();
        $list  = explode(',', $relateid);
        if(!empty($list)) {
            foreach($list as $item){
                $topic->addTopic($name, $region, $item, $type);
                self::delRedis($item, $type);
            }
        }
        return true;
    }

    /**
     * 删除
     *
     * @param string $name            
     * @param int    $relateid            
     */
    public static function delTopic($name,$region, $relateid, $type)
    {
        $topic = new DAOTopic();
        $topic->delTopic($name, $region, $relateid, $type);
        return self::delRedis($relateid, $type);
    }

    /**
     * 清空
     *
     * @param string $name            
     * @param int    $relateid            
     */
    public static function cleanTopic($name,$region)
    {
        $topic = new DAOTopic();
        if($topic->cleanTopic($name, $region)) {
            $list = $topic->getRelateidList($name, $region);
            foreach($list as $item){
                self::delRedis($item, Feeds::FEEDS_VIDEO);
            }
        }
        return true;
    }

    /**
     * 删除redis
     *
     * @param int $relateid            
     * @param int $type            
     */
    public static function delRedis($relateid, $type)
    {
        $key = self::getRedisKey($relateid, $type);
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $cache->delete($key);
    }

    /**
     * 获取key
     *
     * @param int $relateid            
     * @param int $type            
     */
    public static function getRedisKey($relateid, $type)
    {
        switch ($type) {
        case Feeds::FEEDS_VIDEO:
            return "video_cache_$relateid";
                break;
        case Feeds::FEEDS_IMAGE:
            return "image_cache_$relateid";
                break;
        case Feeds::FEEDS_LIVE:
            return "L2_cache_live_$relateid";
                break;
        case Feeds::FEEDS_REPLAY:
            return "L2_cache_live_$relateid";
                break;
        default:
        }
    }
}
