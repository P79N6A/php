<?php
class Util
{
    /**
     * 验证参数日期格式
     *
     * @param  string $date
     * @return boolean
     */
    public static function valid_date($date)
    {
        //匹配日期格式
        if (preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})\s([0-9]{2}):([0-9]{2}):([0-9]{2})$/", $date, $parts)) {
            $hour = $parts[4];
            $seconds = $parts[6];
            $minutes = $parts[5];
            
            $hour_stanrd = str_pad($hour, 2, '0');
            if ($hour < 10 && $hour != $hour_stanrd) {
                return false;
            }
            
            $seconds_stanrd = str_pad($seconds, 2, '0');
            if ($seconds < 10 && $seconds != $seconds_stanrd) {
                return false;
            }
            
            $minutes_stanrd = str_pad($minutes, 2, '0');
            if ($minutes < 10 && $minutes != $minutes_stanrd) {
                return false;
            }
            //检测是否为日期,checkdate为月日年
            if(checkdate($parts[2], $parts[3], $parts[1]) && ($parts[4] > 0 && $parts[4] < 24) && ($parts[5] > 0 && $parts[5] < 59) && ($parts[6] > 0 && $parts[6] < 59)) {
                return true;
            }else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    public static function isValidClient($userid, $deviceid, $platform, $network, $version, $rand, $netspeed, $time, $guid)
    {
        $params = array(
            "userid"=>$userid,
            "deviceid"=>$deviceid,
            "platform"=>$platform,
            "network"=>$network,
            "version"=>$version,
            "rand"=>$rand,
            "netspeed"=>$netspeed,
            "time"=>$time
        );

        ksort($params);

        $str = '';
        foreach ($params as $k => $v) {
            $str .= $k . '=' . rawurldecode(urlencode($v));
        }

        $api_access_config = Context::getConfig("API_ACCESS_CONFIG");

        return $guid == md5($str . $api_access_config["client"]["token"]) ? true : false;
    }

    public static function isValidServer($rand, $time, $guid)
    {
        $params = array(
            "rand"=>$rand,
            "time"=>$time
        );

        foreach ($params as $key => $value) {
            $params[$key] = rawurldecode(urlencode($value));
        }
        
        $api_access_config = Context::getConfig("API_ACCESS_CONFIG");
        if($guid != md5(implode("_", $params) . $api_access_config["server"]["token"])) {
            return false;
        }
         
        if(isset($api_access_config["server"]["hosts"])) {
            $allowed = false;
            foreach($api_access_config["server"]["hosts"] as $host) {
                if(strpos(Util::getIP(), $host) !== false) {
                    $allowed = true;
                    break;
                }
            }
            if(!$allowed) {
                return false;
            }
        }

        return true;
    }
    
    public static function isValidCdn($rand, $time, $guid)
    {
        $params = array(
            "rand"=>$rand,
            "time"=>$time
        );
    
        foreach ($params as $key => $value) {
            $params[$key] = rawurldecode(urlencode($value));
        }
    
        $api_access_config = Context::getConfig("API_ACCESS_CONFIG");
        if($guid != md5(implode("_", $params) . $api_access_config["server"]["token"])) {
            return false;
        }
    
        return true;
    }

    public static function checkFlood($guid)
    {
        $key = "flood_" . $guid;
        $cache = Cache::getInstance("REDIS_CONF_CACHE");

        if (false === $cache->get($key)) {
            $cache->set($key, 1, 7200);
            return true;
        }

        return false;
    }

    public static function checkTicket($ticket)
    {
        $key = "ticket_" . $ticket;
        $cache = Cache::getInstance("REDIS_CONF_USER");

        if (false === $cache->get($key)) {
            $cache->set($key, 1, 300);
            return true;
        }

        return false;
    }

    public static function getTime($cache = true)
    {
        static $time;

        if ($cache) {
            if (! $time) {
                $time = isset($_SERVER['REQUEST_TIME']) && ! empty($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time();
            }
        } else {
            $time = time();
        }

        return $time;
    }

    public static function getError($errno)
    {
        $error_list = Context::getConfig("ERROR_LIST");
        return isset($error_list[$errno]) ? $error_list[$errno] : "";
    }

    public static function getIP()
    {
        if ($_SERVER['HTTP_X_FORWARDED_FOR']) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (getenv("REMOTE_ADDR")) {
            $ip = getenv("REMOTE_ADDR");
        } elseif ($_SERVER['REMOTE_ADDR']) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } elseif ($_SERVER['HTTP_CLIENT_IP']) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (getenv("HTTP_X_FORWARDED_FOR")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } elseif (getenv("HTTP_CLIENT_IP")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } else {
            $ip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
        }

        return $ip;
    }

    public static function isMobile($mobile)
    {
        return 0 != preg_match("/(^1[3456789]{1}\d{9}$)|(^\+1[2-9]\d{2}[2-9](?!11)\d{6}$)/", $mobile);
    }
    
    /**
     * php扩展获取ip地址库
     *
     * @param  string $ip
     * @return string[]|mixed[]|string[]|mixed[]
     */
    public static function ip2Location($ip)
    {
        $data  = Ip2region::btreeSearchString($ip);
        
        if (!empty($data['region'])) {
            $data_array = explode('|', $data['region']);
            
            $province = $data_array[2];
            $city     = $data_array[3];
            $district = $data_array[4];
            $province     = str_replace('省', '', $province);
            $province     = str_replace('自治区', '', $province);
            $province     = str_replace('市', '', $province);
            $city         = str_replace('市', '', $city);
            $district     = str_replace('县', '', $district);
            if ((empty($province) && empty($city) && empty($district) )||($province == '0' && $city == '0' && $district == '0')) {
                return array('', '', '');
            }
        } else {
            $province     = '';
            $city         = '';
            $district     = '';
        }
        
        return array($province, $city, $district);
    }
    
    /**
     * 从淘宝获取城市信息
     *
     * @param  string $ip
     * @return string[]|mixed[]
     */
    public static function ip2region($ip)
    {
        $res_json         = @file_get_contents('http://ip.taobao.com/service/getIpInfo.php?ip=' . $ip);
        $local_array     = json_decode($res_json, true);
        if(!empty($local_array['data'])) {
            $province     = empty($local_array['data']['region']) ? '' : $local_array['data']['region'];
            $city         = empty($local_array['data']['city']) ? '' : $local_array['data']['city'];
            $district     = empty($local_array['data']['isp']) ? '' : $local_array['data']['isp'];
            $province     = str_replace('省', '', $province);
            $province     = str_replace('自治区', '', $province);
            $province     = str_replace('市', '', $province);
            $city         = str_replace('市', '', $city);
            $district     = str_replace('县', '', $district);
        } else {
            $province     = '';
            $city         = '';
            $district     = '';
        }
        
        return array($province, $city, $district);
    }
    
    /**
     * 获取服务端id
     */
    public static function get_server_ip()
    {
        if (isset($_SERVER)) {
            if ($_SERVER['SERVER_ADDR']) {
                $server_ip = $_SERVER['SERVER_ADDR'];
            } else {
                $server_ip = $_SERVER['LOCAL_ADDR'];
            }
        } else {
            $server_ip = getenv('SERVER_ADDR');
        }
        return $server_ip;
    }

    /**
     * header
     *
     * @param string $url
     * @param array  $headers
     */
    public static function curlHeader($url, $headers)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        $handles = curl_exec($ch);
        curl_close($ch);
        return $handles;
    }

    public static function ilog($msg,$key="common")
    {
        $dir="/tmp";
        $data=array();
        $data['time']=date("Y-m-d H:i:s");
        $data['key']=$key;
        if (is_string($msg)) {
            $data['msg']=$msg;
        }elseif (is_array($msg)||is_object($msg)) {
            $data['msg']=$msg;
        }else{
            $data['msg']=$msg;
        }

        $f=$dir."/ilog.log.".date("Ymd");
        if (!file_exists($f)) {
            touch($f);
        }
        $c=json_encode($data);
        file_put_contents($f, $c."\n\n", FILE_APPEND);
    }

    public static function curl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch), 0);
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                throw new Exception($result, $httpStatusCode);
            }
        }

        curl_close($ch);
        return $result;
    }

    public static function staticDomain()
    {
        global $STATIC_DOMAIN;
        return $STATIC_DOMAIN;
    }


    public static function joinStaticDomain($field)
    {
        $discard='dreamlive.tv';
        if (empty($field)) { return '';
        }
        if (stripos($field, 'http://static.'.$discard) === 0||stripos($field, 'https://static.'.$discard) === 0) {
            return str_replace($discard, MAIN_DOMAIN_NAME, $field);
        }else{
            if (preg_match('|^http(s?)://(\w+)\.'.preg_quote(MAIN_DOMAIN_NAME).'/(.*)|', $field)) {
                return $field;
            }else{
                if (stripos($field, '/')===0) {
                    return self::staticDomain().$field;
                }else{
                    return self::staticDomain()."/".$field;
                }

            }
        }
    }

    public static function splitStaticDomain($url)
    {
        if (empty($url)) { return '';
        }
        return preg_replace('/^https?:\/\/\w+\.(dreamlive\.com|dreamlive.cn)\//', '', $url);
    }

    public static function getMillisecond()
    {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($t1)+floatval($t2))*1000);
    }

    public static function myCurl($url,$header=array(),$content=array(),$isPost=false)
    {
        if (!empty($header)) {
            $h=null;
            foreach ($header as $k=>$v){
                $h[]=$k.':'.$v;
            }
        }
        $ch = curl_init();
        if(substr($url, 0, 5)=='https') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if (!$isPost) {
            $q=stripos($url, '?')!==false?'':'?';
            $url.=$q.http_build_query($content);
        }
        curl_setopt($ch, CURLOPT_URL, $url);

        if ($h) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $h);
        }

        if ($isPost) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($content));
        }

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch), 0);
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                throw new Exception($response, $httpStatusCode);
            }
        }

        curl_close($ch);
        return $response;
    }

    /*public static function elog($msg,$m='common'){
        if (!$msg)return;
        $url='http://10.10.10.203:8080/e.php';
        $msg=is_array($msg)||is_object($msg)?json_encode($msg):$msg;
        $args=array(
            'm'=>$m,
            'msg'=>$msg,
        );
        $url.='?'.http_build_query($args);
        self::curl($url);
    }*/
    
    static public function unicode_encode($name)
    {
        $name = iconv('UTF-8', 'UCS-2', $name);
        $len = strlen($name);
        $str = '';
        for ($i = 0; $i < $len - 1; $i = $i + 2)
        {
            $c = $name[$i];
            $c2 = $name[$i + 1];
            if (ord($c) > 0) {    // 两个字节的文字
                $str .= '\u'.base_convert(ord($c), 10, 16).base_convert(ord($c2), 10, 16);
            }
            else
            {
                $str .= $c2;
            }
        }
        return $str;
    }
    
    static public function unicode_decode($name)
    {
        // 转换编码，将Unicode编码转换成可以浏览的utf-8编码
        $pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
        preg_match_all($pattern, $name, $matches);
        if (!empty($matches)) {
            $name = '';
            for ($j = 0; $j < count($matches[0]); $j++)
            {
                $str = $matches[0][$j];
                if (strpos($str, '\\u') === 0) {
                    $code = base_convert(substr($str, 2, 2), 16, 10);
                    $code2 = base_convert(substr($str, 4), 16, 10);
                    $c = chr($code).chr($code2);
                    $c = iconv('UCS-2', 'UTF-8', $c);
                    $name .= $c;
                }
                else
                {
                    $name .= $str;
                }
            }
        }
        return $name;
    }

    public static function sendWarnToDingDing($msg)
    {
        //发送机器人警告
        $url    = 'https://oapi.dingtalk.com/robot/send?access_token=37ccf087279cd9f53af3e2578494f467cfc8910035ce3ac9c190f3b6450a88f3';
        $post_data = array(
            'msgtype'   => 'text',
            'text'      => array(
                'content'   => $msg
            )
        );
        $post_string=json_encode($post_data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array ('Content-Type: application/json;charset=utf-8'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
    }
}
?>
