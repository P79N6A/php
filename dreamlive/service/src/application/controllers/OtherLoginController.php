<?php
class OtherLoginController extends BaseController
{

    public function loginAction()
    {
        $token = $this->getParam("token") ? $this->getParam("token") : "";
        Interceptor::ensureNotEmpty($token, ERROR_PARAM_INVALID_FORMAT, "token");
        // $uid = $this->getParam("uid") ? intval($this->getParam("uid")) : 0;
        // Interceptor::ensureNotFalse($uid >= 0, ERROR_PARAM_NOT_SMALL_ZERO, "uid");
        
        if (! Session::isLogined($token)) {
            $this->render(array('result' => false));
        }
        
        $uid = Session::getLoginId($token);
        if ($uid == 0) {
            $this->render(array('result' => false));
        }
        $shortToken = ShortToken::getToken($uid);
        $this->render(array('token' => $shortToken));
    }
    
    public function isLoginAction()
    {
        $token = $this->getParam("token") ? $this->getParam("token") : "";
        Interceptor::ensureNotEmpty($token, ERROR_PARAM_INVALID_FORMAT, "token");
        
        if(ShortToken::isLogined($token)) {
            $this->render(array('result'=>true));
        }
        $this->render(array('result'=>false));
    }
    
    public function getUserInfoAction()
    {
        //$uid     = (int)$this->getParam("uid",  0);
        $token = $this->getParam("token") ? $this->getParam("token") : "";
        Interceptor::ensureNotEmpty($token, ERROR_PARAM_INVALID_FORMAT, "token");
        $uid = ShortToken::getLoginId($token);
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid($uid)");
    
        $user = new User();
        $userinfo = $user->getUserInfo($uid);
        Interceptor::ensureNotFalse($userinfo && !Forbidden::isForbidden($uid), ERROR_USER_NOT_EXIST);
    
        $counters['live'] = Counter::mixed(
            array(
                Counter::COUNTER_TYPE_FOLLOWERS,
                Counter::COUNTER_TYPE_FOLLOWINGS,
                Counter::COUNTER_TYPE_LIVE_PRAISES
            ),
            array($uid)
        );
        $counters['payment'] = Counter::mixed(
            array(
                Counter::COUNTER_TYPE_PAYMENT_SEND_GIFT,
                Counter::COUNTER_TYPE_PAYMENT_RECEIVE_GIFT,
            ),
            array($uid)
        );
    
        $userinfo["followers"]       = (int) $counters['live'][$uid][Counter::COUNTER_TYPE_FOLLOWERS];
        $userinfo["followings"]      = (int) $counters['live'][$uid][Counter::COUNTER_TYPE_FOLLOWINGS];
        $userinfo["praises"]        = (int) $counters['live'][$uid][Counter::COUNTER_TYPE_LIVE_PRAISES];
        $userinfo["send_gift"]       = (int) $counters['payment'][$uid][Counter::COUNTER_TYPE_PAYMENT_SEND_GIFT];
        $userinfo["receive_gift"]    = (int) $counters['payment'][$uid][Counter::COUNTER_TYPE_PAYMENT_RECEIVE_GIFT];
        
        $userinfo["diamond"] = Account::getBalance($uid, Account::CURRENCY_DIAMOND);
        
        $this->render($userinfo);
    }
}
