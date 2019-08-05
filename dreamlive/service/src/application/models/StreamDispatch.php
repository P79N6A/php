<?php
class StreamDispatch
{
    public static $partners = array('ws','cc');
    public static $serverIp = array('url'=>array('http://59.110.145.201/upload/test'),'partner'=>'server');
    public static $platform = 'cdn';

    /**
     * 获取CDN厂商推流域名
     *
     * @param string $rtmp            
     * @param string $partner            
     * @param int    $uid            
     */
    public static function getStream($rtmp, $partner, $uid)
    {
        $DAOStreamPoint = new DAOStreamPoint();
        $info = $DAOStreamPoint->getInfoByUid($uid);
        if (empty($info)) {
            return array();
        }
        $extend = json_decode($info['extends'], true);
        if (empty($extend[$partner])) {
            return array();
        }
        $clientIp = Util::getIP();
        if (in_array(strtok($clientIp, '.'), array('10','127','172','192'))) {
            return array();
        }
        
        $class = strtoupper($partner)."Stream";
        $obj = new $class();
        $arrTemp = $obj->stream($extend[$partner], $rtmp, $partner);
        krsort($arrTemp);
        return array_values($arrTemp);
    }

    /**
     * 获取UrlSecret
     *
     * @param string $partner            
     * @param string $url            
     * @param string $rtmp            
     */
    public static function getSecretUrl($partner, $url, $rtmp = '')
    {
        return self::getSafeUrl($partner, $url, $rtmp, true);
    }

    public static function getSafeUrl($partner, $url, $rtmp = '',$updateRedis=false)
    {
        $prefix = '';
        if ($rtmp != '') {
            $rtmpTemp = parse_url($rtmp);
            $prefix   = $rtmpTemp['scheme'] . '://' . $rtmpTemp['host'] . $rtmpTemp['path'];
        } else {
            $prefix = $url;
        }

        $api_access_config = Context::getConfig("API_ACCESS_CONFIG");
        $rand     = rand(1000000, 9999999);
        $time     = time();
        $guid     = md5($rand . "_" . $time . $api_access_config["server"]["token"]);
        $ABSTime  = dechex(time() + 86400);
        $secret   = md5($prefix . self::getPartnerSecret($partner));
        if ($updateRedis) {
            self::addRedis($partner, $secret, $ABSTime);
        }

        $option = array(
            'ABSTime'  => $ABSTime,
            'secret'   => $secret,
            'rand'     => $rand,
            'time'     => $time,
            'guid'     => $guid,
            'platform' => self::$platform
        );
        if (strpos($url, '?') !== false) {
            $url .= '&' . http_build_query($option);
        } else {
            $url .= '?' . http_build_query($option);
        }
        return $url;
    }

    /**
     * 写redis
     *
     * @param string $secret            
     * @param string $ABSTime            
     */
    public static function addRedis($partner, $secret, $ABSTime)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key = "authority_string_" . $partner . "_" . $secret . "_" . $ABSTime;
        $cache->SET($key, 1);
        $cache->expire($key, 120);
    }

    /**
     * 读redis
     *
     * @param string $wsSecret            
     * @param string $wsABSTime            
     */
    public static function getRedis($partner, $secret, $ABSTime)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key = "authority_string_" . $partner . "_" . $secret . "_" . $ABSTime;
        return $cache->get($key);
    }

    /**
     * 是否测速
     *
     * @param  int $uid
     * @param  int $lng
     * @param  int $lat
     * @return boolean
     */
    public static function isSpeed($uid, $lng, $lat, $partner)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key = 'user_login_speed';
        if($cache->SISMEMBER($key, $uid)) {
            return true;
        }
        return false;
        
        
        $DAOStreamPoint = new DAOStreamPoint();
        $info = $DAOStreamPoint->getInfoByUid($uid);
        if (empty($info)) {
            return true;
        }
        $extend = json_decode($info['extends'], true);
        if (empty($extend[$partner])) {
            return true;
        }
        // 时间超过1小时
        $time = time();
        if ($time - strtotime($info['addtime']) > 3600) {
            return true;
        }
        // ip地址有无变化
        $clientIp = Util::getIP();
        if ($info['userip'] != $clientIp) {
            return true;
        }
        // 距离大于100米
        $distance = DispatchStream::getDistance($info['lng'], $info['lat'], $lng, $lat);
        if ($distance > 0.1) {
            return true;
        }
        return false;
    }
    
    /**
     * 获取测速数据
     *
     * @param string $region
     * @param int    $uid
     * @param string $clientIp
     */
    public static function getDispatch($region, $uid, $clientIp, $partner)
    {
        if (in_array(strtok($clientIp, '.'), array('10','127','172','192'))) {
            $clientIp = '220.194.45.226';
        }
        
        $arrTemp = array();
        $class = strtoupper($partner).'Stream';
        $obj = new $class();
        array_push($arrTemp, $obj->getSpeedNode($region, $clientIp));
        array_push($arrTemp, self::$serverIp);
        return $arrTemp;
        
        /**
        $arrTemp = array();
        foreach(self::$partners as $partner){
            $class = strtoupper($partner);
            $obj = new $class();
            $arrTemp[] = $obj->getSpeedNode($region, $clientIp);
        }
        return $arrTemp;
        */
    }

    public static function getDispatchStream($region, $rtmp, $partner, $clientIp)
    {
        if (in_array(strtok($clientIp, '.'), array('10','127','172','192'))) {
            $clientIp = '220.194.45.226';
        }
        
        $arrTemp = array();
        $class = strtoupper($partner) . 'Stream';
        $obj = new $class();
        $stream = $obj->getSpeedNode($region, $clientIp);
        foreach ($stream['url'] as $key => $val) {
            $rtmpTemp = parse_url($val);
            $arrTemp[] = array(
                'ip' => $rtmpTemp['host'],
                'speed' => $key
            );
        }
        return $obj->stream($arrTemp, $rtmp, $partner);
    }

    /**
     * 计算两点间距离
     *
     * @param  string $lat1            
     * @param  string $lng1            
     * @param  string $lat2            
     * @param  string $lng2            
     * @return number
     */
    public static function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        // 将角度转为狐度
        $radLat1 = deg2rad($lat1); // deg2rad()函数将角度转换为弧度
        $radLat2 = deg2rad($lat2);
        $radLng1 = deg2rad($lng1);
        $radLng2 = deg2rad($lng2);
        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137;
        return $s;
    }

    /**
     * 获取CDN厂商SECRET_KEY
     *
     * @param  string $partner
     * @return string
     */
    public static function getPartnerSecret($partner)
    {
        switch ($partner) {
        case "ws":
            $secret = WSStream::WCS_SECRET_KEY;
            break;
        case "cc":
            $secret = '';
            break;
        case "al":
            $secret = '';
            break;
        case "tx":
            $secret = '';
            break;
        }
        return $secret;
    }
}
