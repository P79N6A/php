<?php
/**
 * 冲顶大会接口
 * User: User
 * Date: 2018/1/11
 * Time: 16:37
 */
class ActivityMummitController extends BaseController
{
    //分享申请邀请码
    public function applyInviteCodeAction()
    {
        $userid         = $this->getParam('userid', '')?$this->getParam('userid', ''):Context::get("userid");
        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");

        $code_info           = ActivityMummit::applyInviteCode($userid);

        $this->render($code_info);
    }
    //激活邀请码
    public function useInviteCodeAction()
    {
        $code           = $this->getParam('code');
        $userid         = Context::get("userid");
        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");
        Interceptor::ensureNotFalse($code, ERROR_PARAM_INVALID_FORMAT, "userid");

        ActivityMummit::useInviteCode($userid, $code);
        $this->render();
    }
    //榜单排行
    public function getRankAction()
    {
        $rank   = ActivityMummit::getRank();

        $this->render($rank);
    }
    /*场次详情*/
    public function getMatchInfoAction()
    {
        $userid         = Context::get("userid");
        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");

        $this -> render(ActivityMummit::getMatchInfo($userid));
    }
}
