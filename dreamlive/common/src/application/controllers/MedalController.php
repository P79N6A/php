<?php
class MedalController extends BaseController
{
    public function setUserMedalAction()
    {
        /* {{{ */
        $userid = $this->getParam("uid", 0);
        $kind = trim($this->getParam("kind"));
        $medal = trim($this->getParam("medal"));
        $ticket = trim($this->getParam("ticket"));
        
        Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");
        Interceptor::ensureNotFalse(in_array($kind, DAOUserMedal::$MEDAL_KIND), ERROR_PARAM_INVALID_FORMAT, "kind");
        
        if ($ticket) {
            Interceptor::ensureNotFalse(Util::checkTicket($ticket), ERROR_PARAM_INVALID_FORMAT, "ticket");
        }
        
        UserMedal::addUserMedal($userid, $kind, $medal);
        User::reload($userid);
        
        $this->render();
    } /* }}} */

    public function addActiveMedalAction()
    {
        /* {{{ */
        $uid   = $this->getParam("uid", 0);
        $medal = trim(strip_tags($this->getParam("medal")), '');

        Interceptor::ensureNotFalse(is_numeric($uid) && $uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotEmpty($medal, ERROR_PARAM_INVALID_FORMAT, "medal");

        UserMedal::addUserMedal($uid, UserMedal::KIND_ACTIVITY, $medal);
        User::reload($uid);

        $this->render();
    } /* }}} */

    public function delActiveMedalAction()
    {
        /* {{{ */
        $uid   = $this->getParam("uid", 0);

        Interceptor::ensureNotFalse(is_numeric($uid) && $uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");

        UserMedal::delUserMedal($uid, UserMedal::KIND_ACTIVITY);
        User::reload($uid);

        $this->render();
    } /* }}} */
}
