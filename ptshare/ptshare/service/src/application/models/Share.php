<?php
class Share
{
    const SHARE_TYPE_INVITE_NEW_USERS      = "invite_new_user";            //邀请新用户
    const SHARE_TYPE_GROUP_REDPACKET       = "everyday_group_redpacket";   //每日群红包
    const SHARE_TYPE_SHARE_FRIENDS         = "share_friends";              //分享给好友
    const SHARE_TYPE_SHARE_FOLLOWECHATSUB  = "follow_wechat_sub";          //关注公众号
    const SHARE_TYPE_SHARE_GOODS           = "first_share_goods";          //首次分享闲置
    const SHARE_TYPE_PERFECT_INFORMATION   = "perfect_information";        //完善资料


    public static function getShareUrl($uid, $taskid, $type)
    {
        switch ($type) {
            case self::SHARE_TYPE_INVITE_NEW_USERS:
                $action      = "";
                $callbackUrl = "/share/inviteNewUserSuccess";
                $param       = array('uid'=>$uid,'taskid'=>$taskid,'type'=>$type);
                break;
            case self::SHARE_TYPE_GROUP_REDPACKET:
                $redid       = RedPacket::createShareRedPacket($uid);
                $action      = "/redPacket/receiveGroup";
                $callbackUrl = "/redPacket/groupRedPacketSuccess";
                $param         = array('redid' => $redid,'uid'=>$uid,'taskid'=>$taskid,'type'=>$type);
                break;
            case self::SHARE_TYPE_SHARE_FRIENDS:
                $action      = "";
                $callbackUrl = "/share/inviteNewUserSuccess";
                $param       = array('uid'=>$uid,'taskid'=>$taskid,'type'=>$type);
                break;
            case self::SHARE_TYPE_SHARE_FOLLOWECHATSUB:
                $action      = "";
                $callbackUrl = "";
                $param       = array('uid'=>$uid,'taskid'=>$taskid,'type'=>$type);
                break;
            case self::SHARE_TYPE_SHARE_GOODS:
                $action      = "";
                $callbackUrl = "";
                $param       = array('uid'=>$uid,'taskid'=>$taskid,'type'=>$type);
                break;
            case self::SHARE_TYPE_PERFECT_INFORMATION:
                $action      = "";
                $callbackUrl = "";
                $param       = array('uid'=>$uid,'taskid'=>$taskid,'type'=>$type);
                break;
        }
        return array('action'=>$action,'callbackUrl'=>$callbackUrl,'param'=>$param);
    }
}
?>