<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/15
 * Time: 15:25
 */
class GameExchangeController extends BaseController
{

    public function prepareAction()
    {
        $uid=Context::get('userid')?Context::get('userid'):0;
        $amount=$this->getParam("amount", 0);
        $game=$this->getParam("game", "");
        $direct=$this->getParam("direct", 0);
        $other_orderid=$this->getParam("trade_no", "");//游戏方单号
        $sign=$this->getParam("sign", "");

        Interceptor::ensureNotFalse($uid>0, ERROR_PARAM_IS_EMPTY, 'uid');
        Interceptor::ensureNotFalse($amount>0, ERROR_PARAM_IS_EMPTY, 'amount');
        Interceptor::ensureNotEmpty($game, ERROR_PARAM_IS_EMPTY, "game");
        Interceptor::ensureNotFalse(in_array($direct, array(DAOGameExchange::DIRECT_IN,DAOGameExchange::DIRECT_OUT)), ERROR_PARAM_IS_EMPTY, "direct");
        Interceptor::ensureNotEmpty($other_orderid, ERROR_PARAM_IS_EMPTY, "trade_no");
        Interceptor::ensureNotEmpty($sign, ERROR_PARAM_IS_EMPTY, 'sign');

        $param=array('uid'=>$uid,'amount'=>$amount,'game'=>$game,'trade_no'=>$other_orderid);
        Interceptor::ensureNotFalse(GameExchange::signCheck($param, $sign), ERROR_PARAM_SIGN_INVALID, 'sign fail');

        $this->render(GameExchange::prepare($uid, $amount, $game, $direct, $other_orderid));

    }

    public function completeAction()
    {
        $uid=Context::get('userid')?Context::get('userid'):0;
        $orderid=$this->getParam("orderid", "");
        $sign=$this->getParam("sign", "");

        Interceptor::ensureNotFalse($uid>0, ERROR_PARAM_IS_EMPTY, 'uid');
        Interceptor::ensureNotEmpty($orderid, ERROR_PARAM_IS_EMPTY, 'orderid');
        Interceptor::ensureNotEmpty($sign, ERROR_PARAM_IS_EMPTY, 'sign');
        
        $param=array('uid'=>$uid,'orderid'=>$orderid);
        Interceptor::ensureNotFalse(GameExchange::signCheck($param, $sign), ERROR_PARAM_SIGN_INVALID, 'sign fail');
        $this->render(GameExchange::complete($uid, $orderid));
    }

    public function verifyAction()
    {
        $uid=Context::get('userid')?Context::get('userid'):0;
        $orderid=$this->getParam("orderid", "");

        Interceptor::ensureNotFalse($uid>0, ERROR_PARAM_IS_EMPTY, 'uid');
        Interceptor::ensureNotEmpty($orderid, ERROR_PARAM_IS_EMPTY, 'orderid');
        $this->render(GameExchange::verify($uid, $orderid));
    }

    public function listAction()
    {
        $game=$this->getParam("game", "");
        Interceptor::ensureNotEmpty($game, ERROR_PARAM_IS_EMPTY, 'game');
        $this->render(GameExchange::exchangeList($game));
    }
}