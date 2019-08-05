<?php
class InviteController extends BaseController
{
    public function joinAction()
    {
        $userid = Context::get("userid");
        $inviter = intval($this->getParam("inviter"));
        $groupid = intval($this->getParam("groupid"));

        Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");

        $daoInvite = new DAOInvite();

        if ($inviter && $groupid && ($userid != $inviter)) {
            $inviterInfo = User::getUserInfo($inviter);
            if ($inviterInfo) {
                $addtime = date("Y-m-d H:i:s");
                $expiretime = date("Y-m-d H:i:s", strtotime("+1 month"));
                $daoInvite->addInvite($userid, $inviter, $groupid, $addtime, $expiretime);
            }
        }

        $this->render();
    }

    public function getUserInviterAction()
    {
        $userid = Context::get("userid");
        Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");

        $inviter_name = "";
        $daoInvite = new DAOInvite();
        $inviteInfo = $daoInvite->getUserInviter($userid);
        if ($inviteInfo) {
            $inviterInfo = User::getUserInfo($inviteInfo['inviter']);
            $inviter_name = $inviterInfo['nickname'];
        }

        $this->render(
            array(
                'inviter_name' => $inviter_name,
                )
        );
    }

}