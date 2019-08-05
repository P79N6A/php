<?php
class OnlineController extends BaseController
{
    
    public function getUsersAction()
    {
        $offset     = $this->getParam("offset")     ? (int)($this->getParam("offset"))   : 0;
        $num        = $this->getParam("num")        ? intval($this->getParam("num"))     : 20;
        $userid  = Context::get("userid");
        
        $online = new OnlineUsers();
        $returnData = $online->getMyFollowedUserList($userid, $offset, $num);
        
        $this->render($returnData);
    }
}