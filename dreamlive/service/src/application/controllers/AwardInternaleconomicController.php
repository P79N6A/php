<?php
class AwardInternaleconomicController extends BaseController
{
    //奖励钻， 从钻到钻
    public function diamondAction()
    {
        $operater     = intval($this->getParam('adminid'));
        $activeid     = $this->getParam('activeid');
        $active_name  = $this->getParam('active_name');
        $uid         = $this->getParam('uid');
        $currency    = intval($this->getParam('currency'));
        $amount      = intval($this->getParam('amount'));
        $key         = $this->getParam('key');

        Interceptor::ensureNotFalse($operater>0, ERROR_PARAM_IS_EMPTY, 'adminid');
        Interceptor::ensureNotFalse(isset($activeid), ERROR_PARAM_IS_EMPTY, 'activeid');
        Interceptor::ensureNotFalse(isset($active_name), ERROR_PARAM_IS_EMPTY, 'active_name');
        Interceptor::ensureNotFalse($amount>0, ERROR_PARAM_IS_EMPTY, 'amount');
        Interceptor::ensureFalse($key != 'yjjertert345krtwersd2EREwertwert', ERROR_PARAM_INVALID_FORMAT, "key");

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
        $orderid = $internaleconomic->systemDepositAwardDiamond($activeid, $active_name, $uid, $amount, $operater);

        $result = [
            'orderid'   => $orderid,
            'balance'   => $amount,
        ];

        $this->render($result);
    }

    //奖励钻， 从钻到票
    public function ticketAction()
    {
        $operater     = intval($this->getParam('adminid'));
        $activeid     = $this->getParam('activeid');
        $active_name  = $this->getParam('active_name');
        $uid         = $this->getParam('uid');
        $currency    = intval($this->getParam('currency'));
        $amount      = intval($this->getParam('amount'));
        $key         = $this->getParam('key');

        Interceptor::ensureNotFalse($operater>0, ERROR_PARAM_IS_EMPTY, 'adminid');
        Interceptor::ensureNotFalse(isset($activeid), ERROR_PARAM_IS_EMPTY, 'activeid');
        Interceptor::ensureNotFalse(isset($active_name), ERROR_PARAM_IS_EMPTY, 'active_name');
        Interceptor::ensureNotFalse($amount>0, ERROR_PARAM_IS_EMPTY, 'amount');
        Interceptor::ensureFalse($key != 'yjjertert345krtwersd2EREwertwert', ERROR_PARAM_INVALID_FORMAT, "key");

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
        $orderid = $internaleconomic->systemDepositAwardTicket($activeid, $active_name, $uid, $amount, $operater);

        $result = [
            'orderid'   => $orderid,
            'balance'   => $amount,
        ];

        $this->render($result);
    }

}
?>
