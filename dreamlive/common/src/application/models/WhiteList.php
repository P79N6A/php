<?php
class WhiteList
{
    const WHITE_LIST_CACHE_PRE='white:list:';
    const WL_PRIVATE_GIFT='send_private_gift';

    public static function reload($key)
    {
        $k=self::WHITE_LIST_CACHE_PRE.$key;
        $cache=Cache::getInstance("REDIS_CONF_CACHE");
        $daoWhiteList=new DAOWhiteList();
        $d=$daoWhiteList->getWhiteList($key);
        if (empty($d)) { $d=array();
        }
        $cache->set($k, json_encode($d));
    }
    public static function setData($key,$data)
    {
        $daoWhiteList=new DAOWhiteList();
        $re=$daoWhiteList->setData($key, $data);
        if ($re) { self::reload($key);
        }
        return $re;
    }
    
    public static function getList($key)
    {
        $k=self::WHITE_LIST_CACHE_PRE.$key;
        $cache=Cache::getInstance("REDIS_CONF_CACHE");
        $d=$cache->get($k);
        $dt=json_decode($d, true);
        if (!$dt) { return array();
        }
        return $dt;
    }
    
    public static function isInList($item,$key)
    {
        $d=self::getList($key);
        if (in_array($item, $d)) { return true;
        }
        return false;
    }
    
    public static function getAll()
    {
        $dao=new DAOWhiteList();
        return $dao->getAllData();
    }

    public static function getPrivateGiftWhiteList()
    {
        return self::getList(self::WL_PRIVATE_GIFT);
    }
    public static function setPrivateGiftWhiteList(array $data=array())
    {
        return self::setData(self::WL_PRIVATE_GIFT, $data);
    }
}