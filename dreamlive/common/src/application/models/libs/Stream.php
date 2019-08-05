<?php
class Stream
{
    const SALT = "Kd*&0BdQ@#9";

    const PARTNER_WS='ws';//网速s
    const PARTNER_CC='cc';//cc
    const PARTNER_TX='tx';//腾讯
    const PARTNER_AL='al';//阿里
    const PARTNER_XY='xy';//星域
    const PARTNER_JS='js';//金山

    const TYPE_RTMP='rtmp';
    const TYPE_FLV='flv';
    const TYPE_HLS='hls';
    const TYPE_REPLAY='replay';

    public function getStream($uid)
    {
        $partner = $this->getPartner($uid);
        
        $ENV = Context::getConfig("ENV");
        $flags = ($ENV == 'beta') ? $ENV : '';
        
        $prefix = strtoupper($partner . $flags . "_" . time() . "_" . $uid . "_" . rand(1000, 9999));
        $sn = $prefix . "." . substr(md5($prefix . self::SALT), 4, 4);
        
        return array($sn,$partner);
    }

    public function isValidSN($sn)
    {
        list ($prefix, $sign) = explode(".", $sn);
        
        list ($partner, $time, $uid, $rand) = explode("_", $prefix);
        
        $ENV = Context::getConfig("ENV");
        if ($ENV == 'beta' && strpos($partner, 'BETA') !== false) {
            $partner = str_replace("BETA", '', $partner);
        }
        
        if (! in_array(strtolower($partner), StreamDispatch::$partners)) {
            return false;
        }
        
        if (time() - $time > 7200) {
            return false;
        }
        
        return substr(md5($prefix . self::SALT), 4, 4) == $sign ? true : false;
    }

    private function getUrlByType($sn, $partner,$type,$region='china',$replay = false,$replayUrl='',$push=false )
    {
        $streamClass=strtoupper($partner).'Stream';
        //$streamFile='stream_client/'.$streamClass.'.php';
        $method='get'.strtoupper($type).'Url';
        //require_once $streamFile;
        if (!class_exists($streamClass)) { throw new Exception($streamClass.' partner not exists');
        }
        $args=[];
        switch ($type){
        case self::TYPE_RTMP:
            $args=[$sn,$replay];
            break;
        case self::TYPE_HLS:
        case self::TYPE_FLV:
            $args=[$sn,$region,$replay];
            break;
        case self::TYPE_REPLAY:
            $args=[$replayUrl,$region];
            break;
        default:
            throw new Exception("type is exception");
        }
        $stream=new $streamClass();
        $url=call_user_func_array(array($stream,$method), $args);
        //$url=$stream->$method($sn,$replay);
        if (!$url) { throw new Exception('stream url is null');
        }
        if ($type==self::TYPE_RTMP) {
            if (!$push) {
                return StreamDispatch::getSecretUrl($partner, $url);
            }else{
                return StreamDispatch::getSafeUrl($partner, $url);
            }
        }else{
            return StreamDispatch::getSafeUrl($partner, $url);
        }
    }

    public function getRTMPUrl($sn, $partner, $replay = false)
    {
        return $this->getUrlByType($sn, $partner, self::TYPE_RTMP, 'china', $replay);
    }

    public function getPushUrl($sn, $partner, $replay = false)
    {
        return $this->getUrlByType($sn, $partner, self::TYPE_RTMP, 'china', $replay, '', true);
    }

    public function getFLVUrl($sn, $partner,$region='china' ,$replay = false)
    {
        return $this->getUrlByType($sn, $partner, self::TYPE_FLV, $region, $replay);
    }
    public function getHLSUrl($sn, $partner,$region='china' ,$replay = false)
    {
        return $this->getUrlByType($sn, $partner, self::TYPE_HLS, $region, $replay);
    }
    public function getRepalyUrl( $partner,$region='china', $replayUrl = '')
    {
        return $this->getUrlByType('', $partner, self::TYPE_REPLAY, $region, false, $replayUrl);
    }
    public function getWebUrl($sn, $partner,$region='china' ,$replay = false)
    {
        $url=$this->getFLVUrl($sn, $partner, $region, $replay);
        $url=str_replace('http', 'rtmp', $url);
        return str_replace('.flv?', '?', $url);
    }

    /**
     * 获取$partner
     *
     * @param int $uid            
     */
    public function getPartner($uid)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key = "user_partner_string_" . $uid;
        
        //用户设置
        $user_set_key = "partner_probability_user_string_" . $uid;
        $partner = $cache->get($user_set_key);
        if ($partner && in_array($partner, StreamDispatch::$partners)) {
            return $partner;
        }
        
        $partner = $cache->get($key);
        if ($partner && in_array($partner, StreamDispatch::$partners)) {
            return $partner;
        }
        
        //百分比设置
        $probability_set_key = "partner_probability_string";
        $partner_probability = $cache->get($probability_set_key);
        $partner_probability = json_decode($partner_probability, true);
        $partner = $this->getProRand($partner_probability);
        if ($partner && in_array($partner, StreamDispatch::$partners)) {
            $cache->SET($key, $partner);
            $cache->expire($key, 60 * 60);
            return $partner;
        }
        
        return self::PARTNER_WS;
    }

    /**
     * 设置$partner
     *
     * @param int    $uid            
     * @param string $partner            
     */
    public function setPartner($uid, $partner)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key = "user_partner_string_" . $uid;
        $cache->SET($key, $partner);
        return $cache->expire($key, 86400 * 7);
    }

    /**
     * 获取概率
     *
     * @param array $arrTemp            
     */
    public function getProRand($option)
    {
        $partner = '';
        $proSum = array_sum(array_values($option));
        foreach ($option as $key => $pro) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $pro) {
                $partner = $key;
                break;
            } else {
                $proSum -= $pro;
            }
        }
        return $partner;
    }


    public function grabStream($cdn='ws')
    {
        //$cdn=array(self::PARTNER_WS=>true,self::PARTNER_CC=>false);
        $liveStat=new DAOLiveOnlineStatistics();
        $cls=strtoupper($cdn)."Stream";
        $c=new $cls();
        $re=$c->getStreamNum();
        foreach ($re as $j){
            //var_dump($j);
            $liveStat->add($j['uid'], $j['liveid'], $j['sn'], $j['partner'], $j['num']);
        }

        /*foreach ($cdn as $k=>$v){
            if ($v==false)continue;
            $cls=strtoupper($k)."Stream";
            $c=new $cls();
            $re=$c->getStreamNum();
            foreach ($re as $j){
                //var_dump($j);
                $liveStat->add($j['uid'],$j['liveid'] , $j['sn'],$j['partner'] ,$j['num'] );
            }
            //sleep(1);
        }*/
    }


    public static function getAllCdn()
    {
        return [Stream::PARTNER_JS,Stream::PARTNER_AL,Stream::PARTNER_CC,Stream::PARTNER_TX,Stream::PARTNER_WS,Stream::PARTNER_XY] ;
    }
}
?>
