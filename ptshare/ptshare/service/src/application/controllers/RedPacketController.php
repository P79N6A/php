<?php
class RedPacketController extends BaseController
{
    public function groupRedPacketSuccessAction()
    {
        $userid  = Context::get("userid");
        $taskid  = $this->getParam("taskid", 6);
        $redid   = $this->getParam("redid", 0);

        Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_IS_EMPTY, "userid");
        Interceptor::ensureNotFalse(is_numeric($taskid) && $taskid > 0, ERROR_PARAM_IS_EMPTY, "taskid");
        Interceptor::ensureNotFalse(is_numeric($redid) && $redid > 0, ERROR_PARAM_IS_EMPTY, "redid");

        $result = RedPacket::groupRedPacketSuccess($userid, $redid);

        // 执行首次分享好友任务
        try {
            Task::execute($userid, 2, 1);
        } catch (Exception $e) {}

        $this->render(array('param'=>array('action'=>'/redPacket/receiveGroup','uid'=>$userid,'redid'=>$redid)));
    }

    /**
     * 领取群红包
     */
    public function ReceiveGroupAction()
    {
        $userid = Context::get("userid");
        $uid    = $this->getParam("uid", 0);
        $redid  = $this->getParam("redid", 0);

        Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");
        Interceptor::ensureNotFalse(is_numeric($uid) && $uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotFalse(is_numeric($redid) && $redid > 0, ERROR_PARAM_INVALID_FORMAT, "redid");

        $newUser = User::isNewUser($userid);
        list ($uid, $redid, $amount,$flags) = RedPacket::receiveGroupRedPacket($userid, $uid, $redid, $newUser);

        // 执行每日群红包任务  需异步处理
        /**
        try {
            Task::execute($uid, 6, 1);
        } catch (Exception $e) {}
        */

        $user = new User();
        $userInfo = $user->getUserInfo($uid);
        $this->render(array('redPacket' => array('redid' => $redid,'amount' => $amount, 'type'=>$flags),'userInfo' => $userInfo));
    }

    /**
     * 领取红包列表
     */
    public function getRedPacketListAction()
    {
        $userid = Context::get("userid");
        $uid    = $this->getParam("uid", 0);
        $redid  = $this->getParam("redid", 0);

        Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");
        Interceptor::ensureNotFalse(is_numeric($uid) && $uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotFalse(is_numeric($redid) && $redid > 0, ERROR_PARAM_INVALID_FORMAT, "redid");

        $list = RedPacket::getRedPacketList($uid, $redid);

        $this->render(array('list' => $list));
    }

    /**
     * 领取红包历史记录
     */
    public function getUserRedPacketLogListAction()
    {
        $offset = $this->getParam("offset") ? (int) ($this->getParam("offset")) : 0;
        $num    = $this->getParam("num")    ? intval($this->getParam("num")) : 20;
        $type   = $this->getParam("type",'');  //send:我发的红包被哪些人领取，receive:我领取哪些人发的红包
        $userid = Context::get("userid");

        Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");
        Interceptor::ensureNotEmpty($type, ERROR_PARAM_INVALID_FORMAT, "type");

        list($list,$total,$offset,$more) = RedPacketLog::getUserRedPacketLogList($userid, $type, $num, $offset);

        $this->render(array('list' => $list, 'total' => $total, 'offset'=>$offset, 'more'=>$more));
    }

    /**
     * 领取红包详情
     */
    public function getUserRedPacketLogInfoAction()
    {
        $userid = Context::get("userid");
        $uid    = $this->getParam("uid", 0);
        $redid  = $this->getParam("redid", 0);

        Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");
        Interceptor::ensureNotFalse(is_numeric($uid) && $uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotFalse(is_numeric($redid) && $redid > 0, ERROR_PARAM_INVALID_FORMAT, "redid");
        Logger::log("account", "getUserRedPacketLogInfoAction :", array("uid" => $uid, "userid" => $userid, "redid" => $redid));
        list($redPacket, $userInfo) = RedPacketLog::getUserRedPacketLogInfo($userid, $uid, $redid);


        $this->render(array('redPacket' => $redPacket, 'userInfo' => $userInfo));

    }
}
