<?php
class WSStream extends BaseStream
{
    const CN_PUSH       = "rtmp://cn01.push.dreamlive.com";
    const CN_FLV        = "http://cn01.flv.dreamlive.com";
    const CN_HLS        = "http://cn01.hls.dreamlive.com";
    const CN_REPALY     = "http://cn01.replay.dreamlive.com";

    const US_PUSH       = "rtmp://us01.push.dreamlive.com";
    const US_FLV        = "http://us01.flv.dreamlive.com";
    const US_HLS        = "http://us01.hls.dreamlive.com";
    const US_REPALY     = "http://us01.replay.dreamlive.com";

    const WCS_SECRET_KEY   = '4e88feb304553b4e25d27457b42a6a181af51a3c';
    const WCS_SPEED_URL    = 'sdkoptedge.chinanetcenter.com';
    const WCS_SPEED_PREFIX = 'chinanetcenter';
    public static $partner = 'ws';


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
        $arrTemp = array();
        foreach ($stream as $item) {
            $url = $rtmpTemp['scheme'] . '://' . $item['ip'] . $rtmpTemp['path'] . '?wsiphost=ipdb&wsHost=' . $rtmpTemp['host'];
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
            return array('url'=>['http://112.253.38.145/chinanetcenter/testup.flv?wsHost=cn01.push.dreamlive.com',],'partner'=>self::$partner);
        }
        $arrTemp = $url = array();
        $domains = self::getDomain($region);
        if (empty($domains)) {
            return array();
        }
        $result = Util::curlHeader(self::WCS_SPEED_URL, self::getHeaders(str_replace(array('rtmp://','/'), '', $domains), $clientIp));
        $result = explode("\n", trim($result));
        foreach($result as $item){
            $url[] = 'http://' . trim($item) . '/chinanetcenter/testup.flv?wsHost=' . str_replace(array('rtmp://','/'), '', $domains);
        }
        return array('url'=>$url,'partner'=>self::$partner);
    }

    /**
     * 获取header参数
     *
     * @param string $domain
     */
    public static function getHeaders($domain, $clientIp)
    {
        return array(
            'WS_URL:' . $domain . '/',
            'WS_RETIP_NUM:3',
            'WS_URL_TYPE:1',
            'UIP:' . $clientIp
        );
    }

    /**
     * 获取域名
     *
     * @param string $region
     */
    public static function getDomain($region)
    {
        switch ($region) {
        case WSStream::REGION_ABROAD:
            return WSStream::US_PUSH;
                break;
        default:
            return WSStream::CN_PUSH;
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

    public function stop($rtmp)
    {
        $url  = 'http://cm.chinanetcenter.com/CM/cm-command.do?';
        $time = time();
        $username = 'dianhuan';
        $password = "Dream123~!@#";
        $token    = md5($username.$password.$rtmp);
        $option = array(
            'username' => $username,
            'password' => $token,
            'cmd'      => 'channel_manager',
            'action'   => 'forbid',
            'channel'  => $rtmp,
            'type'     => 'publish',
            'reltime'  => $time,
            'rt'       => 1,
            'abstime_end' => $time,
        );
        $url .= http_build_query($option);
        $result = Util::curl($url);
        $re=json_decode($result, true);

        if ($re['code']=='00') { return true;
        }
        return false;
    }

    public function pushList()
    {
        $url  = 'http://qualiter.wscdns.com/api/playerCount.jsp?';
        $key  = '437243A64FC5B35';
        $user = "dianhuan";
        $r    = time().rand(10000, 99999);
        $k    = md5($r . $key);
        $u_arr=array(self::CN_FLV,self::CN_HLS);
        array_walk(
            $u_arr, function (&$item,$key) {
                $item=str_replace(array('rtmp://','http://'), '', $item);
            } 
        );
        $u    = implode(',', $u_arr);
        $option = array(
            'n' => $user,
            'r' => $r,
            'k' => $k,
            'u' => $u
        );
        $url .= http_build_query($option);
        $result = Util::curl($url);
        return json_decode($result, true);
    }

    private function parseSn($wsSn)
    {
        return str_replace(array('cn01.flv.dreamlive.com/live/','cn01.flv.dreamlive.com/replay/','cn01.hls.dreamlive.com/live/','cn01.hls.dreamlive.com/replay/'), '', $wsSn);
    }

    public function getStreamNum()
    {
        $result=[];
        $re=$this->pushList();
        if (!isset($re['dataValue'])) { return $result;
        }
        $daoLive=new DAOLive();
        foreach ($re['dataValue'] as $i){
            $sn=$this->parseSn($i['prog']);
            $live=$daoLive->getLiveInfoBySn($sn, Stream::PARTNER_WS);
            if ($live) {
                $result[]=array(
                    'uid'=>$live['uid'],
                    'liveid'=>$live['liveid'],
                    'sn'=>$live['sn'],
                    'partner'=>$live['partner'],
                    'num'=>$i['value'],
                );
            }
        }
        $t=[];
        foreach ($result as $i){
            if (!isset($t[$i['uid']])) {
                $t[$i['uid']]=$i;
            }else{
                $t[$i['uid']]['num']+=$i['num'];
            }
        }
        $result=array_values($t);
        return $result;
    }

}
?>
