<?php
class OnlineController extends BaseController
{

    public function getUsersAction()
    {
        $offset     = $this->getParam("offset")     ? (int)($this->getParam("offset"))   : 0;
        $num        = $this->getParam("num")        ? intval($this->getParam("num"))     : 20;
        $type       = $this->getParam("type")        ? intval($this->getParam("type"))   : 1;//只对miniapp有效，1在线，2，推荐
        $userid  = Context::get("userid");

        $online = new OnlineUsers();
        $returnData = $online->getMyFollowedUserList($userid, $offset, $num, $type);

        $this->render($returnData);
    }
}