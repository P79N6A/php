<?php
/**
 * 冲顶大会接口
 * User: User
 * Date: 2018/1/11
 * Time: 16:37
 */
class ActivityMummitController extends BaseController
{
    //获取分享用户信息
    public function getUserCodeInfoAction()
    {
        $userid         = $this->getParam('userid', '');
        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");

        $code_info           = ActivityMummit::getUserCodeInfo($userid);

        $this->render($code_info);
    }
    //分享申请邀请码
    public function applyInviteCodeAction()
    {
        $userid         = Context::get("userid");
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
    /*
     * 添加复活卡
     * */
    public function addRevivalCardAction()
    {
        $uid        = $this->getParam('uid', '');
        $num        = $this->getParam('num', 1);

        Counter::increase(Counter::COUNTER_TYPE_ROUND_NUM, $uid, $num);
        $card       = new DAOActivityHeaderCard();
        if(Counter::get('activity_code', 'card_'.$uid)>0) {
            $card->modCard($uid, $num);
        }else{
            $card->addCard($uid, $num);
        }
        $this->render();
    }
    /*
     * 每人免费领取一张复活卡
     * */
    public function freeRevivalCardAction()
    {
        $uid        = Context::get("userid");
        $num        = $this->getParam('num', 1);
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");

        $this->render(ActivityMummit::freeRevivalCard($uid, $num));
    }
    public function testAction()
    {
        $uid    = $this->getParam('uid', '');
        $this->render(ActivityMummit::freeRevivalCardexchange($uid));
    }
    /*
     * 清理复活卡
     * */
    public function clearRevivalCardAction()
    {
        $uid    = $this->getParam('uid', '');
        $num = Counter::get(Counter::COUNTER_TYPE_ROUND_NUM, $uid);
        Counter::decrease(Counter::COUNTER_TYPE_ROUND_NUM, $uid, $num);
        $this->render(Counter::get(Counter::COUNTER_TYPE_ROUND_NUM, $uid));
    }
}
