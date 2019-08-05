<?php
class Internaleconomic
{
    public static function systemDeposit($uid, $amount, $operater)
    {
        $account = new Account();
        $diamond = $account->getBalance(Account::ACTIVE_ACCOUNT2, ACCOUNT::CURRENCY_DIAMOND);
        Interceptor::ensureNotEmpty($diamond, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

        $arr = explode(",", $uid);
        if(is_array($arr)) {
            $amounts = count($arr) * $amount;
            Interceptor::ensureNotFalse($diamond > $amounts, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            $dao_deposit = new DAODeposit();
            try {
                $dao_deposit->startTrans();
                foreach ($arr as $key => $value){
                    $orderid = Account::getOrderId($value);
                    Account::decrease(Account::ACTIVE_ACCOUNT2, Account::TRADE_TYPE_INTERNALECONOMIC, $orderid, $amount, Account::CURRENCY_DIAMOND, "{$operater}充运营币$amount",  array());
                    Account::increase($value, Account::TRADE_TYPE_INTERNALECONOMIC, $orderid, $amount, Account::CURRENCY_DIAMOND, "{$operater}充运营币$amount",  array());
                }
                $dao_deposit->commit();
            } catch (MySQLException $e) {
                $dao_deposit->rollback();
                throw new BizException(ERROR_SYS_DB_SQL);
            }

        } else {
            Interceptor::ensureNotFalse($diamond > $amount, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            $dao_deposit = new DAODeposit();
            try {
                $dao_deposit->startTrans();
                $orderid = Account::getOrderId($uid);
                Account::decrease(Account::ACTIVE_ACCOUNT2, Account::TRADE_TYPE_INTERNALECONOMIC, $orderid, $amount, Account::CURRENCY_DIAMOND, "{$operater}充运营币$amount",  array());
                Account::increase($uid, Account::TRADE_TYPE_INTERNALECONOMIC, $orderid, $amount, Account::CURRENCY_DIAMOND, "{$operater}充运营币$amount",  array());
                $dao_deposit->commit();
            } catch (MySQLException $e) {
                $dao_deposit->rollback();
                throw new BizException(ERROR_SYS_DB_SQL);
            }
        }

        return $orderid;
    }

    public static function systemDepositActive($activeid, $uid, $amount, $operater)
    {
        $account = new Account();
        $diamond = $account->getBalance(Account::ACTIVE_ACCOUNT25, ACCOUNT::CURRENCY_DIAMOND);
        Interceptor::ensureNotEmpty($diamond, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

        $arr = explode(",", $uid);
        if(is_array($arr)) {
            $amounts = count($arr) * $amount;
            Interceptor::ensureNotFalse($diamond > $amounts, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            $dao_deposit = new DAODeposit();
            try {
                $dao_deposit->startTrans();
                foreach ($arr as $key => $value){
                    $orderid = Account::getOrderId($value);
                    Account::decrease(Account::ACTIVE_ACCOUNT25, Account::TRADE_TYPE_ACTIVE, $orderid, $amount, Account::CURRENCY_DIAMOND, "{$operater}充活动币$amount",  array('activeid'=>$activeid));
                    Account::increase($value, Account::TRADE_TYPE_ACTIVE, $orderid, $amount, Account::CURRENCY_DIAMOND, "{$operater}充活动币$amount",  array('activeid'=>$activeid));
                }
                $dao_deposit->commit();
            } catch (MySQLException $e) {
                $dao_deposit->rollback();
                throw new BizException(ERROR_SYS_DB_SQL);
            }

        } else {
            Interceptor::ensureNotFalse($diamond > $amount, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            $dao_deposit = new DAODeposit();
            try {
                $dao_deposit->startTrans();
                $orderid = Account::getOrderId($uid);
                Account::decrease(Account::ACTIVE_ACCOUNT25, Account::TRADE_TYPE_ACTIVE, $orderid, $amount, Account::CURRENCY_DIAMOND, "{$operater}充活动币$amount",  array('activeid'=>$activeid));
                Account::increase($uid, Account::TRADE_TYPE_ACTIVE, $orderid, $amount, Account::CURRENCY_DIAMOND, "{$operater}充活动币$amount",  array('activeid'=>$activeid));
                $dao_deposit->commit();
            } catch (MySQLException $e) {
                $dao_deposit->rollback();
                throw new BizException(ERROR_SYS_DB_SQL);
            }
        }

        return $orderid;
    }

}
?>