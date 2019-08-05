<?php
class AdminChangeController extends BaseController
{
    public function changeSNAction()
    {
        $liveid     = $this->getParam("liveid")   ? intval($this->getParam("liveid"))             : 0;
        $sn         = $this->getParam("sn")       ? strip_tags(trim($this->getParam("sn")))       : "";
        $partner    = $this->getParam("partners") ? strip_tags(trim($this->getParam("partners"))) : "";
    
        Interceptor::ensureNotEmpty($liveid, ERROR_BIZ_LIVE_SN_NOT_EMPTY, "liveid");
        Interceptor::ensureNotEmpty($sn, ERROR_BIZ_LIVE_SN_NOT_EMPTY, "sn");
        Interceptor::ensureNotEmpty($partner, ERROR_BIZ_LIVE_SN_NOT_EMPTY, "partner");
        Interceptor::ensureNotFalse(in_array($partner, Stream::getAllCdn()), ERROR_BIZ_LIVE_SN_NOT_EMPTY, "partner");
    
    
        //设置sn
        $live = new Live();
        $live->setPartnerSn($liveid, $sn, $partner);
        $liveInfo = $live->getLiveInfo($liveid);
    
        //发送消息
        $stream = new Stream();
        $replay = ($liveInfo['record'] == 'Y') ? true : false;
        $flv    = $stream->getFLVUrl($sn, $partner, $liveInfo['region'], $replay);
        $hls    = $stream->getHLSUrl($sn, $partner, $liveInfo['region'], $replay);
        $data = array(
            "sn" => $sn,
            "partner" => $partner,
            "flv" => $flv,
            "hls" => $hls
        );
    
        file_put_contents('/tmp/change_sn_'.date('Y-m').'.log', 'liveid='.$liveid.'   uid='.$liveInfo['uid'].'   data='.json_encode($data)."\n", FILE_APPEND);
        Messenger::sendToGroup($liveid, Messenger::MESSAGE_TYPE_CHANGE_SN, $liveInfo['uid'], '切换流地址', $data);
        $this->render();
    }
}
