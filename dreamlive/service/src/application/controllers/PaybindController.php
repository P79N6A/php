<?php
class PaybindController extends BaseController
{
    public function addAction()
    {
        $source   = $this->getParam('source');
        $account = $this->getParam('account');
        $realname = $this->getParam('realname');
        $userid = Context::get("userid");
        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "userid");
        if ($source == 'alipay') {
            Interceptor::ensureNotFalse(filter_var($account, FILTER_VALIDATE_EMAIL) || Util::isMobile($account), ERROR_BIZ_PAYBIND_ACCOUNT_NOT_VALID, "account");
        }
        Interceptor::ensureNotEmpty($source, ERROR_PARAM_IS_EMPTY, "source");
        Interceptor::ensureNotEmpty($account, ERROR_PARAM_IS_EMPTY, "account");
        
        $paybind = new PayBind();
        $paybind->add($userid, $account, $realname, $source);
        
        $this->render();
    }
    
    public function checkCodeAction()
    {
        $mobile = $this->getParam('mobile');
        $code = $this->getParam('code');
        $userid = Context::get("userid");
    
        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "userid");
        Interceptor::ensureNotFalse(Captcha::verify($mobile, $code, 'bindwithdraw'), ERROR_BIZ_PAYBIND_MOBILE_CODE_INVAILD, "code");
        
        $withdraw_mobile_dao = new DAOWithdrawMobile();
        $withdraw_mobile_dao->add($userid, $mobile);
        
        $this->render();
    }
    
    public function existAction()
    {
        $userid = $this->getParam('userid');
    
        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "userid");
        
        $withdraw_mobile_dao = new DAOWithdrawMobile();
        $mobile = $withdraw_mobile_dao->exists($userid);
        $return['exists'] = $mobile>0;
        $return['mobile'] = $mobile;

        $this->render($return);
    }
    
    public function updateAction()
    {
        $source   = $this->getParam('source');
        $account = $this->getParam('account');
        $realname = $this->getParam('realname');
        $userid = Context::get("userid");
        $relateid = $this->getParam('relateid');
        $mobile = $this->getParam('mobile');
        $code = $this->getParam('code');
        
        Interceptor::ensureNotFalse($relateid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "relateid");
        
        $paybind_dao = new DAOPayBind();
        $paybindinfo = $paybind_dao->getPaybindById($relateid);
        
        Interceptor::ensureNotFalse(Captcha::verify($mobile, $code, 'withdraw'), ERROR_BIZ_PAYBIND_MOBILE_CODE_INVAILD, "code");
        Interceptor::ensureNotEmpty($paybindinfo, ERROR_BIZ_PAYBIND_NOT_EXIST);
        Interceptor::ensureNotEmpty($source, ERROR_PARAM_IS_EMPTY, "source");
        Interceptor::ensureNotEmpty($account, ERROR_PARAM_IS_EMPTY, "account");
        
        $paybind = new PayBind();
        $bool = $paybind->update($userid, $account, $realname, $source, $relateid);
        
        $this->render();
    }
    
    public function getPaybindListAction()
    {
        $userid = Context::get("userid");
        $uid = $this->getParam('uid');
        !empty($uid) ? $userid = $uid : '';
        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "userid");
        $paybind = new PayBind();
        $info = $paybind->getPayBindList($userid);
        
        $this->render(array('account' => $info));
    }
}
?>