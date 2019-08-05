<?php
class LinkController extends BaseController
{
    /**
     * 在线主播列表
     */
    public function getOnlineAnchorAction()
    {
        $offset  = $this->getParam("offset")  ? (int)($this->getParam("offset")) : 0;
        $num     = $this->getParam("num")     ? intval($this->getParam("num"))   : 20;
        $userid  = Context::get("userid");
        
        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "userid");
        
        $result = Link::getFollowingsOnlineAnchor($userid, $offset, $num);
        
        $this->render($result);
    }
    
    /**
     * 连麦申请
     */
    public function applyAction()
    {
        $uid      = $this->getParam("uid")    ? intval(trim($this->getParam("uid")))    : 0;
        $userid   = intval(Context::get("userid"));
        
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "uid");
        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "userid");
        
        Interceptor::ensureNotFalse(Link::isUserPermissions($userid), ERROR_LINK_USER_PERMISSIONS, "userid"); // 用户权限
        Interceptor::ensureNotFalse(Link::isAnchorPermissions($uid), ERROR_LINK_ANCHOR_PERMISSIONS, "uid"); // 主播权限
        Interceptor::ensureNotFalse(($liveid = Live::isUserLive($uid)), ERROR_BIZ_LIVE_NOT_ACTIVE, "liveid"); // 用户申请,主播必须在直播状态
        Interceptor::ensureFalse(Link::isRepeatApply($userid, $uid, $liveid), ERROR_LINK_REPEAT_APPLY, "reapplication"); // 重复申请
        Interceptor::ensureFalse((Link::getApplyTotal($uid, $liveid) >= Link::LINK_APPLY_TIMES_LIMIT), ERROR_LINK_APPLY_TIMES_LIMIT, "liveid");//申请次数限制
        
        // 1,写库
        $result = Link::apply($userid, $uid, $liveid);
        
        // 2,发直播间消息通知主播
        if($result) {
            Messenger::sendUserLinkApply($uid, $userid, $liveid);
        }
        
        $userInfo = User::getUserInfo($uid);
        $this->render(array('user'=>$userInfo));
    }
    
    /**
     * 用户取消连麦
     */
    public function cancelAction()
    {
        $uid      = $this->getParam("uid")    ? intval(trim($this->getParam("uid")))    : 0;
        $userid   = intval(Context::get("userid"));
        $liveid   = Live::isUserLive($uid);
        
        Interceptor::ensureNotFalse($liveid, ERROR_PARAM_NOT_SMALL_ZERO, "liveid");
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "uid");

        // 1,写库
        $result = Link::cancel($userid, $uid, $liveid);
        
        // 2,发消息通知
        Messenger::sendUserLinkCancel($uid, $userid, $liveid);
        
        $this->render();
        
    }
    
    /**
     * 连麦申请列表
     */
    public function getApplyListAction()
    {
        $uid      = $this->getParam("uid")       ? intval($this->getParam("uid"))           : 0;
        $liveid   = $this->getParam("liveid")    ? intval($this->getParam("liveid"))        : 0;
        $num      = $this->getParam("num")       ? strip_tags(trim($this->getParam("num"))) : 10;
        $offset   = $this->getParam("offset")    ? (int)($this->getParam("offset"))         : 0;
    
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "uid");
        Interceptor::ensureNotFalse($liveid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "liveid");
    
        $result = Link::getApplyList($uid, $liveid, $num, $offset);
    
        $this->render($result);
    }
    
    /**
     * 主播拒绝用户连麦
     */
    public function refuseAction()
    {
        $liveid  = $this->getParam("liveid") ? intval(trim($this->getParam("liveid"))) : 0;
        $uid     = $this->getParam("uid")    ? intval(trim($this->getParam("uid")))    : 0;
        $userid  = intval(Context::get("userid"));
    
        Interceptor::ensureNotFalse($liveid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "liveid");
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "uid");
    
        // 1,写库
        $result = Link::refuse($userid, $uid, $liveid);
    
        // 2,发消息通知用户
        Messenger::sendUserLinkRefuse($uid, $userid, $liveid);
    
        $this->render();
    }
    
    /**
     * 主播接受用户连麦
     */
    public function acceptAction()
    {
        $uid        = $this->getParam("uid")        ? intval(trim($this->getParam("uid")))        : 0;
        $linktype   = $this->getParam("linktype")   ? intval(trim($this->getParam("linktype")))   : Link::LINK_TYPE_ANCHOR_USER;
        $streamtype = $this->getParam("streamtype") ? intval(trim($this->getParam("streamtype"))) : 1;
        $userid     = intval(Context::get("userid"));
        $liveid     = Live::isUserLive($userid);
        
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "uid");
        Interceptor::ensureNotFalse($liveid > 0, ERROR_BIZ_LIVE_NOT_ACTIVE, "liveid");
        
        Interceptor::ensureNotFalse(Link::isAnchorPermissions($userid), ERROR_LINK_ANCHOR_PERMISSIONS, "uid");//主播权限
        $linktype = (Live::isUserLive($uid)) ? Link::LINK_TYPE_ANCHOR_ANCHOR : Link::LINK_TYPE_ANCHOR_USER;
        if($linktype == Link::LINK_TYPE_ANCHOR_USER) {
            if($liveid != Live::getLiveRoomByUser($uid)) {
                include_once 'message_client/RongCloudClient.php';
                $rongCloudClient = new RongCloudClient();
                $result = $rongCloudClient->queryChatroomUserExist($liveid, $uid);
                Interceptor::ensureNotFalse($result['isInChrm'], ERROR_LINK_APPLY_NOT_IN_LIVEROOM, "uid");// 用户连麦，判断是否在主播的直播间
            }
        }
        
        // 1,写库
        $linkid = Link::accept($userid, $uid, $liveid, $linktype, $streamtype);
            
        // 2,发消息通知用户接入连麦
        $liveid = ($linktype == Link::LINK_TYPE_ANCHOR_ANCHOR) ? Live::isUserLive($uid) : Live::isUserLive($userid);
        Messenger::sendUserLinkAccept($uid, $userid, $liveid, $linkid);
        //Messenger::sendUserLinkAcceptToLive($userid, $liveid, $linkid);
        
        $this->render(array('linkid'=>$linkid));
    }
    
    /**
     * 用户连麦完成
     */
    public function successAction()
    {
        $liveid  = $this->getParam("liveid") ? intval(trim($this->getParam("liveid"))) : 0;
        $uid     = $this->getParam("uid")    ? intval(trim($this->getParam("uid")))    : 0;
        $linkid  = $this->getParam("linkid") ? intval(trim($this->getParam("linkid"))) : 0;
        $userid  = intval(Context::get("userid"));
        
        Interceptor::ensureNotFalse($liveid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "liveid");
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "uid");
        Interceptor::ensureNotFalse($linkid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "linkid");
        
        $result = Link::connected($userid, $uid, $liveid, $linkid);
        
        $this->render();
    }
    
    /**
     * 连麦挂断
     */
    public function disconnectedAction()
    {
        //$liveid  = $this->getParam("liveid") ? intval(trim($this->getParam("liveid")))   : 0;
        $uid     = $this->getParam("uid")    ? intval(trim($this->getParam("uid")))      : 0;
        $linkid  = $this->getParam("linkid") ? intval(trim($this->getParam("linkid")))   : 0;
        $type    = $this->getParam("type")   ? strip_tags(trim($this->getParam("type"))) : 'anchor';
        $userid  = intval(Context::get("userid"));
        if($type == 'anchor') {
            $id = $userid;
        }else{
            $id = $uid;
        }
        $liveid  = Live::isUserLive($id);
        
        Interceptor::ensureNotFalse($liveid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "liveid");
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "uid");
        Interceptor::ensureNotFalse($linkid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "linkid");
        
        // 1,写库
        $result = Link::disconnected($userid, $uid, $liveid, $linkid, $type);
        $result = Link::disconnected($uid, $userid, $liveid, $linkid, $type);
        
        // 2,发消息通知
        Messenger::sendUserDisconnected($userid, $liveid, $type);
        
        $this->render();
    }
    
    /**
     * 连麦历史记录
     */
    public function getLinkListAction()
    {
        $uid      = $this->getParam("uid")     ? intval($this->getParam("uid"))           : 0;
        $num      = $this->getParam("num")     ? strip_tags(trim($this->getParam("num"))) : 10;
        $offset   = $this->getParam("offset")  ? (int)($this->getParam("offset"))         : 0;
        
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "uid");
        
        $result = Link::getLinkList($uid, $num, $offset);
        
        $this->render($result);
    }

    /**
     * 主播连麦许可
     */
    public function permitAction()
    {
        $liveid = $this->getParam("liveid") ? intval(trim($this->getParam("liveid"))) : 0;
        $permit = $this->getParam("permit") ? strip_tags(trim($this->getParam("permit"))) : '';
        $userid = intval(Context::get("userid"));
        
        Interceptor::ensureNotFalse($liveid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "liveid");
        Interceptor::ensureNotFalse(in_array($permit, array('open','close')), ERROR_PARAM_DATA_NOT_EXIST, $permit);
        
        // 1,写数据存储
        $result = Link::setUserPermit($userid, $permit);
        
        // 2,发消息通知
        Messenger::sendUserLinkPermit($userid, $liveid, $permit);
        
        $this->render();
    }
    
    /**
     * 新增连麦权限用户
     */
    public function setLinkRightsAction()
    {
        $uid = $this->getParam('uid') ? trim($this->getParam('uid')) : '';
        $type = $this->getParam('type') ? trim($this->getParam('type')) : '';
        $key = Link::_getKey();
        $cache = Cache::getInstance('REDIS_CONF_CACHE');
        Interceptor::ensureNotFalse($uid> 0, ERROR_PARAM_NOT_SMALL_ZERO, "uid");
        Interceptor::ensureNotFalse($type> 0, ERROR_PARAM_NOT_SMALL_ZERO, "type");
        
        //新增连麦权限用户
        if (($ret = $cache->zAdd($key, $type, $uid)) !== false) {
            $data = array('error' => 0, 'msg'=>'操作成功');
        } else {
            $data = array('error' => 1, 'msg'=>'操作失败');
        }
        
        $this->render($data);
    }
    
    /**
     * 删除连麦权限用户
     */
    public function remLinkRightsAction()
    {
        $uid = $this->getParam('uid') ? trim($this->getParam('uid')) : '';
        $key = Link::_getKey();
        Interceptor::ensureNotFalse($uid> 0, ERROR_PARAM_NOT_SMALL_ZERO, "uid");
        $cache = Cache::getInstance('REDIS_CONF_CACHE');
        
        $ret = $cache->zRem($key, $uid);
        
        if ($ret) {
            $data = array('error' => 0, 'msg'=>'操作成功');
        } else {
            $data = array('error' => 1, 'msg'=>'操作失败');
        }
        
        $this->render($data);
    }
    
}
