<?php
class TXStream extends BaseStream
{
    const CN_PUSH       = "rtmp://cn04.push.dreamlive.cn";
    const CN_FLV        = "http://cn04.flv.dreamlive.cn";
    const CN_HLS        = "http://cn04.hls.dreamlive.cn";
    const CN_REPALY     = "http://cn04.replay.dreamlive.cn";
    
    const US_PUSH       = "rtmp://us04.push.dreamlive.cn";
    const US_FLV        = "http://us04.flv.dreamlive.cn";
    const US_HLS        = "http://us04.hls.dreamlive.cn";
    const US_REPALY     = "http://us04.replay.dreamlive.cn";

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
