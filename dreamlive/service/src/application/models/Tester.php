<?php
class Tester
{
    const COMAPNY_ACCOUNT = 1000; //系统收钱账户
    const ACTIVE_ACCOUNT8 = 1008; //测试号中转帐号
    public static function transfer($uid, $deposit_num, $remark)
    {
        return AccountInterface::plus($uid, Tester::ACTIVE_ACCOUNT8, Account::TRADE_TYPE_TESTER, $deposit_num, Account::CURRENCY_DIAMOND, $remark, array('uid'=>$uid, 'num'=>$deposit_num, 'remark'=>$remark));
    }
}
?>