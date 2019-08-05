<?php
class AntiSpamForbidden
{

    private function _getKey()
    {
        return 'message_forbidden_word';
    }
    
    private function _getIpKey()
    {
        return 'message_forbidden_ip';
    }
    
    private function _getDeviceKey()
    {
        return 'message_forbidden_device';
    }
    
    public function addForbiddenWord($keyword)
    {
        $cache = Cache::getInstance('REDIS_CONF_CACHE');
        return $cache->sadd($this->_getKey(), md5($keyword));
    }
    
    /**
     * 添加垃圾消息设备
     *
     * @param  unknown $ip
     * @return unknown
     */
    public function addForbiddenDevice($deviceid)
    {
        $cache = Cache::getInstance('REDIS_CONF_CACHE');
        return $cache->sadd($this->_getDeviceKey(), $deviceid);
    }
    
    /**
     * 验证设备
     *
     * @param  unknown $ip
     * @return unknown
     */
    public function checkForbiddenDevice($deviceid)
    {
        return Cache::getInstance('REDIS_CONF_CACHE')->sIsMember($this->_getDeviceKey(), $deviceid);
    }
    
    /**
     * 添加垃圾消息ip
     *
     * @param  unknown $ip
     * @return unknown
     */
    public function addForbiddenIp($ip)
    {
        $cache = Cache::getInstance('REDIS_CONF_CACHE');
        return $cache->sadd($this->_getIpKey(), $ip);
    }
    
    /**
     * 删除垃圾消息ip
     *
     * @param  unknown $ip
     * @return unknown
     */
    public function delForbiddenIp($ip)
    {
        $cache = Cache::getInstance('REDIS_CONF_CACHE');
        return $cache->srem($this->_getIpKey(), $ip);
    }
    
    /**
     * 删除垃圾消息ip
     *
     * @param  unknown $ip
     * @return unknown
     */
    public function delForbiddenDevice($deviceid)
    {
        $cache = Cache::getInstance('REDIS_CONF_CACHE');
        return $cache->srem($this->_getIpKey(), $deviceid);
    }
    
    /**
     * 验证ip
     *
     * @param  unknown $ip
     * @return unknown
     */
    public function checkForbiddenIp($ip)
    {
        return Cache::getInstance('REDIS_CONF_CACHE')->sIsMember($this->_getIpKey(), $ip);
    }
    
    public function checkForbidden($keyword)
    {
        return Cache::getInstance('REDIS_CONF_CACHE')->sIsMember($this->_getKey(), md5($keyword));
    }
}
