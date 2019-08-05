<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/10
 * Time: 10:24
 */
class HandbookingerController extends BaseController
{

    /* #视频生成server回调
    public function fillUrlRoundAction(){
        $roundid=$this->getParam("roundid",0);
        $url=$this->getParam("url","");
        Interceptor::ensureNotFalse($roundid>0,ERROR_PARAM_IS_EMPTY,'roundid' );
        Interceptor::ensureNotFalse($url!="",ERROR_PARAM_IS_EMPTY,'url' );

        $this->render(Handbookinger::updateRound($roundid,0,0,$url ));
    }*/

    //新跑马押注
    public function stakeAction()
    {
        $userid         = Context::get('userid');
        $amount         = intval($this->getParam("amount"));
        $trackno        = intval($this->getParam("trackno"));

        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, 'userid');
        Interceptor::ensureNotFalse($amount > 0, ERROR_PARAM_INVALID_FORMAT, 'amount');
        Interceptor::ensureNotFalse($trackno > 0, ERROR_PARAM_INVALID_FORMAT, 'trackno');

        $this ->render(Handbookinger::stake($userid, $amount, $trackno));
    }
    //新跑马返回结果
    public function getGameResultAction()
    {
        $userid         = Context::get('userid');
        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, 'userid');

        $this -> render(Handbookinger::getGameResult($userid));
    }

}