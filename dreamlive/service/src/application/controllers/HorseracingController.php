<?php

/**
 * 跑马游戏接口
 * User: Administrator
 * Date: 2017/5/4
 * Time: 17:59
 */
class HorseracingController extends BaseController
{
    //-------------------------星钻-----------------------------------------//
    /*
     * 抢庄接口
     * */
    public function bankerAction()
    {
        $robot          = 0;
        $gameid         = intval($this->getParam("gameid"));
        $amount         = intval($this->getParam("amount"));
        $liveid         = intval($this->getParam("liveid"));
        $userid         = Context::get('userid');
        //$userid         = 20000010; //测试

        Interceptor::ensureNotFalse($gameid > 0, ERROR_PARAM_INVALID_FORMAT, 'gameid');
        Interceptor::ensureNotFalse($amount > 0, ERROR_PARAM_INVALID_FORMAT, 'amount');
        Interceptor::ensureNotFalse($liveid > 0, ERROR_PARAM_INVALID_FORMAT, 'liveid');
        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, 'userid');

        $info           = Horseracing::banker($gameid, $amount, $liveid, $userid, $robot);

        $this -> render($info);
    }
    /**
     * 押注接口
     */
    public function stakeAction()
    {
        $gameid         = intval($this->getParam("gameid"));
        $liveid         = intval($this->getParam("liveid"));
        $amount         = intval($this->getParam("amount"));
        $trackno        = intval($this->getParam("trackno"));
        $userid         = Context::get('userid');
        //$userid         = 20000010; //测试

        Interceptor::ensureNotFalse($gameid > 0, ERROR_PARAM_INVALID_FORMAT, 'gameid');
        Interceptor::ensureNotFalse($amount > 0, ERROR_PARAM_INVALID_FORMAT, 'amount');
        Interceptor::ensureNotFalse($liveid > 0, ERROR_PARAM_INVALID_FORMAT, 'liveid');
        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, 'userid');
        Interceptor::ensureNotFalse($trackno > 0, ERROR_PARAM_INVALID_FORMAT, 'trackno');

        $info       = Horseracing::stake($gameid, $amount, $liveid, $userid, $trackno);

        $this -> render($info);
    }
    /**
     * 获取本局游戏获利前三用户与登录用户该打赏的礼物
     */
    public function getGameResultAction()
    {
        $userid         = Context::get('userid');
        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, 'userid');
        $info           = Horseracing::getGameResult($userid);

        $this ->render($info);
    }
    //------------------------------星光--------------------------------------//
    /*
     * 抢庄接口
     * */
    public function starBankerAction()
    {
        $robot          = 0;
        $gameid         = intval($this->getParam("gameid"));
        $amount         = intval($this->getParam("amount"));
        $liveid         = intval($this->getParam("liveid"));
        $userid         = Context::get('userid');
        //$userid         = 20000010; //测试

        Interceptor::ensureNotFalse($gameid > 0, ERROR_PARAM_INVALID_FORMAT, 'gameid');
        Interceptor::ensureNotFalse($amount > 0, ERROR_PARAM_INVALID_FORMAT, 'amount');
        Interceptor::ensureNotFalse($liveid > 0, ERROR_PARAM_INVALID_FORMAT, 'liveid');
        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, 'userid');

        $info           = Horseracing::starBanker($gameid, $amount, $liveid, $userid, $robot);

        $this -> render($info);
    }
    /**
     * 押注接口
     */
    public function starStakeAction()
    {
        $gameid         = intval($this->getParam("gameid"));
        $liveid         = intval($this->getParam("liveid"));
        $amount         = intval($this->getParam("amount"));
        $trackno        = intval($this->getParam("trackno"));
        $userid         = Context::get('userid');
        //$userid         = 20000010; //测试

        Interceptor::ensureNotFalse($gameid > 0, ERROR_PARAM_INVALID_FORMAT, 'gameid');
        Interceptor::ensureNotFalse($amount > 0, ERROR_PARAM_INVALID_FORMAT, 'amount');
        Interceptor::ensureNotFalse($liveid > 0, ERROR_PARAM_INVALID_FORMAT, 'liveid');
        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, 'userid');
        Interceptor::ensureNotFalse($trackno > 0, ERROR_PARAM_INVALID_FORMAT, 'trackno');

        $info       = Horseracing::starStake($gameid, $amount, $liveid, $userid, $trackno);

        $this -> render($info);
    }
    /**
     * 获取本局游戏获利前三用户与登录用户该打赏的礼物
     */
    public function getStarGameResultAction()
    {
        $userid         = Context::get('userid');
        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, 'userid');
        $info           = Horseracing::getStarGameResult($userid);

        $this ->render($info);
    }
    /**
     * 跑马游戏排行榜
     */
    public function getRankingInfoAction()
    {
        $userid = Context::get("userid");

        //$type   = $this->getParam('type',1);
        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, 'userid');
        //Interceptor::ensureNotFalse($type > 0, ERROR_PARAM_INVALID_FORMAT, 'type');

        $info   = Horseracing::getRankingInfo($userid);

        $this ->render($info);
    }
    /*public function testAction(){
        $user_info      = User::getUserInfo('10115774');
        Interceptor::ensureNotFalse(!empty($user_info),ERROR_LOGINUSER_NOT_EXIST);
        var_dump(!empty($user_info),$user_info);
        die();
        var_dump($user_info);
    }*/




}