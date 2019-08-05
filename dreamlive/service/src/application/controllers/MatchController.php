<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2017/12/11
 * Time: 10:40
 */
class MatchController extends BaseController
{
    //pk发起
    public function applyAction()
    {
        $uid        = Context::get('userid');
        $invitee    = (int)$this -> getParam('invitee', 0);
        $duration   = (int)$this -> getParam('duration', 0);//时长分钟

        Interceptor::ensureNotEmpty($duration,    ERROR_PARAM_IS_EMPTY, "duration");
        Interceptor::ensureNotEmpty($uid,  ERROR_PARAM_IS_EMPTY, "uid");

        $this -> render(Match::apply($uid, $invitee, $duration));
    }
    //接受pk
    public function acceptAction()
    {
        $uid            = Context::get('userid');
        $matchid        = (int)$this->getParam('matchid', '');

        Interceptor::ensureNotEmpty($uid,    ERROR_PARAM_IS_EMPTY, "uid");
        Interceptor::ensureNotEmpty($matchid,    ERROR_PARAM_IS_EMPTY, "matchid");

        $this -> render(Match::accept($uid, $matchid));
    }
    //pk广场
    public function getMatchListAction()
    {
        //缺少分页
        $uid            = Context::get('userid');
        $offset         = (int)$this->getParam('offset', PHP_INT_MAX);
        $offset         = $offset>0?$offset:PHP_INT_MAX;
        $num            = (int)$this->getParam('num', 15);
        $num            = $num>0?$num:15;
        Interceptor::ensureNotEmpty($uid,    ERROR_PARAM_IS_EMPTY, "uid");

        $this -> render(Match::getMatchList($uid, $offset, $num));
    }
    //pk记录
    public function getMyMatchListAction()
    {
        $uid            = Context::get('userid');
        Interceptor::ensureNotEmpty($uid,    ERROR_PARAM_IS_EMPTY, "uid");

        $this -> render(Match::getMyMatchList($uid));
    }
    //pk贡献
    public function rankAction()
    {
        $uid            = Context::get('userid');
        $matchid        = (int)$this->getParam('matchid', 0);

        Interceptor::ensureNotEmpty($uid,    ERROR_PARAM_IS_EMPTY, "uid");
        Interceptor::ensureNotEmpty($matchid,    ERROR_PARAM_IS_EMPTY, "matchid");

        $this -> render(Match::rank($uid, $matchid));
    }
    //pk详情
    public function getMatchInfoAction()
    {
        $uid        = Context::get('userid');
        $matchid    = (int)$this->getParam('matchid', 0);
        Interceptor::ensureNotEmpty($uid,    ERROR_PARAM_IS_EMPTY, "uid");
        Interceptor::ensureNotEmpty($matchid,    ERROR_PARAM_IS_EMPTY, "matchid");

        $this -> render(Match::getMatchInfo($uid, $matchid));
    }
    //添加小黑屋
    public function imprisonAction()
    {
        $uid        = (int)$this->getParam('uid', '');
        $second     = (int)$this->getParam('second', 0);//单位天
        $source     = $this->getParam('source', 'MATCH');
        $matchid    = (int)$this->getParam('matchid', 0);
        $adminid    = (int)$this->getParam('adminid', 0);

        Interceptor::ensureNotEmpty($uid,    ERROR_PARAM_IS_EMPTY, "uid");
        Interceptor::ensureNotEmpty($second>0,    ERROR_PARAM_IS_EMPTY, "second");

        $this -> render(Match::imprison($uid, $second, $source, $matchid, $adminid));
    }
    //移除小黑屋
    public function releaseAction()
    {
        $prisonid        = (int)$this->getParam('prisonid', '');
        $note             = trim($this->getParam('note', ''));
        Interceptor::ensureNotEmpty($prisonid,    ERROR_PARAM_IS_EMPTY, "prisonid");

        $this -> render(Match::release($prisonid, $note));
    }
    //爆发榜
    public function getOutbreakRankAction()
    {
        $this ->render();
    }
    
    public function getPkInfoAction()
    {
        $matchid    = (int) $this->getParam('matchid', 0);
        $uid        = (int) $this->getParam('uid', 0);
        
        Interceptor::ensureNotEmpty($uid,    ERROR_PARAM_IS_EMPTY, "uid");
        Interceptor::ensureNotEmpty($matchid,    ERROR_PARAM_IS_EMPTY, "matchid");
        
        $data = MatchMessage::getPkRankListAll($matchid, $uid);
        
        $this->render($data);
    }

    //获取正在进行的pk
    public function getConductPKAction()
    {
        $list       = Match::getConductPK();
        $this -> render($list);
    }
    /*
     * 获取pk主播榜前三
     * */
    public function getAnchorTop3Action()
    {
        $this->render(Match::getAnchorTop3());
    }
    /*
     * 获取土豪前三
     * */
    public function getUserTop3Action()
    {
        $this ->render(Match::getUserTop3());
    }
    
    //主播榜
    public function getAnchorRankAction()
    {
        $this ->render();
    }
    //土豪榜
    public function getLocalTyrantsRankAction()
    {
        $uid        = $this->getParam('uid');
        $score      = $this->getParam('score', '');
        $this ->render(Match::sendMatch($uid, $score));
    }
    public function testAction()
    {
        Messenger::sendToGroup(11041599, Messenger::MESSAGE_TYPE_USER_ACCEPT_MATCH, 21000475, "通知受邀者直播间用户更新pk信息", array());

    }
}
