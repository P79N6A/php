<?php 
class IDGEN
{

    const
            SELLID    = 1,

            PACKAGEID = 2,

        end = '';

    public static function generate($namespace='', $length = 2)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        
        $key = "IDGEN:".date("Y-m-d H:i:s");
        
        $sn = $cache->incr($key);

        $cache->expire($key, 60);
        
        return $namespace.(strtotime("2038-01-19 03:14:07") - time()).sprintf("%0{$length}d", $sn);

    }

}
?>