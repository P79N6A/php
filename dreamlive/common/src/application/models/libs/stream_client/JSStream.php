<?php
class JSStream extends BaseStream
{

    const CN_PUSH       = "rtmp://cn06.push.dreamlive.cn";
    const CN_FLV        = "http://cn06.flv.dreamlive.cn";
    const CN_HLS        = "http://cn06.hls.dreamlive.cn";
    const CN_REPALY     = "http://cn06.replay.dreamlive.cn";
    
    const US_PUSH       = "rtmp://us06.push.dreamlive.cn";
    const US_FLV        = "http://us06.flv.dreamlive.cn";
    const US_HLS        = "http://us06.hls.dreamlive.cn";
    const US_REPALY     = "http://us06.replay.dreamlive.cn";


    public static $partner = 'js';


    /**
     * 获取测速节点
     *
     * @param string $region
     * @param string $clientIp
     */
    public function getSpeedNode($region, $clientIp)
    {
        $push=$region==BaseStream::REGION_ABROAD?self::US_PUSH:self::CN_PUSH;
        $domain=BaseStream::getDomainByUrl($push);
        $url='http://cwwshdns.ksyun.com/b?dn='.$domain.'&&ip='.$clientIp;
        $result=Util::curl($url);
        $re=@json_decode($result, true);
        if (!$re) { return [];
        }
        if ($re['code']!=0) { return[];
        }
        $ipList=$re['data']['ip_list'];
        $urls=[];
        foreach ($ipList as $i){
            //$url[] = 'http://' . trim($i) . '/chinanetcenter/testup.flv?wsHost=' . str_replace(array('rtmp://','/'), '', $domains);
        }
        return array('url'=>$url,'partner'=>Stream::PARTNER_JS);
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

}
?>
