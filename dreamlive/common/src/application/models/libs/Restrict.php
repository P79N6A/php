<?php
class Restrict
{
    private static $_config = array(
        "needbind" => array(
            "prefix" => "restrict:needbind:",
            "frequency" => 1,
            "interval" => 86400
        ),
        "/user/getuserinfo" => array(
            "prefix" => "restrict:getuserinfo:",
            "frequency" => 120,
            "interval" => 60
        ),
        "loginerror" => array(
            "prefix" => "restrict:loginerror:",
            "frequency" => 5,
            "interval" => 86400
        ),
        "/follow/add" => array(
            "prefix" => "restrict:addfollow:",
            "frequency" => 60,
            "interval" => 60
        ),
        "/follow/multiAdd" => array(
            "prefix" => "restrict:maddfollow:",
            "frequency" => 60,
            "interval" => 60
        )
    );

    private static $_whites = array()

    ;

    private static function _getConfig($rule)
    {
        return isset(self::$_config[$rule]) ? self::$_config[$rule] : array(
            "prefix" => "restrict:" . md5($rule) . ":",
            "frequency" => 100000,
            "interval" => 1
        );
    }

    /**
     * 封禁检查
     * 
     * @param  $rule 规则名            
     * @param  $uniqid 封禁检查值            
     * @param  bool                   $update
     *            是否检查同时更新次数值，默认只检查是否触犯封禁次数
     * @return bool true 达到封禁次数 false 未达到封禁次数
     */
    public static function check($rule, $uniqid, $update = false)
    {
        foreach (self::$_whites as $white) {
            if (strpos($uniqid, $white) !== false) {
                return false;
            }
        }
        
        $config = self::_getConfig($rule);
        
        $key = $config["prefix"] . $uniqid;
        $limit = $config["frequency"];
        $interval = $config["interval"];
        
        $record = Cache::getInstance("REDIS_CONF_CACHE")->get($key);
        
        $record = ! empty($record) ? $record : "0_" . time();
        list ($times, $starttime) = explode("_", $record);
        
        $times = ! empty($times) ? $times : "0";
        $starttime = ! empty($starttime) ? $starttime : time();
        
        if (time() <= ($starttime + $interval)) {
            if ($times >= $limit) {
                return true;
            }
        } else {
            $times = 0;
            $starttime = time();
        }
        
        if ($update) {
            $record = ($times + 1) . "_" . $starttime;
            Cache::getInstance("REDIS_CONF_CACHE")->set($key, $record, $interval);
        }
        
        return false;
    }

    /**
     * 封禁触犯次数增加
     * 
     * @param
     *            $rule
     * @param
     *            $uniqid
     * @return bool
     */
    public static function add($rule, $uniqid)
    {
        return self::check($rule, $uniqid, true);
    }

    public static function delete($rule, $uniqid)
    {
        $config = self::_getConfig($rule);
        $key = $config["prefix"] . $uniqid;
        
        return Cache::getInstance("REDIS_CONF_CACHE")->delete($key);
    }
}
?>
