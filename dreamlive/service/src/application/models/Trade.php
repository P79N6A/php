<?php
class Trade
{
    const TRADE_TYPE_DEPOSIT = 1; //充值
    const TRADE_TYPE_WITHDWAW = 2; //提现
    const TRADE_TYPE_GIFT = 3; //送礼
    const TRADE_TYPE_TICKET_TO_DIAMOND = 4; //票买钻
    const TRADE_TYPE_RED_PACKET = 5; //红包
    const TRADE_TYPE_SYSTERM_TOOL = 6; //系统道具
    const SYSTEM_ACCOUNT_ID = 100000; //系统账户

    public function add($orderid, $uid, $receiver, $type, $direct, $currency, $amount, $num, $remark, $extends = array(), $relateid = 0, $liveid = 0)
    {
        $dao_trade = new DAOTrade();
        return $dao_trade->add($orderid, $uid, $receiver, $type, $direct, $currency, $amount, $num, $remark, $extends, $relateid, $liveid);
    }

    public static function getOrderId($uid)
    {
        return date('ymdHis') . sprintf("%05d", substr($uid, -5)) . rand(100, 999);
    }

}
