<?php
class WuInternaleconomicController extends BaseController
{
    //吴总， 从1035到类型为69预算
    public function diamondAction()
    {
        $operater     = intval($this->getParam('adminid'));
        $uid         = $this->getParam('uid');
        $amount      = intval($this->getParam('amount')) * 10;
        $key         = $this->getParam('key');
        $tradeid     = $this->getParam('tradeid');

        Interceptor::ensureNotFalse($operater>0, ERROR_PARAM_IS_EMPTY, 'adminid');
        Interceptor::ensureNotFalse($amount>0, ERROR_PARAM_IS_EMPTY, 'amount');
        Interceptor::ensureNotFalse($tradeid>0, ERROR_PARAM_IS_EMPTY, 'tradeid');
        Interceptor::ensureFalse($key != 'yjjertert345krtwersd232EREwertwdssert', ERROR_PARAM_INVALID_FORMAT, "key");
        //Interceptor::ensureNotFalse(in_array($uid, array(10016037,10000001)), ERROR_PARAM_INVALID_FORMAT, "uid");

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
        $orderid = $internaleconomic->systemDepositWu($uid, $amount, $operater, $tradeid);

        $result = [
            'orderid'   => $orderid,
            'balance'   => $amount,
        ];

        $this->render($result);
    }

    

}
?>
