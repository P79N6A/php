<?php
class BaseStream
{
    const REGION_CHINA    = 'china';
    const REGION_ABROAD   = 'abroad';

    const PATH_LIVE='live';
    const PATH_REPLAY='replay';


    protected function getDomainByType($type,$region='china')
    {
        return "";
    }
    private function joinUrl($type,$sn,$replay=false,$replayUrl='',$region='china')
    {
        $domain=$this->getDomainByType($type, $region);
        if (strrpos($replayUrl, 'http://') !== false) {
            $domain = "";
        }
        $path=$replay==true?self::PATH_REPLAY:self::PATH_LIVE;
        $cls=get_class($this);
        $pre=strtolower(str_replace('Stream', '', $cls));
        switch ($type){
        case Stream::TYPE_RTMP:
                
            return $domain.'/'.$path.'/'.$sn;
        case Stream::TYPE_FLV:
            return $domain.'/'.$path.'/'.$sn.'.flv';
        case Stream::TYPE_HLS:
            if ($pre==Stream::PARTNER_CC) {
                return $domain.'/'.$path.'/'.$sn.'.m3u8';
            }
            return $domain.'/'.$path.'/'.$sn.'/playlist.m3u8';
        case Stream::TYPE_REPLAY:
            if (empty($replayUrl)) { throw new Exception("replayurl param is null");
            }
            if (empty($domain)) {
                return $replayUrl;
            }
            return $domain.'/'.$replayUrl;
        default:
            throw new Exception("unknow stream type");

        }
    }

    public function getRTMPUrl($sn, $replay = false)
    {
        $region = Context::get("region");
        return $this->joinUrl(Stream::TYPE_RTMP, $sn, $replay, '', $region);
    }

    public function getFLVUrl($sn,$region,$replay = false)
    {
        return $this->joinUrl(Stream::TYPE_FLV, $sn, $replay, '', $region);
    }


    public function getHLSUrl($sn, $region, $replay = false)
    {
        return $this->joinUrl(Stream::TYPE_HLS, $sn, $replay, '', $region);
    }

    public function getREPLAYUrl($replayurl, $region)
    {
        return $this->joinUrl(Stream::TYPE_REPLAY, '', false, $replayurl, $region);
    }

    public static function getDomainByUrl($url)
    {
        return trim(str_replace('rtmp://', '', $url));
    }
    
}
?>
