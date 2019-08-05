<?php

class DispatchStream
{
    const WCS_MGR_URL    = 'http://dianhuan.mgr20.v1.wcsapi.com';
    const WCS_ACCESS_KEY = '4492bdf57c52dcb1e9e499070694fd2dee50c833';
    const WCS_SECRET_KEY = '4e88feb304553b4e25d27457b42a6a181af51a3c';
    const BUCKET         = "dianhuan-video";
    
    const CLIENT = array(
        'ws' => array(
            'parnter' => "ws",
            'url'     => "sdkoptedge.chinanetcenter.com",
            'prefix'  => "chinanetcenter"
        )
    );
    const SERVER = array(
        'http://59.110.145.201/upload/test'
    );
    
    public static function getDispatch($region, $uid, $clientIp)
    {
        $arrTemp = array();
        $domains = self::getDomain($region);
        if (empty($domains)) {
            return array();
        }
        
        if(in_array(strtok($clientIp, '.'), array('10', '127', '172', '192'))) {
            $clientIp = '220.194.45.226';
        }
        foreach (self::CLIENT as $key => $val) {
            $result = Util::curlHeader($val['url'], self::getHeaders(str_replace(array('rtmp://','/'), '', $domains), $clientIp));
            $result = explode("\n", trim($result));
            $arrTemp[$key]['prefix']   = $val['prefix'];
            $arrTemp[$key]['url']      = str_replace(array('rtmp://','/'), '', $domains);
            $arrTemp[$key]['clientIp'] = $result;
        }
        $arrTemp['server'] = self::SERVER;
        return $arrTemp; 
    }

    /**
     * 获取测速域名
     * 
     * @param string $rtmp            
     * @param string $partner            
     * @param int    $uid            
     */
    public static function getRtmp($rtmp, $partner, $uid)
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
        if(in_array(strtok($clientIp, '.'), array('10', '127', '172', '192'))) {
            return array();
        }
        
        $rtmpTemp = parse_url($rtmp);
        $arrTemp  = array();
        foreach($extend[$partner] as $item){
            $url = $rtmpTemp['scheme'] . '://' . $item['ip'] . $rtmpTemp['path'] . '?wsiphost=ipdb&wsHost=' . $rtmpTemp['host'];
            $arrTemp[$item['speed']] = self::getSecretUrl($url, $rtmp);
        }
        krsort($arrTemp);
        return array_values($arrTemp);
        
        /**
        $ip = '';
        $speed = $extend[$partner][0]['speed'];
        for ($i = 1; $i < count($extend[$partner]); $i ++) {
            if ($speed < $extend[$partner][$i]['speed']) {
                $speed = $extend[$partner][$i]['speed'];
                $ip    = $extend[$partner][$i]['url'];
            }
        }
        if ($ip != '') {
            $arrTemp = parse_url($rtmp);
            return $arrTemp['scheme'] . '://' . $ip . $arrTemp['path'] . '?wsiphost=ipdb&wsHost=' . $arrTemp['host'];
        }
        return $rtmp;
        */
    }

    public static function getSecretUrl($url, $rtmp = '')
    {
        $prefix = '';
        if($rtmp != '') {
            $rtmpTemp = parse_url($rtmp);
            $prefix = $rtmpTemp['scheme'] . '://' . $rtmpTemp['host'] . $rtmpTemp['path'];
        }else{
            $prefix = $url;
        }
        $wsABSTime = dechex(time() + 86400);
        $wsSecret  = md5($prefix . self::WCS_SECRET_KEY);
        $option = array(
            'wsABSTime' => $wsABSTime,
            'wsSecret' => $wsSecret
        );
        self::addRedis($wsSecret, $wsABSTime);
        
        if (strpos($url, '?') !== false) {
            $url .= '&' . http_build_query($option);
        } else {
            $url .= '?' . http_build_query($option);
        }
        return $url;
    }
    
    public static function addRedis($wsSecret,$wsABSTime)
    {
        //写authority_ws_string
        $cache  = Cache::getInstance("REDIS_CONF_CACHE");
        $key    = "authority_ws_string_".$wsSecret."_".$wsABSTime;
        $cache->SET($key, 1);
        $cache->expire($key, 60);
    }
    
    public static function getRedis($wsSecret,$wsABSTime)
    {
        //写authority_ws_string
        $cache  = Cache::getInstance("REDIS_CONF_CACHE");
        $key    = "authority_ws_string_".$wsSecret."_".$wsABSTime;
        return $cache->get($key);
    }
    
    
    /**
     * 是否测速
     */
    public static function isSpeed($uid,$lng,$lat,$partner)
    {
        $DAOLive = new DAOLive();
        if($DAOLive->isExist($uid)) {
            return true;
        }
        
        $DAOStreamPoint = new DAOStreamPoint();
        $info = $DAOStreamPoint->getInfoByUid($uid);
        if (empty($info)) {
            return true;
        }
        $extend = json_decode($info['extends'], true);
        if (empty($extend[$partner])) {
            return true;
        }
        
        //时间超过1小时
        $time = time();
        if($time - strtotime($info['addtime']) > 3600) {
            return true;
        }
        
        //ip地址有无变化
        $clientIp = Util::getIP();
        if($info['userip'] != $clientIp) {
            return true;
        }
        
        //距离大于100米
        $distance = DispatchStream::getDistance($info['lng'], $info['lat'], $lng, $lat);
        if ($distance > 0.1) {
            return true;
        }
        
        return false;
    }

    /**
     * 获取header参数
     *
     * @param string $domain            
     */
    public static function getHeaders($domain,$clientIp)
    {
        return array(
            'WS_URL:' . $domain.'/',
            'WS_RETIP_NUM:3',
            'WS_URL_TYPE:1',
            'UIP:'.$clientIp
        );
    }

    /**
     * 获取域名
     *
     * @param string $region            
     */
    public static function getDomain($region)
    {
        include_once 'stream_client/WSStream.php';
        switch ($region) {
        case WSStream::REGION_ABROAD:
            return WSStream::US_PUSH;
                break;
        default:
            return WSStream::CN_PUSH;
        }
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
}
