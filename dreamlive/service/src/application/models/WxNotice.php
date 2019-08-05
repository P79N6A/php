<?php

class WxNotice
{

    const TEXT_TITLE = array(
        "劲爆热舞嗨翻天",
        "歌神附体中",
        "又被融化啦",
        "这大长腿绝了",
        "刷新你的想象力",
        "被鬼迷心窍了",
        "绝世美颜等你来撩",
        "直播上瘾看主播要醉",
    );

    /**
     * 订阅小程序开播通知
     *
     * @param  $uid
     * @param  $openid
     * @param  $formid
     * @return bool
     */
    public static function subNotice($uid,$openid, $formid)
    {
        /* {{{ */
        $dao_notice = new DAOWxNotice();
        $exists = $dao_notice->exists($uid);
        if($exists) {
            $dao_notice->modNotice($uid, $openid, $formid);
        }else{
            $dao_notice->addNotice($uid, $openid, $formid);
        }
        return true;
    }/* }}} */

    /**
     *
     * @param  $uid
     * @param  $liveid
     * @param  $nickname
     * @param  $medal
     * @return bool
     * @throws Exception
     */
    public static function sendLiveStart($uid,$liveid,$nickname,$medal)
    {
        /* }}} */
        $dao_notice = new DAOWxNotice();
        $notice = $dao_notice->getNoticeInfo($uid);
        if($notice) {

            $live = new Live();
            $live_info = $live->getLiveInfo($liveid);

            if(empty($live_info["title"])) {
                $text_title = self::TEXT_TITLE;
                $k = rand(0, 7);
                $title = $text_title[$k];
            }else{
                $title = $live_info["title"];
            }

            $data['openid'] = $notice['openid'];
            $data['formid'] = $notice['formid'];
            $data['liveid'] = $liveid;
            $data['keyword1'] = $title;
            $data['keyword2'] = $nickname.'的直播间';
            $data['keyword3'] = $medal;
            $result = WxMiniProgram::sendNotice($data);
            return true;
        }else{
            return false;
        }
    }/* }}} */


    public static function getAllNoticeUsers()
    {
        $dao_notice = new DAOWxNotice();
        return $dao_notice->getNoticeUsers();
    }

}
?>