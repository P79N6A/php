<?php
class Operation
{
    const ADMIN_USER_REDIS_KEY = "yunying_opt_uids";
    
    public function addRedis($uids)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $uids_string = $cache->get(self::ADMIN_USER_REDIS_KEY);
        if (is_array($uids)) {
            
            foreach ($uids as &$u) {
                $u = trim($u);
                Interceptor::ensureNotFalse($u > 0, ERROR_PARAM_INVALID_FORMAT, "uids");
                Interceptor::ensureNotFalse(is_int($u), ERROR_PARAM_INVALID_FORMAT, "uids");
            }
            
            $add_uid_string = implode(',', $uids);
            
            if (!empty($uids_string)) {
                $uids_string = $uids_string. ',' . $add_uid_string;
            } else {
                $uids_string = $add_uid_string;
            }
            
        } else {
            
            Interceptor::ensureNotFalse($uids > 0, ERROR_PARAM_NOT_SMALL_ZERO, "uid");
            if (!empty($uids_string)) {
                $uids_string = $uids_string. ',' . $uids;
            } else {
                $uids_string = $uids;
            }
            
        }
        
        $this->resetRedis(explode(',', $uids_string));
        
        return true;
    }
    
    
    /**
     * 从缓存删除运营人员
     *
     * @param  int $del_uid
     * @return boolean
     */
    public function delRedis($del_uid)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $uid_string = $cache->get(self::ADMIN_USER_REDIS_KEY);
        
        if (!empty($uid_string)) {
            $uids_array = explode(',', $uid_string);
            
            
            $new_uids = [];
            foreach ($uids_array as $uid) {
                if ($uid != $del_uid) {
                    $new_uids[] = $uid;
                } else {
                    
                }
            }
            
            $this->resetRedis($new_uids);
        } else {
            return true;
        }
    }
    
    
    private function resetRedis( array $uid_array)
    {
        try {
            
            $cache = Cache::getInstance("REDIS_CONF_CACHE");
            if (!empty($uid_array)) {
                $uid_array = array_unique($uid_array);
                $uids_string = implode(',', $uid_array);
                $cache->set(self::ADMIN_USER_REDIS_KEY, $uids_string);
            } else {
                $cache->del(self::ADMIN_USER_REDIS_KEY);
            }
            
            
            return true;
        } catch (Exception $e) {
            throw new BizException(ERROR_SYS_REDIS);
        }
        
        return true;
    }
    
    
    public static function getUidsArray()
    {
        try {
            $cache = Cache::getInstance("REDIS_CONF_CACHE");
            $uids_string = $cache->get(self::ADMIN_USER_REDIS_KEY);
            if (!empty($uids_string)) {
                return explode(',', $uids_string);
            } else {
                return false;
            }
            
        } catch (Exception $e) {
            throw new BizException(ERROR_SYS_REDIS);
        }
        
        return false;
    }
    
}