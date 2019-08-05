<?php
class AuthorityController
{
    function streamwsAction()
    {
        Consume::start();
        $wsABSTime  = $_REQUEST["wsABSTime"] ? trim($_REQUEST["wsABSTime"])   : '';
        $wsSecret   = $_REQUEST["wsSecret"]  ? trim($_REQUEST["wsSecret"])    : '';
        //$url        = $_REQUEST["url"]       ? trim($_REQUEST["url"])       : '';
        $sn         = $_REQUEST["sn"]        ? trim($_REQUEST["sn"])          : '';
        $point      = $_REQUEST["point"]     ? trim($_REQUEST["point"])       : '';
        $domain     = $_REQUEST["domain"]    ? trim($_REQUEST["domain"])      : '';
        file_put_contents('/tmp/streamws.log', 'sn='.$sn.'  wsABSTime='.$wsABSTime.'  wsSecret='.$wsSecret.'  point='.$point.'    domain='.$domain."\n", FILE_APPEND);

        $url = 'rtmp://'.$domain.'/'.$point.'/'.$sn;
        Interceptor::ensureNotFalse((hexdec($wsABSTime)>time()), AUTHORITY_WS_TIME_ERROR, "wsABSTime");
        Interceptor::ensureNotFalse(($wsSecret == md5($url . DispatchStream::WCS_SECRET_KEY)), AUTHORITY_WS_SECRET_ERROR, "wsSecret");

        //$sn = str_replace(array('rtmp://cn01.push.dreamlive.tv/live/','rtmp://cn01.push.dreamlive.tv/replay/','http://cn01.flv.dreamlive.tv/live/','http://cn01.flv.dreamlive.tv/replay/','http://cn01.hls.dreamlive.tv/live/','http://cn01.hls.dreamlive.tv/replay/'),'',$url);

        $live  = new DAOLive();
        $isLive = $live->isLiveAuthorityStatus($sn, 'ws');
        $authority = DispatchStream::getRedis($wsSecret, $wsABSTime);
        file_put_contents('/tmp/streamws.log', 'isLive='.print_r($isLive, true).'  authority='.print_r($authority, true)."\n", FILE_APPEND);
        Interceptor::ensureNotFalse(($isLive || $authority), AUTHORITY_WS_LIVE_ERROR, "fail");

        $result = array(
            "errno" => OK,
            "errmsg" => Util::getError(OK),
            "consume" => Consume::getTime(),
            "time" => Util::getTime(false)
        );
        echo json_encode($result);
    }
}
