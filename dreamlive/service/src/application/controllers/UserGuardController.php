<?php

/**
 * @desc   守护
 * @author yangqing
 */
class UserGuardController extends BaseController
{

    /**
     * 守护列表
     */
    public function getGuardAction()
    {
        $userid = Context::get("userid");
        $uid    = $this->getParam("uid") ? intval($this->getParam("uid")) : 0;
        Interceptor::ensureNotFalse(is_numeric($uid), ERROR_PARAM_INVALID_FORMAT, "uid");

        $list = UserGuard::getGuard($userid, $uid);
        $this->render($list);
    }

    /**
     * 购买守护
     */
    public function buyAction()
    {
        $userid  = Context::get("userid");
        $uid     = $this->getParam("uid")  ? intval($this->getParam("uid")) : 0;
        $type    = $this->getParam("type") ? trim($this->getParam("type"))  : 0;
        $liveid  = $this->getParam("liveid") ? trim($this->getParam("liveid"))  : 0;

        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotFalse(in_array($type, array(UserGuard::GUARD_TYPE_YEAR,UserGuard::GUARD_TYPE_MONTH)), ERROR_PARAM_INVALID_FORMAT, "type");
        Interceptor::ensureNotFalse(!($userid === $uid), ERROR_GUARD_NOT_SELF, "uid");

        $endtime = true; //返回守护到期时间
        $receiveTicket = true; //主播星票余额
        $result = UserGuard::buy($userid, $uid, $type, $liveid, $endtime, $receiveTicket);
        $this->render(array('endtime'=>$endtime,'receiveTicket'=>$receiveTicket));
    }

    /**
     * 检查用户是否是主播的守护
     */
    public function checkGuardAction()
    {
        $uid = $this->getParam('uid') ? trim($this->getParam('uid')) : 0; //用户uid
        $relateid = $this->getParam('relateid') ? intval($this->getParam('relateid')) : 0; //主播uid

        $ret = UserGuard::getUserGuardRedis($uid, $relateid, true);
        $data['expire'] = ($ret['ttl'] > 0) ? $ret['ttl'] : 0; //过期时间（单位：秒）
        $data['ttl'] = ($ret['ttl'] > 0) ? $ret['ttl'] : 0; //过期时间（单位：秒）
        $data['type'] = intval($ret['type']);
        $this->render($data);
    }

    /**
     * 我守护的人列表
     */
    public function getGuardingListAction()
    {
        $uid = Context::get("userid");
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");

        $list = UserGuard::getGuardingList($uid);
        $this->render($list);
    }

    /**
     * 守护我的人列表
     */
    public function getGuardedListAction()
    {
        $uid = Context::get("userid");
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");

        $list = UserGuard::getGuardedList($uid);
        $this->render($list);
    }

     /**
      * 守护我的人列表（直播间 ）
      */
    public function getLiveGuardedListAction()
    {
        $uid = $this->getParam('uid') ? intval($this->getParam('uid')) : 0; //主播uid
        $liveid = $this->getParam('liveid') ? intval($this->getParam('liveid')) : 0; //直播id

        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotFalse($liveid > 0, ERROR_PARAM_INVALID_FORMAT, "liveid");

        $list = UserGuard::getLiveGuardedList($uid, $liveid);
        $this->render($list);
    }
}
