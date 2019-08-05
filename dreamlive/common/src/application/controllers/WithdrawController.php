<?php
class WithdrawController extends BaseController
{
    public function applyAction()
    {
        $uid      = Context::get("userid");
        $source   = $this->getParam('source');
        $amount   = $this->getParam('amount');

        Interceptor::ensureNotEmpty($amount, ERROR_PARAM_IS_EMPTY, 'amount');
        Interceptor::ensureNotEmpty($source, ERROR_PARAM_IS_EMPTY, 'source');

        $withdraw = new Withdraw();
        $result = $withdraw->add($uid, $source, $amount);

        $this->render($result);
    }

    public function checkAction()
    {        
        $dao_withdraw = new DAOWithdraw();
        $sql = "select id, orderid, uid, `status` from withdraw where status!=5 order by id desc limit  ?";
        
        $data = $dao_withdraw->getAll($sql, 1000);

        print "<pre>";
        print_r("共统计". count($data) . "条");
        print "</pre>";

        foreach ($data as $key => $value) {
            $str = (int)substr($value['uid'], -2);
            $sql = "select * from journal_$str where orderid={$value['orderid']} and uid=? limit 1";
            $data_journal = $dao_withdraw->getRow($sql, $value['uid']);
            if($data_journal['id']) {
                print "<pre>";
                print_r($value['uid'] . "的提现订单" . $value['orderid'] . '正常');
                print "</pre>";
            } else {
                print "<pre>";
                print_r("<font color='#ff0000'>" . $value['uid'] . "的提现订单" . $value['orderid'] . '不正常</font>');
                print "</pre>";
            }
        }
        exit;

    }


    

    public function applyForH5Action()
    {
        $uid      = $this->getParam('userid');
        $source   = $this->getParam('source');
        $amount   = $this->getParam('amount');
        $sign     = $this->getParam('sign');

        Interceptor::ensureNotEmpty($amount, ERROR_PARAM_IS_EMPTY, 'amount');
        Interceptor::ensureNotEmpty($source, ERROR_PARAM_IS_EMPTY, 'source');
        Interceptor::ensureNotFalse(Withdraw::checkSign($uid, $sign), ERROR_BIZ_PAYMENT_WITHDRAW_TOKEN, "sign");

        $withdraw = new Withdraw();
        $result = $withdraw->add($uid, $source, $amount);

        $this->render($result);
    }

    public function applyCashForH5Action()
    {
        $uid      = $this->getParam('userid');
        $source   = $this->getParam('source');
        $amount   = $this->getParam('amount');
        $sign     = $this->getParam('sign');

        Interceptor::ensureNotEmpty($amount, ERROR_PARAM_IS_EMPTY, 'amount');
        Interceptor::ensureNotEmpty($source, ERROR_PARAM_IS_EMPTY, 'source');
        Interceptor::ensureNotFalse(Withdraw::checkSign($uid, $sign), ERROR_BIZ_PAYMENT_WITHDRAW_TOKEN, "sign");

        $withdraw = new Withdraw();
        $result = $withdraw->addCash($uid, $source, $amount);

        $this->render($result);
    }

    public function acceptAction()
    {
        $orderid = $this->getParam('orderid');

        Interceptor::ensureNotEmpty($orderid, ERROR_PARAM_IS_EMPTY, 'orderid');

        $withdraw = new Withdraw();
        $result = $withdraw->accept($orderid);

        $this->render($result);
    }

    public function rejectAction()
    {
        $orderid = $this->getParam('orderid');
        $reason  = $this->getParam("reason");

        Interceptor::ensureNotEmpty($orderid, ERROR_PARAM_IS_EMPTY, 'orderid');
        Interceptor::ensureNotEmpty($reason, ERROR_PARAM_IS_EMPTY, 'reason');

        $withdraw = new Withdraw();
        $result = $withdraw->reject($orderid, $reason);

        $this->render($result);
    }

    public function getWithdrawListAction()
    {
        $uid    = Context::get("userid");
        $offset = (int) $this->getParam("offset")?(int) $this->getParam("offset"):PHP_INT_MAX;
        $num    = (int) $this->getParam("num", 20);

        Interceptor::ensureFalse($num > 200, ERROR_PARAM_INVALID_FORMAT, "num");

        list($total, $list, $offset) = Withdraw::getWithdrawList($uid, $offset, $num);

        $this->render(
            array(
            'list' => $list,
            'total' => $total,
            'offset' => $offset,
            )
        );
    }

    public function getWithdrawListForH5Action()
    {
        $uid    = $this->getParam('userid');
        $offset = (int) $this->getParam("offset")?(int) $this->getParam("offset"):PHP_INT_MAX;
        $num    = (int) $this->getParam("num", 20);
        $sign     = $this->getParam('sign');

        Interceptor::ensureFalse($num > 200, ERROR_PARAM_INVALID_FORMAT, "num");
        Interceptor::ensureNotFalse(Withdraw::checkSign($uid, $sign), ERROR_BIZ_PAYMENT_WITHDRAW_TOKEN, "sign");

        list($total, $list, $offset) = Withdraw::getWithdrawList($uid, $offset, $num);

        $this->render(
            array(
            'list' => $list,
            'total' => $total,
            'offset' => $offset,
            )
        );
    }

    public function getWithdrawPriceAction()
    {
        $uid    = Context::get("userid");

        $result = Withdraw::getWithdrawPrice($uid);

        $this->render($result);
    }

    public function getWithdrawPriceForH5Action()
    {
        $uid    = $this->getParam('userid');
        $sign     = $this->getParam('sign');

        Interceptor::ensureNotFalse(Withdraw::checkSign($uid, $sign), ERROR_BIZ_PAYMENT_WITHDRAW_TOKEN, "sign");

        $result = Withdraw::getWithdrawPrice($uid);

        $this->render($result);
    }


    public function familyAcceptAction()
    {
        $orderid = $this->getParam('orderid');
        $reason  = $this->getParam("reason", "家族体现确认通过");

        Interceptor::ensureNotEmpty($orderid, ERROR_PARAM_IS_EMPTY, 'orderid');

        $withdraw = new Withdraw();
        $result = $withdraw->updateStatus($orderid, Withdraw::WITHDRAW_STATUS_OPERATION, $reason);

        $this->render($result);
    }

    public function operationAcceptAction()
    {
        $orderid = $this->getParam('orderid');
        $reason  = $this->getParam("reason", "运营提现审批通过");

        Interceptor::ensureNotEmpty($orderid, ERROR_PARAM_IS_EMPTY, 'orderid');

        $withdraw = new Withdraw();
        $result = $withdraw->updateStatus($orderid, Withdraw::WITHDRAW_STATUS_FINANCE, $reason);

        $this->render($result);
    }

    public function getSignAction()
    {
        $uid = $this->getParam('userid');
        $mobile = $this->getParam('mobile');
        $code = $this->getParam('code');
        Interceptor::ensureNotFalse(Captcha::verify($mobile, $code, "exchange"), ERROR_CODE_INVALID, $mobile. "($code)");

        $sign = Withdraw::getSign($uid, $mobile, $code);

        $this->render(array("sign"=>$sign));
    }

}
?>
