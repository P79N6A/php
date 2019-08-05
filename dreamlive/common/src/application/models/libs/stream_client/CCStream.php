<?php
class CCStream extends BaseStream
{

    const CN_PUSH       = "rtmp://cn02.push.dreamlive.cn";
    const CN_FLV        = "http://cn02.flv.dreamlive.cn";
    const CN_HLS        = "http://cn02.hls.dreamlive.cn";
    const CN_REPALY     = "http://cn02.replay.dreamlive.cn";
    
    const US_PUSH       = "rtmp://us02.push.dreamlive.cn";
    const US_FLV        = "http://us02.flv.dreamlive.cn";
    const US_HLS        = "http://us02.hls.dreamlive.cn";
    const US_REPALY     = "http://us02.replay.dreamlive.cn";

    const OP_ALLOW='allow';
    const OP_DENY='deny';

    const CC_SECRET_KEY   = '4e88feb304553b4e25d27457b42a6a181af51a3c';
    const CC_SPEED_URL    = 'http://119.29.29.29/d?';
    const CC_SPEED_PREFIX = 'dreamlive/upload_test';
    public static $partner = 'cc';

    /**
     * 获取测速推流地址
     *
     * @param array  $stream
     * @param string $rtmp
     * @param string $partner
     */
    public function stream($stream, $rtmp, $partner)
    {
        $rtmpTemp = parse_url($rtmp);
        $pathTemp = explode('/', $rtmpTemp['path']);
        $point    = $pathTemp[1];
        $sn       = $pathTemp[2];

        $arrTemp = array();
        foreach ($stream as $item) {
            $url = $rtmpTemp['scheme'] . '://' . $item['ip'] . '/' . $point . '?vhost=' . $rtmpTemp['host'] . '/' . $sn;
            $arrTemp[$item['speed']] = StreamDispatch::getSecretUrl(self::$partner, $url, $rtmp);
        }
        return $arrTemp;
    }

    /**
     * 获取测速节点
     *
     * @param string $region
     * @param string $clientIp
     */
    public function getSpeedNode($region, $clientIp)
    {
        $env=Context::getConfig('ENV');
        if ($env=='beta') {
            return array('url'=>['http://58.222.48.16:8089/dreamlive/upload_test/testup.flv',],'partner'=>self::$partner);
        }
        $arrTemp = $url = array();
        $domains = trim(str_replace('rtmp://', '', self::getDomain($region)));
        if (empty($domains)) {
            return array();
        }

        $option = array(
            'dn' => $domains,
            'ip' => trim($clientIp)
        );
        $speedUrl = self::CC_SPEED_URL . http_build_query($option);
        $result = Util::curl($speedUrl);
        $result = explode(";", trim($result));
        foreach ($result as $item) {
            $url[] = 'http://' . trim($item) . ':8089/dreamlive/upload_test/testup.flv';
        }
        return array('url' => $url,'partner' => self::$partner);
    }

    /**
     * 获取域名
     *
     * @param string $region
     */
    public static function getDomain($region)
    {
        switch ($region) {
        case CCStream::REGION_ABROAD:
            return CCStream::US_PUSH;
                break;
        default:
            return CCStream::CN_PUSH;
        }
    }

    protected function getDomainByType($type,$region='china')
    {
        switch ($type){
        case Stream::TYPE_RTMP:
            return $region==parent::REGION_ABROAD?self::US_PUSH:self::CN_PUSH;
        case Stream::TYPE_FLV:
            return $region==parent::REGION_ABROAD?self::US_FLV:self::CN_FLV;
        case Stream::TYPE_HLS:
            return $region==parent::REGION_ABROAD?self::US_HLS:self::CN_HLS;
        case Stream::TYPE_REPLAY:
            return $region==parent::REGION_ABROAD?self::US_REPALY:self::CN_REPALY;
        default:
            return "";
        }
    }

    public static function getConfig()
    {
        return array(
            'access_id'=>'b6902630f7694831971c625dc920993f',
            'access_key'=>'a12cc02282c54624a1a0628aaefa53ab',
            'stop_url'=>'http://api.bokecs.com/liveService/v2/{domain}/{appname}/{streamname}/{op}/{denyts}/truncate',
            'push_list_url'=>'http://api.bokecs.com/liveService/v2/{domain}/streams',
            'pull_list_url'=>'http://api.bokecs.com/liveService/v2/{domain}/{appname}/{streamname}/{page}/{rows}/members',
            'speed_url'=>'http://119.29.29.29/d',
            'speed_key'=>'4e88feb304553b4e25d27457b42a6a181af51a3c',
            'speed_path'=>'dreamlive/upload_test',
            'speed_file'=>'testup.flv',
            'speed_port'=>8089,
        );
    }
    public static function commonHeader()
    {
        $config=self::getConfig();
        $timestamp=Util::getMillisecond();
        $access_sign=md5($config['access_id']."|".$config['access_key'].'|'.$timestamp);
        return array(
            'access_id'=>$config['access_id'],
            'access_key'=>$config['access_key'],
            'timestamp'=>$timestamp,
            'access_sign'=>$access_sign,
        );
    }

    //$sn 包括后面的查询字符串
    public function stop($sn)
    {
        $config=self::getConfig();
        $url=$config['stop_url'];
        $domain=$this->getPushDomain();
        $params=array(
            'domain'=>$domain,
            'appname'=>self::PATH_LIVE,
            'streamname'=>$sn,
            'op'=>self::OP_DENY,
            'denyTs'=>-1,
        );
        return $this->request($url, $params);
    }

    public function getAllPush()
    {
        $config=self::getConfig();
        $url=$config['push_list_url'];
        $domain=$this->getPushDomain();
        $params=array(
            'domain'=>$domain,
        );
        return $this->request($url, $params);
    }

    //$sn 包括后面的查询字符串
    public function getAllPull($sn='',$page=1,$rows=10000)
    {
        $config=self::getConfig();
        $url=$config['pull_list_url'];
        $domain=$this->getPushDomain();
        $params=array(
            'domain'=>$domain,
            'appname'=>'ALL',
            'streamname'=>!empty($sn)?$sn:"ALL",
            'page'=>$page,
            'rows'=>$rows,
        );
        return $this->request($url, $params);
    }

    private function request($url,$params)
    {
        foreach ($params as $k=>$v){
            $url=str_replace('{'.$k.'}', $v, $url);
        }
        $result=Util::myCurl($url, self::commonHeader());
        $result=@json_decode($result, true);
        if (!$result||$result['code']!=1) {
            throw new Exception('stop_cc: '.$url.$result['message']);
        }
        return $result;
    }

    public function getPushDomain($region='china')
    {
        return str_replace(array('rtmp://','http://'), '', $region==BaseStream::REGION_ABROAD?self::US_PUSH:self::CN_PUSH);
    }

    public function getStreamNum()
    {
        $result=[];
        /*$re=$this->getAllPush();
        if (!isset($re['urls']))return $result;
        $daoLive=new DAOLive();
        foreach ($re['urls'] as $i){
            $live=$daoLive->getLiveInfoBySn($i['stream'],Stream::PARTNER_CC );
            if ($live){
                $t=array(
                    'uid'=>$live['uid'],
                    'liveid'=>$live['liveid'],
                    'sn'=>$live['sn'],
                    'partner'=>$live['partner'],
                    'num'=>0,
                );
                $pull=$this->getAllPull($i['stream']);
                if (isset($pull['members'][0]['streamName'])){
                    $t['num']=$pull['members'][0]['number'];
                }
                $result[]=$t;
                //usleep(10);

            }
        }*/
        $daoLive=new DAOLive();
        $pull=$this->getAllPull();
        foreach ($pull['members'] as $i){
            $live=$daoLive->getLiveInfoBySn($i['streamName'], Stream::PARTNER_CC);
            if ($live) {
                $result[]=array(
                    'uid'=>$live['uid'],
                    'liveid'=>$live['liveid'],
                    'sn'=>$live['sn'],
                    'partner'=>$live['partner'],
                    'num'=>$i['number'],
                );
            }
        }
        return $result;
    }
    /*   public function getSpeedNode($clientIp,$region='china'){
        return array('url'=>['http://58.222.48.16:8089/dreamlive/upload_test/testup.flv',],'partner'=>Stream::PARTNER_CC);
        $config=self::getConfig();
        $arrTemp = $url = array();
        $domains = self::getDomainByUrl(self::CN_PUSH);
        if (empty($domains)) {
            return array();
        }

        $option = array(
            'dn' => $domains,
            'ip' => trim($clientIp)
        );
        $speedUrl = $config['speed_url'] .'?'. http_build_query($option);
        $result = Util::curl($speedUrl);
        if (!$result)return $url;
        $result = explode(";", trim($result));
        foreach ($result as $item) {
            $url[] = 'http://' . trim($item) . ':'.$config['speed_port'].'/'.$config['speed_path'].'/'.$config['speed_file'];
        }
        return array('url' => $url,'partner' => Stream::PARTNER_CC);
    }*/
}
?>
