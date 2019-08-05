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

    //公对公转非公对公：工会类型变更生效时刻，执行对对该工会旗下所有主播的账户的星票进行强制结算到工会长的资金账户
    public function familyCorporateChangeAction()
    {
        //|================================================================================================|
        //| 1. 所有人的票=>转到家族长的总票
        //| 2. 总票=>按分成算资金（公司资金, 工会长资金）
        $familyid = $this->getParam('familyid');
        Interceptor::ensureNotEmpty($familyid, ERROR_PARAM_IS_EMPTY, 'familyid');


        //| 1. 所有人的票=>转到家族长的总票
        $family_ticket = Withdraw::switchTo($familyid);

        //| 2. 总票=>按分成算资金（公司资金, 工会长资金）
        $withdraw = new Withdraw();
        $result = $withdraw->familyTicketToAmount($familyid, $family_ticket);

        $this->render($result);

    }

    //非公对公转公对公：执行对该工会旗下所有主播的账户可提现星票的强制结算，将星票按照每个主播的有效提现比例折算到其现金账户中
    public function familyNoCorporateChangeAction()
    {
        //|================================================================================================|
        //| 1. 所有人的票=>转到家族长的总票
        //| 2. 总票=>按分成算资金（公司资金, 工会长资金）
        $familyid = $this->getParam('familyid');
        Interceptor::ensureNotEmpty($familyid, ERROR_PARAM_IS_EMPTY, 'familyid');

        $withdraw = new Withdraw();
        $result = $withdraw->familyNoCorporateChange($familyid);

        $this->render($result);

    }


    //通过家族id得到公对公帐户的票和钱.
    public function getTicketByFamilyidAction()
    {
        $familyid = $this->getParam('familyid');
        Interceptor::ensureNotEmpty($familyid, ERROR_PARAM_IS_EMPTY, 'familyid');

        $withdraw = new Withdraw();
        $result = $withdraw->countFamilyTicket($familyid);
        $result['amount'] = floor($result['ticket'] * $result['family_percent'] / 10 * 1000)/1000;

        $this->render($result);
    }

    //工会提现
    public function familyWithdrawAddAction()
    {
        //|================================================================================================|
        //| 1. 所有人的票=>转到家族长的总票
        //| 2. 总票=>按分成算资金（公司资金, 工会长资金）
        $familyid = $this->getParam('familyid');
        $admin    = $this->getParam('admin');
        Interceptor::ensureNotEmpty($familyid, ERROR_PARAM_IS_EMPTY, 'familyid');
        Interceptor::ensureNotEmpty($admin, ERROR_PARAM_IS_EMPTY, 'admin');


        //| 1. 所有人的票=>转到家族长的总票
        $family_ticket = Withdraw::switchTo($familyid);
        //| 2. 总票=>按分成算资金（公司资金, 工会长资金）

        $amount = floor($family_ticket['ticket'] * $family_ticket['family_percent'] / 10 * 1000)/1000;
        if($amount) {
            $source = 'alipay';
            $uid    = $family_ticket['family_uid'];
            $withdraw = new Withdraw();
            $result = $withdraw->familyAdd($familyid, $uid, $source, $amount, $admin, $orderid);
        }

        $this->render($result);
    }

    //得到用户id的分成比例
    public function getAuthorPercentAction()
    {
        //|================================================================================================|
        //| 1. 所有人的票=>转到家族长的总票
        //| 2. 总票=>按分成算资金（公司资金, 工会长资金）
        $uid = $this->getParam('uid');
        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, 'uid');

        $withdraw = new Withdraw();
        $result = $withdraw->get_author_percent($uid);

        $this->render($result);
    }

    //自由主播加入到工会时， 结算主播的票到主播的资金.
    public function addToFamilyAction()
    {
        $uid = $this->getParam('uid');
        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, 'uid');

        $withdraw = new Withdraw();
        $result = $withdraw->addToFamily($uid);

        $this->render($result);
    }

    //公对公提现参数编辑, 同时更新订单数据
    public function corporateEditAction()
    {
        $orderid = $this->getParam('orderid');
        $familyid = $this->getParam('familyid');
        $author_percent = $this->getParam('author_percent');
        $pay_percent = $this->getParam('pay_percent');
        $three_pay_percent = $this->getParam('three_pay_percent');
        $is_receipt = $this->getParam('is_receipt');
        $is_receipt_real = $this->getParam('is_receipt_real');
        $rate = $this->getParam('rate');
        $settlement = $this->getParam('settlement');
        Interceptor::ensureNotEmpty($orderid, ERROR_PARAM_IS_EMPTY, 'orderid');

        $withdraw = new Withdraw();
        $result = $withdraw->corporateEdit($orderid, $familyid, $author_percent, $pay_percent, $three_pay_percent, $is_receipt, $is_receipt_real, $rate, $settlement);

        $this->render($result);
    }

    //公对公提现参数编辑, 同时更新订单数据
    public function corporateEditDetailAction()
    {
        $id = $this->getParam('id');
        $real_tickets = $this->getParam('real_tickets');
        Interceptor::ensureNotEmpty($id, ERROR_PARAM_IS_EMPTY, 'id');

        $withdraw = new Withdraw();
        $result = $withdraw->corporateEditDetail($id, $real_tickets);

        $this->render($result);
    }

    public function corporateRejectAction()
    {
        $orderid = $this->getParam('orderid');
        $reason  = $this->getParam("reason");

        Interceptor::ensureNotEmpty($orderid, ERROR_PARAM_IS_EMPTY, 'orderid');
        Interceptor::ensureNotEmpty($reason, ERROR_PARAM_IS_EMPTY, 'reason');

        $withdraw = new Withdraw();
        $result = $withdraw->updateStatus($orderid, Withdraw::WITHDRAW_STATUS_OPERATION, $reason);

        $this->render($result);
    }

    public function corporateAcceptAction()
    {
        $orderid = $this->getParam('orderid');

        Interceptor::ensureNotEmpty($orderid, ERROR_PARAM_IS_EMPTY, 'orderid');

        $withdraw = new Withdraw();
        $result = $withdraw->corporateAccept($orderid);

        $this->render($result);
    }

}
?>
