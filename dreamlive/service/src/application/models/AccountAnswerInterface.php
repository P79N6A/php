<?php
class AccountAnswerInterface extends Account
{

    public static function cash($cash)
    {
        $cash = json_decode($cash, true);
        $systemid = Account::ANSWER_ACCOUNT;
        $dao_gift_log = new DAOGiftLog();
        try {
            $dao_gift_log->startTrans();
            $orderid  = self::getOrderId($systemid);
            $type     = Account::TRADE_TYPE_ANSWER;
            $currency = Account::CURRENCY_CASH;
            foreach ($cash as $key => $value){
                $remark  = "答题获奖现金$key:$value.";
                $extends = array("uid"=>$key, 'num'=>$value);
                Interceptor::ensureNotFalse(self::decrease($systemid, $type, $orderid, $value, $currency, $remark, $extends),  ERROR_BIZ_PAYMENT_CASH_BALANCE_DUE);
                Interceptor::ensureNotFalse(self::increase($key, $type, $orderid, $value, $currency, $remark, $extends), ERROR_BIZ_PAYMENT_CASH_BALANCE_DUE);
            }

            $dao_gift_log->commit();
        } catch (Exception $e) {
            $dao_gift_log->rollback();
            throw $e;
        }

        foreach ($cash as $key => $value){
            Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $key, "您在本轮追梦の百万富翁中过关斩将，获得最终的奖励金{$value}元，我们已经将奖金发放到您的收益账户，请注意查收，有任何问题，请联系客服. ", "您在本轮追梦の百万富翁中过关斩将，获得最终的奖励金{$value}元，我们已经将奖金发放到您的收益账户，请注意查收，有任何问题，请联系客服.", 0);
        }

        return $orderid;
    }
}
?>
