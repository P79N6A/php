<?php
class Vip
{
    private static $level_current_list = array(
        1  => 500,
        2  => 1000,
        3  => 2000,
        4  => 5000,
        5  => 10000,
        6  => 20000,
        7  => 50000,
        8  => 100000,
        9  => 200000,
        10 => 500000,
    );
    private static $level_keep_list = array(
        1  => 300,
        2  => 500,
        3  => 1000,
        4  => 2000,
        5  => 5000,
        6  => 10000,
        7  => 20000,
        8  => 50000,
        9  => 100000,
        10  => 200000,
    );
    const KICK_GUARD_NONE = 0;
    const KICK_GUARD_OP   = 1;
    const KICK_GUARD_ALL  = 2;

    const SILENCE_GUARD_NONE = 0;
    const SILENCE_GUARD_OP   = 1;
    const SILENCE_GUARD_ALL  = 2;

    const REIDSKEY_PREFIX  = "dreamlive_vipconfig_";

    const TYPE_LOGO        = "logo";
    const TYPE_EXCLUSIVE   = "exclusive";
    const TYPE_RIDE        = "ride";
    const TYPE_RIDE_EXPIRE = "ride_expire";
    const TYPE_HORN_NUM    = "horn_num";
    const TYPE_PROP_NUM    = "prop_num";
    const TYPE_FONT_COLOR  = "font_color";
    const TYPE_SILENCE     = "silence";
    const TYPE_SILENCE_NUM = "silence_num";
    const TYPE_KICK        = "kick";
    const TYPE_KICK_NUM    = "kick_num";

    public static function getKeyConfig($level)
    {
        return self::REIDSKEY_PREFIX . $level;
    }

    public static function getKeyUserType($userid, $type)
    {
        return self::REIDSKEY_PREFIX . $type .'_'. $userid;
    }

    public static function getLevelCurrentConsume($level)
    {
        return self::$level_current_list[$level];
    }

    public static function getLevelKeepConsume($level)
    {
        return self::$level_keep_list[$level];
    }

    public static function getLevelConfigs($level)
    {
        $redis = Cache::getInstance("REDIS_CONF_USER");
        $type = array( 'logo','exclusive', 'ride','ride_expire','horn_num','prop_num','font_color','silence','silence_num','kick','kick_num');
        $levelConfig = $redis->hmget(self::getKeyConfig($level), $type);

        return $levelConfig !== false?$levelConfig:array();
    }

    public static function getLevelConfig($level, $type)
    {
        $redis = Cache::getInstance("REDIS_CONF_USER");
        
        $levelConfig = $redis->hget(self::getKeyConfig($level), $type);

        return $levelConfig !== false?$levelConfig:'';
    }

    public static function getMaxLevel()
    {

        return count(self::$level_current_list);
    }

    public static function getUserVipInfo($userid)
    {
        $daoVip = new DAOUserVip($userid);

        return  $daoVip->getUserInfo();
    }
    
    public static function getUserVipLevel($userid)
    {
        $current_level = 0;
        $keep_level    = 0;

        $daoVip = new DAOUserVip($userid);
        $infos  = $daoVip->getUserInfo();

        return self::calcUserVipLevel($infos['consume_current'], $infos['consume_keep']);
    }

    public static function calcUserVipLevel($current, $keep)
    {
        $current_level = 0;
        $keep_level    = 0;

        $current_list = array_reverse(self::$level_current_list, true);
        $keep_list    = array_reverse(self::$level_keep_list, true);
        
        foreach ($current_list as $k => $v) {
            if ($current >= $v) {
                $current_level = $k;
                break;
            }
        }
        foreach ($keep_list as $k => $v) {
            if ($keep >= $v) {
                $keep_level = $k;
                break;
            }
        }
        
        return max($current_level, $keep_level);
    }
    public static function getLeftNumber($userid, $type)
    {
        $userinfo = User::getUserInfo($userid);
        $level = intval($userinfo['vip']);

        if($level < 1 ) {
            return 0;
        }
        $redis = Cache::getInstance("REDIS_CONF_USER");
        $key        = self::getKeyUserType($userid, $type);
        $configkey  = self::getKeyConfig($level);

        $levelCount = $redis->hget($configkey, $type);

        if(!$levelCount) {
            return 0;
        }

        $userCount = $redis->get($key);

        if($userCount == false) {
            return intval($levelCount);
        }else if($userCount < $levelCount) {
            return intval($levelCount - $userCount);
        }

        return 0;
    }
    /**
     * 返回剩余num
     *
     * @return int -1 失败 >-1 剩余次数
     */
    public static function incrLeftNumber($userid, $type)
    {
        $userinfo = User::getUserInfo($userid);
        $level = intval($userinfo['vip']);

        if($level < 1 ) {
            return -1;
        }
        $redis = Cache::getInstance("REDIS_CONF_USER");
        $key        = self::getKeyUserType($userid, $type);
        $configkey  = self::getKeyConfig($level);

        $levelCount = $redis->hget($configkey, $type);

        if(!$levelCount) {
            return -1;
        }

        $userCount = $redis->get($key);

        if($userCount == false) {
            if(self::TYPE_HORN_NUM == $type) {
                $expire = strtotime('+1 month', strtotime(date('Y-m')));
            }else{
                $expire = strtotime('+1 day', strtotime(date('Y-m-d')));
            }

            $redis->incr($key);
            $redis->expireat($key, $expire);

            return intval($levelCount - 1);
        }else if($userCount < $levelCount) {
            $redis->incr($key);

            return intval($levelCount - $userCount - 1);
        }

        return -1;
    }

    public static function addUserConsumeCurrent($userid, $amont)
    {
        $daoVip = new DAOUserVip($userid);
        $infos  = $daoVip->getUserInfo();

        $level = self::calcUserVipLevel($infos['consume_current'], $infos['consume_keep']);

        $effected_rows = $daoVip->incrUserConsume($amont);
        
        if (! $effected_rows) {
            $effected_rows = $daoVip->addUserConsume($amont);
        }

        $newlevel = self::calcUserVipLevel($infos['consume_current']+$amont, $infos['consume_keep']);

        if($newlevel > $level) {
            UserMedal::addUserMedal($userid, UserMedal::KIND_VIP, $newlevel);
            $daoVip->addUptime($userid);
            
            $daoVipLog = new DAOUserVipLog($userid);
            $logid = $daoVipLog->addLog($userid, $level, $newlevel);

            $rideid = self::getLevelConfig($newlevel, self::TYPE_RIDE);
            $expire = self::getLevelConfig($newlevel, self::TYPE_RIDE_EXPIRE);
            if($expire && $rideid) {
                try{
                    $product = new Product();
                    $orderid = $product->sendRideByVip($userid, $rideid, $expire);
                    $daoVipLog->addAwardLog($logid, json_encode(array('ride'=>(string)$orderid)));
                }catch (Exception $e) {
                    $daoVipLog->addAwardLog($logid, json_encode(array('ride'=>array('code'=>$e->getCode(),'errmsg'=>$e->getMessage()))));
                    Logger::log("vip_send_ride", null, array("uid" => $userid,"errno" => $e->getCode(),"errmsg" => $e->getMessage()));
                }
            }

            User::reload($userid);
        }
        
        return $effected_rows;
    }

    public static function setVipLevel($userid, $newlevel)
    {
        $userinfo = User::getUserInfo($userid);
        $oldlevel = intval($userinfo['vip']);

        $dao = new DAOUserVip($userid);
    
        $info['consume_current'] = self::$level_current_list[$newlevel];
        $info['consume_keep'] = 0;
        if(!$dao->modUserVip($userid, $info)) {
            $dao->addUserConsume($info['consume_current']);
        }

        $daoVipLog = new DAOUserVipLog($userid);
        $daoVipLog->addLog($userid, $oldlevel, $newlevel);

        if($newlevel>0) {
            UserMedal::addUserMedal($userid, UserMedal::KIND_VIP, $newlevel);
        }else{
            UserMedal::delUserMedal($userid, UserMedal::KIND_VIP);
        }

        return true;
    }

}
?>