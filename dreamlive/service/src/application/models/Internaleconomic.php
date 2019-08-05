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
            $order_array = array();
            try {
                $dao_deposit->startTrans();
                foreach ($arr as $key => $value){
                    $orderid = Account::getOrderId($value);
                    $order_array[$key] = $orderid;
                    Account::decrease(Account::ACTIVE_ACCOUNT2, Account::TRADE_TYPE_INTERNALECONOMIC, $orderid, $amount, Account::CURRENCY_DIAMOND, "{$operater}充运营币$amount",  array());
                    Account::increase($value, Account::TRADE_TYPE_INTERNALECONOMIC, $orderid, $amount, Account::CURRENCY_DIAMOND, "{$operater}充运营币$amount",  array());
                }
                $dao_deposit->commit();
            } catch (MySQLException $e) {
                $dao_deposit->rollback();
                throw new BizException(ERROR_SYS_DB_SQL);
            }

            //vip
            foreach ($arr as $key => $value){
                $deposit_info = array(
                'orderid'=> $order_array[$key]['orderid'],
                'uid'    => $value,
                'amount' => $amount,
                );

                // 信息改变发送同步到任务后台
                include_once 'process_client/ProcessClient.php';
                ProcessClient::getInstance("dream")->addTask("vip_consume_add_worker", $deposit_info);
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
            //vip
            $deposit_info = array(
            'orderid'=> $orderid,
            'uid'    => $uid,
            'amount' => $amount,
            );

            // 信息改变发送同步到任务后台
            include_once 'process_client/ProcessClient.php';
            ProcessClient::getInstance("dream")->addTask("vip_consume_add_worker", $deposit_info);

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

        //vip
        $deposit_info = array(
        'orderid'=> $orderid,
        'uid'    => $uid,
        'amount' => $amount,
        );

        // 信息改变发送同步到任务后台
        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("vip_consume_add_worker", $deposit_info);

        return $orderid;
    }

    public static function systemDepositAwardDiamond($activeid, $active_name, $uid, $amount, $operater)
    {
        $account = new Account();
        $diamond = $account->getBalance(Account::ACTIVE_ACCOUNT15, ACCOUNT::CURRENCY_DIAMOND);
        Interceptor::ensureNotEmpty($diamond, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

        $arr = explode(",", $uid);
        if(is_array($arr)) {
            $amounts = count($arr) * $amount;
            Interceptor::ensureNotFalse($diamond >= $amounts, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            $dao_deposit = new DAODeposit();
            try {
                $dao_deposit->startTrans();
                foreach ($arr as $key => $value){
                    $orderid = Account::getOrderId($value);
                    Account::decrease(Account::ACTIVE_ACCOUNT15, Account::TRADE_TYPE_AWARD, $orderid, $amount, Account::CURRENCY_DIAMOND, "{$operater}通过{$active_name}($activeid)奖励钻$amount",  array('activeid'=>$activeid));
                    Account::increase($value, Account::TRADE_TYPE_AWARD, $orderid, $amount, Account::CURRENCY_DIAMOND, "{$operater}通过{$active_name}($activeid)奖励钻$amount",  array('activeid'=>$activeid));
                }
                $dao_deposit->commit();
            } catch (MySQLException $e) {
                $dao_deposit->rollback();
                throw new BizException(ERROR_SYS_DB_SQL);
            }

        } else {
            Interceptor::ensureNotFalse($diamond >= $amount, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            $dao_deposit = new DAODeposit();
            try {
                $dao_deposit->startTrans();
                $orderid = Account::getOrderId($uid);
                Account::decrease(Account::ACTIVE_ACCOUNT15, Account::TRADE_TYPE_AWARD, $orderid, $amount, Account::CURRENCY_DIAMOND, "{$operater}通过{$active_name}($activeid)奖励$amount",  array('activeid'=>$activeid));
                Account::increase($uid, Account::TRADE_TYPE_AWARD, $orderid, $amount, Account::CURRENCY_DIAMOND, "{$operater}通过{$active_name}($activeid)奖励$amount",  array('activeid'=>$activeid));
                $dao_deposit->commit();
            } catch (MySQLException $e) {
                $dao_deposit->rollback();
                throw new BizException(ERROR_SYS_DB_SQL);
            }
        }

        return $orderid;
    }

    public static function systemDepositAwardTicket($activeid, $active_name, $uid, $amount, $operater)
    {
        $account = new Account();
        $diamond = $account->getBalance(Account::ACTIVE_ACCOUNT15, ACCOUNT::CURRENCY_DIAMOND);
        Interceptor::ensureNotEmpty($diamond, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

        $arr = explode(",", $uid);
        if(is_array($arr)) {
            $amounts = count($arr) * $amount;
            Interceptor::ensureNotFalse($diamond >= $amounts, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            $dao_deposit = new DAODeposit();
            try {
                $dao_deposit->startTrans();
                foreach ($arr as $key => $value){
                    $orderid = Account::getOrderId($value);
                    Account::decrease(Account::ACTIVE_ACCOUNT15, Account::TRADE_TYPE_AWARD, $orderid, $amount, Account::CURRENCY_DIAMOND, "{$operater}通过{$active_name}($activeid)奖励票$amount",  array('activeid'=>$activeid));
                    Account::increase($value, Account::TRADE_TYPE_AWARD, $orderid, $amount, Account::CURRENCY_TICKET, "{$operater}通过{$active_name}($activeid)奖励票$amount",  array('activeid'=>$activeid));
                }
                $dao_deposit->commit();
            } catch (MySQLException $e) {
                $dao_deposit->rollback();
                throw new BizException(ERROR_SYS_DB_SQL);
            }

        } else {
            Interceptor::ensureNotFalse($diamond >= $amount, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            $dao_deposit = new DAODeposit();
            try {
                $dao_deposit->startTrans();
                $orderid = Account::getOrderId($uid);
                Account::decrease(Account::ACTIVE_ACCOUNT15, Account::TRADE_TYPE_AWARD, $orderid, $amount, Account::CURRENCY_DIAMOND, "{$operater}通过{$active_name}($activeid)奖励票$amount",  array('activeid'=>$activeid));
                Account::increase($uid, Account::TRADE_TYPE_AWARD, $orderid, $amount, Account::CURRENCY_TICKET, "{$operater}通过{$active_name}($activeid)奖励票$amount",  array('activeid'=>$activeid));
                $dao_deposit->commit();
            } catch (MySQLException $e) {
                $dao_deposit->rollback();
                throw new BizException(ERROR_SYS_DB_SQL);
            }
        }

        return $orderid;
    }

    public static function systemDepositWu($uid, $amount, $operater, $tradeid)
    {
        $account = new Account();
        $diamond = $account->getBalance(Account::ACTIVE_ACCOUNT35, ACCOUNT::CURRENCY_DIAMOND);
        Interceptor::ensureNotEmpty($diamond, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

        $arr = explode(",", $uid);
        if(is_array($arr)) {
            $amounts = count($arr) * $amount;
            Interceptor::ensureNotFalse($diamond >= $amounts, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            $dao_deposit = new DAODeposit();
            try {
                $dao_deposit->startTrans();
                foreach ($arr as $key => $value){
                    $orderid = Account::getOrderId($value);
                    Account::decrease(Account::ACTIVE_ACCOUNT35, Account::TRADE_TYPE_WU, $orderid, $amount, Account::CURRENCY_DIAMOND, "{$operater}给{$uid}转钻$amount, tradeid:$tradeid",  array());
                    Account::increase($value, Account::TRADE_TYPE_WU, $orderid, $amount, Account::CURRENCY_DIAMOND, "{$operater}给{$uid}转钻$amount,tradeid:$tradeid",  array());
                }
                $dao_deposit->commit();
            } catch (MySQLException $e) {
                $dao_deposit->rollback();
                throw new BizException(ERROR_SYS_DB_SQL);
            }

        } else {
            Interceptor::ensureNotFalse($diamond >= $amount, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            $dao_deposit = new DAODeposit();
            try {
                $dao_deposit->startTrans();
                $orderid = Account::getOrderId($uid);
                Account::decrease(Account::ACTIVE_ACCOUNT35, Account::TRADE_TYPE_WU, $orderid, $amount, Account::CURRENCY_DIAMOND, "{$operater}给{$uid}转钻$amount,tradeid:$tradeid",  array());
                Account::increase($uid, Account::TRADE_TYPE_WU, $orderid, $amount, Account::CURRENCY_DIAMOND, "{$operater}给{$uid}转钻$amount,tradeid:$tradeid",  array());
                $dao_deposit->commit();
            } catch (MySQLException $e) {
                $dao_deposit->rollback();
                throw new BizException(ERROR_SYS_DB_SQL);
            }
        }

        //vip
        $deposit_info = array(
        'orderid'=> $orderid,
        'uid'    => $uid,
        'amount' => $amount,
        );

        // 信息改变发送同步到任务后台
        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("vip_consume_add_worker", $deposit_info);


        return $orderid;
    }


}
?>