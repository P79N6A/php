<?php
class WalletController extends BaseController
{
    public function getWalletInfoAction()
    {
        $account = new Account();
        $account_list = $account->getAccountList(Context::get("userid"));

        $wallet_info = array(
            "account"=>$account_list
        );

        $this->render($wallet_info);
    }

    //财务派发
    public function distributeAction()
    {
    	$userid  = $this->getParam("userid")  ? $this->getParam("userid")  : 0;
        $amount  = $this->getParam("amount")  ? $this->getParam("amount")  : 0;
        $remark  = $this->getParam("remark")  ? $this->getParam("remark")  : "";
        $extends = $this->getParam("extends") ? json_decode($this->getParam("extends")) : "[]";

        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");
        Interceptor::ensureNotFalse($amount > 0, ERROR_PARAM_INVALID_FORMAT, "amount");
        Interceptor::ensureNotEmpty($remark, ERROR_PARAM_IS_EMPTY, "remark");
        Logger::log('sell_log', 'distribute1',  array("param" => json_encode($_REQUEST)));
        $orderid = Account::distribute($userid, $amount, $remark, $extends);

        $this->render(array("orderid"=>$orderid));
    }

    //财务回收
    public function recycleAction()
    {
        $userid  = $this->getParam("userid")  ? $this->getParam("userid")  : 0;
        $amount  = $this->getParam("amount")  ? $this->getParam("amount")  : 0;
        $remark  = $this->getParam("remark")  ? $this->getParam("remark")  : "";
        $extends = $this->getParam("extends") ? json_decode($this->getParam("extends")) : array();

        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");
        Interceptor::ensureNotFalse($amount > 0, ERROR_PARAM_INVALID_FORMAT, "amount");
        Interceptor::ensureNotEmpty($remark, ERROR_PARAM_IS_EMPTY, "remark");

        $orderid = Account::recycle($userid, $amount, $remark, $extends);

        $this->render(array("orderid"=>$orderid));
    }

    //运营冻结账户金额
    public function freezeAction()
    {
        $userid  = $this->getParam("userid")  ? $this->getParam("userid")  : 0;
        $amount  = $this->getParam("amount")  ? $this->getParam("amount")  : 0;
        $remark  = $this->getParam("remark")  ? $this->getParam("remark")  : "";
        $extends = $this->getParam("extends") ? json_decode($this->getParam("extends")) : array();

        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");
        Interceptor::ensureNotFalse($amount > 0, ERROR_PARAM_INVALID_FORMAT, "amount");
        Interceptor::ensureNotEmpty($remark, ERROR_PARAM_IS_EMPTY, "remark");

        $orderid = Account::freeze($userid, $amount, $remark, $extends);

        $this->render(array("orderid"=>$orderid));
    }

    //运营解冻账户金额
    public function unfreezeAction()
    {
        $userid  = $this->getParam("userid")  ? $this->getParam("userid")  : 0;
        $amount  = $this->getParam("amount")  ? $this->getParam("amount")  : 0;
        $remark  = $this->getParam("remark")  ? $this->getParam("remark")  : "";
        $extends = $this->getParam("extends") ? json_decode($this->getParam("extends")) : array();

        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");
        Interceptor::ensureNotFalse($amount > 0, ERROR_PARAM_INVALID_FORMAT, "amount");
        Interceptor::ensureNotEmpty($remark, ERROR_PARAM_IS_EMPTY, "remark");

        $orderid = Account::unfreeze($userid, $amount, $remark, $extends);

        $this->render(array("orderid"=>$orderid));
    }
}
?>
