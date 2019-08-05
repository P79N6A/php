<?php
class Prop
{
    public function send($sender, $liveid, $content)
    {
        $account     = new Account();
        $dao_account = new DAOAccount($sender);
        try {
            $dao_account->startTrans();

            $orderid = $account->getOrderId($sender);

            $extends = [
                'sender' => $sender,
                'content' => $content,
                'liveid' => $liveid,
            ];

            $diamond = $account->getBalance($sender, ACCOUNT::CURRENCY_DIAMOND);
            Interceptor::ensureNotEmpty($diamond, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse($diamond >= 1, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse($account->decrease($sender, ACCOUNT::TRADE_TYPE_SYSTERM_TOOL, $orderid, 1, ACCOUNT::CURRENCY_DIAMOND, "用户{$sender}发送弹幕{$content},消费1钻", $extends), ERROR_BIZ_PAYMENT_DIAMOND_OUT_ACCOUNTED);
            Interceptor::ensureNotFalse($account->increase(ACCOUNT::COMAPNY_ACCOUNT, ACCOUNT::TRADE_TYPE_SYSTERM_TOOL, $orderid, 1, ACCOUNT::CURRENCY_TICKET, "用户{$sender}发送弹幕{$content},系统收到1个票", $extends), ERROR_BIZ_PAYMENT_TICKET_ACCOUNTED_FOR);

            $dao_account->commit();
        } catch (Exception $e) {
            $dao_account->rollback();
            throw $e;
        }
        
        return true;
    }
}
