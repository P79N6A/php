<?php
class ActiveInternaleconomicController extends BaseController
{
    //为后台提供的运营币操作
    public function acceptAction()
    {
        $operater    = intval($this->getParam('adminid'));
        $activeid         = $this->getParam('activeid');
        $uid         = $this->getParam('uid');
        $currency    = intval($this->getParam('currency'));
        $amount      = intval($this->getParam('amount'));
        $tmp = explode(",", $uid);
        if(is_array($tmp)) {
            foreach ($tmp as $key => $value){
                Interceptor::ensureNotFalse($value, ERROR_PARAM_IS_EMPTY, 'uid');
                $userinfo = User::getUserInfo($value);
                Interceptor::ensureFalse(!$userinfo, ERROR_USER_NOT_EXIST, "uid");
                $dao_profile = new DAOProfile($value);
                $profiles = $dao_profile->getUserProfiles();
                Interceptor::ensureNotFalse((!(isset($profiles['frozen']) && $profiles['frozen'] == 'Y')), ERROR_BIZ_GIFT_FROZEN, "uid");
            }
        } else {
            Interceptor::ensureNotFalse($uid, ERROR_PARAM_IS_EMPTY, 'uid');
            $userinfo = User::getUserInfo($uid);
            Interceptor::ensureFalse(!$userinfo, ERROR_USER_NOT_EXIST, "uid");
            $dao_profile = new DAOProfile($uid);
            $profiles = $dao_profile->getUserProfiles();
            Interceptor::ensureNotFalse((!(isset($profiles['frozen']) && $profiles['frozen'] == 'Y')), ERROR_BIZ_GIFT_FROZEN, "uid");
        }

        $internaleconomic = new Internaleconomic();
        $orderid = $internaleconomic->systemDepositActive($activeid, $uid, $amount, $operater);

        $result = [
            'orderid'   => $orderid,
            'balance'   => $amount,
        ];

        $this->render($result);
    }

}
?>
