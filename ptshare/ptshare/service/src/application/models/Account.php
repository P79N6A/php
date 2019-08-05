<?php
class Account
{
    const CURRENCY_GRAPE  			= 1; // 葡萄账户
    const CURRENCY_FREEZE_GRAPE 	= 2; // 冻结葡萄账户
    const CURRENCY_CASH   			= 3; // 代金券账户123
    const CURRENCY_FREEZE_CASH		= 4; // 冻结代金券账户
    const CURRENCY_AWARD_GRAPE      = 5; // 奖励账户

    const TRADE_TYPE_GRAPE_TRANSFER   = 1; // 财务葡萄转账
    const TRADE_TYPE_GRAPE_AWARD      = 2; // 任务激励
    const TRADE_TYPE_CASH_AWARD       = 3; // 现金激励
    const TRADE_TYPE_CASH_TRANSFER    = 4; // 财务现金转账
    const TRADE_TYPE_GRAPE_FREEZE     = 5; // 冻结葡萄账户
    const TRADE_TYPE_GRAPE_UNFREEZE   = 6; // 解冻葡萄账户
    const TRADE_TYPE_GRAPE_DISTRIBUTE = 7; // 财务出账
    const TRADE_TYPE_GRAPE_RECYCLE    = 8; // 财务回收
    const TRADE_TYPE_GRAPE_REDPACKET  = 9; // 红包奖励

    const TRADE_TYPE_GRAPE_FREEZE_TO_SYSTRM   = 10; // 解冻葡萄账户转系统账户
    const TRADE_TYPE_GRAPE_SYSTRM_TO_USER     = 11; // 系统账户转用户葡萄账户
    const TRADE_TYPE_CASH_FREEZE              = 12; // 冻结代金券
    const TRADE_TYPE_CASH_UNFREEZE            = 13; // 解冻代金券
    const TRADE_TYPE_CASH_FREEZE_TO_SYSTRM    = 14; // 冻结代金券账户转系统账户
    const TRADE_TYPE_CASH_SYSTRM_TO_USER      = 15; // 系统账户转用户代金券账户
    const TRADE_TYPE_GRAPE_USER_TO_SYSTRM     = 16; // 用户葡萄账户转系统葡萄账户
    const TRADE_TYPE_CASH_USER_TO_SYSTRM      = 17; // 用户葡萄账户转系统葡萄账户
    const TRADE_TYPE_GRAPE_FOLLOW_AWARD       = 18; // 发放吸粉奖励
    const TRADE_TYPE_GRAPE_LOTTERY_AWARD      = 19; // 彩票发奖
    const TRADE_TYPE_GRAPE_DISCOUNT_TO_SYSTEM = 20; // 折扣账户转系统账户

    const ACCOUNT_GRAPE_SYSTEM    = 10000; // 葡萄系统账户
    const ACCOUNT_GRAPE_AWARD     = 10001; // 葡萄任务账户
    const ACCOUNT_GRAPE_FOREGIFT  = 10002; // 葡萄押金账户
    const ACCOUNT_GRAPE_REDPACKET = 10003; // 葡萄红包账户
    const ACCOUNT_GRAPE_LOTTERY   = 10004; // 葡萄彩票发奖账户
    const ACCOUNT_GRAPE_DISCOUNT  = 10005; // 折扣账户

    const ACCOUNT_CASH_SYSTEM       = 20000; // 代金券系统账户
    const ACCOUNT_CASH_AWARD        = 20001; // 代金券激励账户
    const ACCOUNT_CASH_FOREGIFT     = 20002; // 代金券押金账户

    const ACCOUNT_SYSTEM_RECEIVER 	= 40000; //系统收葡萄账户

    const ACCOUNT_GRAPE_PERI 		= 10000000; //葡萄精账户


    public static function getBalance($uid, $currency, $locked = false)
    {
        $dao_account = new DAOAccount($uid);
        $balance = $dao_account->getBalance($currency, $locked);

        return $balance;
    }

    public static function getAccountList($uid)
    {
        $dao_account = new DAOAccount($uid);
        $account_list = $dao_account->getAccountList();

        $accounts = array(
            "grape" => 0,
            "freeze" => 0,
            "cash" => 0.00
        );

        foreach ($account_list as $key => $value) {
            switch ($value["currency"]) {
                case self::CURRENCY_GRAPE:
                    $accounts["grape"] = intval($value["balance"]);
                    break;
                case self::CURRENCY_FREEZE_GRAPE:
                    $accounts["freeze"] = intval($value["balance"]);
                    break;
                case self::CURRENCY_CASH:
                    $accounts["cash"] = doubleval($value["balance"]);
                    break;
                case self::CURRENCY_FREEZE_CASH:
                	$accounts["freeze_cash"] = doubleval($value["balance"]);
                	break;
            }
        }

        return $accounts;
    }

    // 冻结葡萄(葡萄账户转冻结账户)
    public static function freeze($uid, $amount, $orderid, $remark, $extends = array())
    {
        if ($amount > 0) {
            return self::transfer($uid, $uid, self::TRADE_TYPE_GRAPE_FREEZE, $orderid, $amount, self::CURRENCY_GRAPE, self::CURRENCY_FREEZE_GRAPE, $remark, $extends);
        }
        return true;
    }

    // 解冻葡萄(冻结账户转葡萄账户)
    public static function unfreeze($uid, $amount, $orderid, $remark, $extends = array())
    {
        if ($amount > 0) {
            return self::transfer($uid, $uid, self::TRADE_TYPE_GRAPE_UNFREEZE, $orderid, $amount, self::CURRENCY_FREEZE_GRAPE, self::CURRENCY_GRAPE, $remark, $extends);
        }
        return true;
    }

    // 回收扣除葡萄(冻结账户转系统账户)
    public static function recycleFreeze($uid, $amount, $orderid, $remark, $extends = array())
    {
        if ($amount > 0) {
            return self::transfer($uid, self::ACCOUNT_SYSTEM_RECEIVER, self::TRADE_TYPE_GRAPE_FREEZE_TO_SYSTRM, $orderid, $amount, self::CURRENCY_FREEZE_GRAPE, self::CURRENCY_GRAPE, $remark, $extends);
        }
        return true;
    }

    // 退款葡萄(系统账户转用户账户)
    public static function refundGrape($uid, $amount, $orderid, $remark, $extends = array())
    {
        if ($amount > 0) {
            return self::transfer(self::ACCOUNT_SYSTEM_RECEIVER, $uid, self::TRADE_TYPE_GRAPE_SYSTRM_TO_USER, $orderid, $amount, self::CURRENCY_GRAPE, self::CURRENCY_GRAPE, $remark, $extends);
        }
        return true;
    }

    // 扣除葡萄(用户账户转系统账户)
    public static function deductGrape($uid, $amount, $orderid, $remark, $extends = array())
    {
        if ($amount > 0) {
            return self::transfer($uid,self::ACCOUNT_SYSTEM_RECEIVER, self::TRADE_TYPE_GRAPE_USER_TO_SYSTRM, $orderid, $amount, self::CURRENCY_GRAPE, self::CURRENCY_GRAPE, $remark, $extends);
        }
        return true;
    }

    // 冻结代金券(代金券账户转冻结账户)
    public static function freezeCash($uid, $amount, $orderid, $remark, $extends = array())
    {
        if ($amount > 0) {
            return self::transfer($uid, $uid, self::TRADE_TYPE_CASH_FREEZE, $orderid, $amount, self::CURRENCY_CASH, self::CURRENCY_FREEZE_CASH, $remark, $extends);
        }
        return true;
    }

    // 解冻代金券(冻结账户代金券账户)
    public static function unfreezeCash($uid, $amount, $orderid, $remark, $extends = array())
    {
        if ($amount > 0) {
            return self::transfer($uid, $uid, self::TRADE_TYPE_CASH_UNFREEZE, $orderid, $amount, self::CURRENCY_FREEZE_CASH, self::CURRENCY_CASH, $remark, $extends);
        }
        return true;
    }

    // 回收扣除代金券(冻结账户转系统账户)
    public static function recycleFreezeCash($uid, $amount, $orderid, $remark, $extends = array())
    {
        if ($amount > 0) {
            return self::transfer($uid, self::ACCOUNT_SYSTEM_RECEIVER, self::TRADE_TYPE_CASH_FREEZE_TO_SYSTRM, $orderid, $amount, self::CURRENCY_FREEZE_CASH, self::CURRENCY_CASH, $remark, $extends);
        }
        return true;
    }

    // 退款代金券(系统账户转用户账户)
    public static function refundCash($uid, $amount, $orderid, $remark, $extends = array())
    {
        if ($amount > 0) {
            return self::transfer(self::ACCOUNT_SYSTEM_RECEIVER, $uid, self::TRADE_TYPE_CASH_SYSTRM_TO_USER, $orderid, $amount, self::CURRENCY_CASH, self::CURRENCY_CASH, $remark, $extends);
        }
        return true;
    }

    // 扣除代金券(用户账户转系统账户)
    public static function deductCash($uid, $amount, $orderid, $remark, $extends = array())
    {
        if ($amount > 0) {
            return self::transfer($uid, self::ACCOUNT_SYSTEM_RECEIVER, self::TRADE_TYPE_CASH_USER_TO_SYSTRM, $orderid, $amount, self::CURRENCY_CASH, self::CURRENCY_CASH, $remark, $extends);
        }
        return true;
    }



    //财务从系统账户派发
    public static function distribute($uid, $amount, $remark, $extends=[])
    {
//        Interceptor::ensureNotFalse(in_array($uid, array(self::ACCOUNT_GRAPE_AWARD, self::ACCOUNT_GRAPE_REDPACKET)), ERROR_BIZ_PAYMENT_ACCOUNT_INVALID);

        $orderid = Order::getOrderId();

        try {
            self::transfer(self::ACCOUNT_GRAPE_SYSTEM, $uid, self::TRADE_TYPE_GRAPE_DISTRIBUTE, $orderid, $amount, self::CURRENCY_GRAPE, self::CURRENCY_GRAPE, $remark, $extends);

        } catch (MySQLException $e) {
        	Logger::log('sell_log', 'distribute2',  array("msg" => $e->getMessage(), 'code' => $e->getCode()));
            throw new BizException($e->getMessage());
        }

        return $orderid;
    }

    //财务回收到系统账户
    public static function recycle($uid, $amount, $remark, $extends=[])
    {
        $orderid = Order::getOrderId();

        try {
            $dao_account = new DAOAccount($uid);
            $dao_account->startTrans();

            self::transfer($uid, self::ACCOUNT_GRAPE_SYSTEM, self::TRADE_TYPE_GRAPE_RECYCLE, $orderid, $amount, self::CURRENCY_GRAPE, self::CURRENCY_GRAPE, $remark, $extends);

            $dao_account->commit();
        } catch (MySQLException $e) {
            $dao_account->rollback();
            throw new BizException($e->getMessage());
        }

        return $orderid;
    }

    //往奖励账户转钱
    public static function distributeAward($uid, $amount, $remark, $extends=[])
    {
        $orderid = Order::getOrderId();

        try {
            $dao_account = new DAOAccount($uid);
            $dao_account->startTrans();

            self::transfer($uid, $uid, self::TRADE_TYPE_GRAPE_FOLLOW_AWARD, $orderid, $amount, self::CURRENCY_GRAPE, self::CURRENCY_AWARD_GRAPE, $remark, $extends);

            $dao_account->commit();
        } catch (MySQLException $e) {
            $dao_account->rollback();
            throw new BizException($e->getMessage());
        }

        return $orderid;
    }

    public static function addCashAward($receiver, $amount, $remark, $extends = array())
    {
        $orderid = Order::getOrderId();

        try {
            $dao_account = new DAOAccount($receiver);
            $dao_account->startTrans();

            self::transfer(self::ACCOUNT_CASH_AWARD, $receiver, self::TRADE_TYPE_CASH_AWARD, $orderid, $amount, self::CURRENCY_CASH, self::CURRENCY_CASH, $remark, $extends);

            $dao_account->commit();
        } catch (MySQLException $e) {
            $dao_account->rollback();
            throw new BizException($e->getMessage());
        }

        return $orderid;
    }

    //吸粉红包
    public static function addFollowAward($sender, $receiver, $amount, $remark, $extends = array())
    {
        $orderid = Order::getOrderId();

        try {
            $dao_account = new DAOAccount($receiver);
            $dao_account->startTrans();

            self::transfer($sender, $receiver, self::TRADE_TYPE_GRAPE_FOLLOW_AWARD, $orderid, $amount, self::CURRENCY_AWARD_GRAPE, self::CURRENCY_GRAPE, $remark, $extends);

            $dao_account->commit();
        } catch (MySQLException $e) {
            $dao_account->rollback();
            throw new BizException($e->getMessage());
        }

        return $orderid;
    }

    public static function addGrapeAward($receiver, $amount, $remark, $extends = array())
    {
        $orderid = Order::getOrderId();

        try {
            $dao_account = new DAOAccount($receiver);
            $dao_account->startTrans();

            self::transfer(self::ACCOUNT_GRAPE_AWARD, $receiver, self::TRADE_TYPE_GRAPE_AWARD, $orderid, $amount, self::CURRENCY_GRAPE, self::CURRENCY_GRAPE, $remark, $extends);

            $dao_account->commit();
        } catch (MySQLException $e) {
            $dao_account->rollback();
            throw new BizException($e->getCode(), $e->getMessage());
        }

        return $orderid;
    }

    public static function addGrapeRedPacket($receiver, $amount, $remark, $extends = array())
    {
        $orderid = Order::getOrderId();

        try {
            $dao_account = new DAOAccount($receiver);
            $dao_account->startTrans();

            self::transfer(self::ACCOUNT_GRAPE_REDPACKET, $receiver, self::TRADE_TYPE_GRAPE_REDPACKET, $orderid, $amount, self::CURRENCY_GRAPE, self::CURRENCY_GRAPE, $remark, $extends);

            $dao_account->commit();
        } catch (MySQLException $e) {
            $dao_account->rollback();
            throw new BizException($e->getMessage());
        }

        return $orderid;
    }


    public static function addGrapeLottery($receiver, $amount, $remark, $extends = array())
    {
        $orderid = Order::getOrderId();

        try {
            $dao_account = new DAOAccount($receiver);
            $dao_account->startTrans();

            self::transfer(self::ACCOUNT_GRAPE_LOTTERY, $receiver, self::TRADE_TYPE_GRAPE_LOTTERY_AWARD, $orderid, $amount, self::CURRENCY_GRAPE, self::CURRENCY_GRAPE, $remark, $extends);

            $dao_account->commit();
        } catch (MySQLException $e) {
            $dao_account->rollback();
            throw new BizException($e->getMessage());
        }

        return $orderid;
    }

    // 折扣账户转系统账户
    public static function discountToSystem($orderid, $amount,$remark,$extends = array()){
        try {
            $dao_account = new DAOAccount(self::ACCOUNT_GRAPE_SYSTEM);
            $dao_account->startTrans();

            self::transfer(self::ACCOUNT_GRAPE_DISCOUNT, self::ACCOUNT_GRAPE_SYSTEM, self::TRADE_TYPE_GRAPE_DISCOUNT_TO_SYSTEM, $orderid, $amount, self::CURRENCY_GRAPE, self::CURRENCY_GRAPE, $remark, $extends);

            $dao_account->commit();
        } catch (MySQLException $e) {
            $dao_account->rollback();
            throw new BizException($e->getMessage());
        }

        return $orderid;
    }

    public static function transfer($sender, $receiver, $type, $orderid, $amount, $sender_currency, $receiver_currency, $remark, $extends)
    {
        self::decrease($sender, $type, $orderid, $amount, $sender_currency, $remark, $extends);
        self::increase($receiver, $type, $orderid, $amount, $receiver_currency, $remark, $extends);

        return true;
    }

    /**
     * 账户金额增加
     *
     * @param int $uid         目标用户
     * @param int $type        账变类型
     * @param int $orderid     相关订单号
     * @param double $amount   账变金额
     * @param string $currency 交易币种 1 葡萄账户 2 冻结账户 3 代金券账户
     * @param string $remark   交易详情文字方式描述
     * @param array $extends   交易详情数组形式 交易现场快照
     * @return 当前余额
     */
    private static function increase($uid, $type, $orderid, $amount, $currency, $remark, $extends = array())
    {
        $dao_account = new DAOAccount($uid);
        $balance = $dao_account->getBalance($currency);

        $extends['before'] = $balance;
        $extends['after'] = $balance + $amount;

        $dao_account->insert($uid, $currency, $amount);

        $dao_journal = new DAOJournal($uid);
        $dao_journal->add($orderid, $uid, $type, 'IN', $currency, $amount, $remark, $extends);

        $balance += $amount;

        return $balance;
    }

    /**
     * 账户金额减少
     *
     * @param int $uid         目标用户
     * @param int $type        账变类型
     * @param int $orderid     相关订单号
     * @param double $amount   账变金额
     * @param string $currency 交易币种 1 葡萄账户 2 冻结账户 3 代金券账户
     * @param string $remark   交易详情文字方式描述
     * @param array $extends   交易详情数组形式 交易现场快照
     * @return 当前余额
     */
    private static function decrease($uid, $type, $orderid, $amount, $currency, $remark, $extends = array())
    {
        $dao_account = new DAOAccount($uid);
        $balance = $dao_account->getBalance($currency);

        Interceptor::ensureNotFalse($dao_account->decrease($uid, $currency, $amount), ERROR_BIZ_PAYMENT_ACCOUNT_BALANCE_LACK);

        $extends['before'] = $balance;
        $extends['after'] = $balance - $amount;

        $dao_journal = new DAOJournal($uid);
        $dao_journal->add($orderid, $uid, $type, 'OUT', $currency, $amount, $remark, $extends);

        $balance -= $amount;

        return $balance;
    }
}
?>
