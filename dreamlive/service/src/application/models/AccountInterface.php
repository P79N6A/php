<?php
class AccountInterface extends Account
{
    public static function minus($uid, $systemid, $type, $num, $currency, $remark, $extends = array(), $orderid='')
    {
        /*{{{守护入钻帐号1003*/

        Interceptor::ensureNotEmpty($num, ERROR_PARAM_IS_EMPTY, 'num');
        Interceptor::ensureNotFalse($num > 0, ERROR_PARAM_INVALID_FORMAT, "num");
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotFalse($systemid < 10000, ERROR_PARAM_INVALID_FORMAT, "systemid");
        Interceptor::ensureNotEmpty($type, ERROR_PARAM_IS_EMPTY, "type");
        Interceptor::ensureNotEmpty($remark, ERROR_PARAM_IS_EMPTY, "remark");
        Interceptor::ensureNotEmpty($extends, ERROR_PARAM_IS_EMPTY, "extends");

        $currency = Account::CURRENCY_DIAMOND; //保留此输入， 只允许钻转钻

        $user = new User();
        $userinfo = $user->getUserInfo($uid);
        Interceptor::ensureNotFalse(isset($userinfo['uid']), ERROR_USER_NOT_EXIST);

        $dao_profile = new DAOProfile($uid);
        $profiles = $dao_profile->getUserProfiles();
        Interceptor::ensureNotFalse((!(isset($profiles['frozen']) && $profiles['frozen'] == 'Y')), ERROR_BIZ_GIFT_FROZEN, "uid");

        $dao_gift_log = new DAOGiftLog();
        try {
            $dao_gift_log->startTrans();
            $diamond = self::getBalance($uid, $currency);
            Interceptor::ensureNotEmpty($diamond, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse($diamond>=$num, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

            if(!$orderid) {
                $orderid = self::getOrderId($uid);
            }

            Interceptor::ensureNotFalse(self::decrease($uid, $type, $orderid, $num, $currency, $remark, $extends),  ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse(self::increase($systemid, $type, $orderid, $num, $currency, $remark, $extends), ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

            $dao_gift_log->commit();
        } catch (Exception $e) {
            $dao_gift_log->rollback();
            throw $e;
        }

        return $orderid;
    }

    public static function plus($uid, $systemid, $type, $num, $currency, $remark, $extends = array(), $orderid='')
    {
        /*{{{守护入钻帐号1003 退还*/
        Interceptor::ensureNotEmpty($num, ERROR_PARAM_IS_EMPTY, 'num');
        Interceptor::ensureNotFalse($num > 0, ERROR_PARAM_INVALID_FORMAT, "num");
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotFalse($systemid < 10000, ERROR_PARAM_INVALID_FORMAT, "systemid");
        Interceptor::ensureNotEmpty($type, ERROR_PARAM_IS_EMPTY, "type");
        Interceptor::ensureNotEmpty($remark, ERROR_PARAM_IS_EMPTY, "remark");
        Interceptor::ensureNotEmpty($extends, ERROR_PARAM_IS_EMPTY, "extends");

        $currency = Account::CURRENCY_DIAMOND; //保留此输入， 只允许钻转钻

        $user = new User();
        $userinfo = $user->getUserInfo($uid);
        Interceptor::ensureNotFalse(isset($userinfo['uid']), ERROR_USER_NOT_EXIST);

        $dao_gift_log = new DAOGiftLog();
        try {
            $dao_gift_log->startTrans();
            $diamond = self::getBalance($systemid, $currency);
            Interceptor::ensureNotEmpty($diamond, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse($diamond>=$num, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

            if(!$orderid) {
                $orderid = self::getOrderId($uid);
            }
            Interceptor::ensureNotFalse(self::decrease($systemid, $type, $orderid, $num, $currency, $remark, $extends),  ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse(self::increase($uid, $type, $orderid, $num, $currency, $remark, $extends), ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

            $dao_gift_log->commit();
        } catch (Exception $e) {
            $dao_gift_log->rollback();
            throw $e;
        }

        return $orderid;
    }

    /**
     * 找回守护的票子，并调整榜单
     */
    public static function getBackGuard($sender,$receiver,$amount,$orderid,$liveid=0)
    {
        if (!$sender) { throw new Exception("sender is null");
        }
        if (!$receiver) { throw new Exception("receiver is null");
        }
        if (!$orderid) { throw new Exception("orderid is null");
        }
        if (!$amount||$amount<=0) { throw new Exception("amount is err");
        }
        $account103=self::ACTIVE_ACCOUNT3;
        $type=self::TRADE_TYPE_GUARD_BACK;//守护追钻加票，调整类型
        $giftlog_dao=new DAOGiftLog();
        try{
            $giftlog_dao->startTrans();
            //记录日志。
            $flog=dirname(__FILE__)."/getguardback.log";
            if (!file_exists($flog)) {
                touch($flog);
            }
            $data=file_get_contents($flog);
            if ($data) {
                $dataJson=json_decode($data, true);
                if (in_array($orderid, $dataJson)) {
                    throw new Exception();
                }

            }else{
                file_put_contents($flog, json_encode(array()));
            }
            $diamond = self::getBalance($account103, Account::CURRENCY_DIAMOND);
            Interceptor::ensureNotEmpty($diamond, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse($diamond>=$amount, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            //从1003扣除钻石，
            //向uid加票
            $orderid = self::getOrderId($sender);
            Interceptor::ensureNotFalse(self::decrease($account103, $type, $orderid, $amount, Account::CURRENCY_DIAMOND, "uid=".$account103."守护找回扣除钻amount=".$amount, ['uid'=>$account103,"amount"=>$amount]),  ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse(self::increase($receiver, $type, $orderid, $amount, Account::CURRENCY_TICKET, "uid=".$receiver."守护找回增加票子amount=".$amount, ['uid'=>$receiver,"amount"=>$amount]), ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            //调整榜单
            Counter::increase(Counter::COUNTER_TYPE_PAYMENT_RECEIVE_GIFT, $receiver, $amount);//收礼计数器
            if($liveid) {
                Counter::increase(Counter::COUNTER_TYPE_LIVE_TICKET, $liveid, $amount);//直播间计数器
            }
            Counter::increase(Counter::COUNTER_TYPE_PAYMENT_SEND_GIFT, $sender, $amount);//送礼计数器
            $rank=new Rank();
            $rank->setRank("sendgift", "increase", $sender, $amount);//送礼榜
            $rank->setRank("receivegift", "increase", $receiver, $amount);//收礼榜

            $giftlog_dao->commit();

            $data1=file_get_contents($flog);
            if ($data1) {
                $dataJson1 = json_decode($data1, true);
                array_push($dataJson1, $orderid);
                file_put_contents($flog, json_encode($dataJson1));
            }
            return true;
        }catch (Exception $e){
            $giftlog_dao->rollback();
            return false;
        }

    }




    public static function minusGift($uid, $receiver, $type, $num, $currency, $remark, $extends = array(), $orderid='')
    {
        /*{{{守护入票守护人*/

        Interceptor::ensureNotEmpty($num, ERROR_PARAM_IS_EMPTY, 'num');
        Interceptor::ensureNotFalse($num > 0, ERROR_PARAM_INVALID_FORMAT, "num");
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotFalse($receiver  > 0, ERROR_PARAM_INVALID_FORMAT, "receiver");
        Interceptor::ensureNotEmpty($type, ERROR_PARAM_IS_EMPTY, "type");
        Interceptor::ensureNotEmpty($remark, ERROR_PARAM_IS_EMPTY, "remark");
        Interceptor::ensureNotEmpty($extends, ERROR_PARAM_IS_EMPTY, "extends");

        $user = new User();
        $userinfo = $user->getUserInfo($uid);
        Interceptor::ensureNotFalse(isset($userinfo['uid']), ERROR_USER_NOT_EXIST);

        $dao_profile = new DAOProfile($uid);
        $profiles = $dao_profile->getUserProfiles();
        Interceptor::ensureNotFalse((!(isset($profiles['frozen']) && $profiles['frozen'] == 'Y')), ERROR_BIZ_GIFT_FROZEN, "uid");

        $dao_gift_log = new DAOGiftLog();
        try {
            $dao_gift_log->startTrans();
            $diamond = self::getBalance($uid, Account::CURRENCY_DIAMOND);
            Interceptor::ensureNotEmpty($diamond, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse($diamond>=$num, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

            if(!$orderid) {
                $orderid = self::getOrderId($uid);
            }
            if($type == 30 || $type == 31) {
                Interceptor::ensureNotFalse(self::decrease($uid, $type, $orderid, $num, Account::CURRENCY_DIAMOND, $remark, $extends),  ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
                $aver = ceil($num/2); //分成两个部分, 一部分进接收人, 一倍分是1009
                $sum_aver = $num-$aver;
                Interceptor::ensureNotFalse(self::increase($receiver, $type, $orderid, $aver, Account::CURRENCY_TICKET, $remark, $extends), ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
                Interceptor::ensureNotFalse(self::increase(Account::ACTIVE_ACCOUNT9, $type, $orderid, $sum_aver, Account::CURRENCY_TICKET, $remark, $extends), ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            } else { 
                Interceptor::ensureNotFalse(self::decrease($uid, $type, $orderid, $num, Account::CURRENCY_DIAMOND, $remark, $extends),  ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
                Interceptor::ensureNotFalse(self::increase($receiver, $type, $orderid, $num, Account::CURRENCY_TICKET, $remark, $extends), ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            }

            $dao_gift_log->commit();
        } catch (Exception $e) {
            $dao_gift_log->rollback();
            throw $e;
        }

        return $orderid;
    }

    public static function plusGift($uid, $receiver, $type, $num, $currency, $remark, $extends = array(), $orderid='')
    {
        /*{{{守护入票守护人 退还*/
        Interceptor::ensureNotEmpty($num, ERROR_PARAM_IS_EMPTY, 'num');
        Interceptor::ensureNotFalse($num > 0, ERROR_PARAM_INVALID_FORMAT, "num");
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotFalse($receiver  > 0, ERROR_PARAM_INVALID_FORMAT, "receiver");
        Interceptor::ensureNotEmpty($type, ERROR_PARAM_IS_EMPTY, "type");
        Interceptor::ensureNotEmpty($remark, ERROR_PARAM_IS_EMPTY, "remark");
        Interceptor::ensureNotEmpty($extends, ERROR_PARAM_IS_EMPTY, "extends");

        $user = new User();
        $userinfo = $user->getUserInfo($uid);
        Interceptor::ensureNotFalse(isset($userinfo['uid']), ERROR_USER_NOT_EXIST);

        $dao_gift_log = new DAOGiftLog();
        try {
            $dao_gift_log->startTrans();
            $ticket = self::getBalance($receiver, Account::CURRENCY_TICKET);
            Interceptor::ensureNotEmpty($ticket, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse($ticket>=$num, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

            if(!$orderid) {
                $orderid = self::getOrderId($uid);
            }
            Interceptor::ensureNotFalse(self::decrease($receiver, $type, $orderid, $num, Account::CURRENCY_TICKET, $remark, $extends),  ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse(self::increase($uid, $type, $orderid, $num, Account::CURRENCY_DIAMOND, $remark, $extends), ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

            $dao_gift_log->commit();
        } catch (Exception $e) {
            $dao_gift_log->rollback();
            throw $e;
        }

        return $orderid;
    }

    //同币种业务转账
    private static function sameCurrencyTransferAccounts($fromUid,$toUid,$amount,$currency,$tradeType,$remark='',$ext=array())
    {
        $account = new Account();
        //$daoAccount=new DAOAccount($fromUid);
        $daoTrack=new DAOTrack();
        try {
            $daoTrack->startTrans();
            $balance = $account->getBalance($fromUid, $currency);
            Interceptor::ensureNotEmpty($balance, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse($balance>=$amount, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            $orderid = $account->getOrderId();

            Interceptor::ensureNotFalse($account->decrease($fromUid, $tradeType, $orderid, $amount, $currency, $remark, $ext),  ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse($account->increase($toUid, $tradeType, $orderid, $amount, $currency, $remark, $ext), ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            
            $daoTrack->commit();
        } catch (Exception $e) {
            $daoTrack->rollback();
            throw $e;
        }
    }

    //补五一活动钻
    public static function wuyiAct($uid,$amount,$preRemaark='')
    {
        if ($amount<=0) { return;
        }
        $type=Account::TRADE_TYPE_DO_TASK;
        $sys=1005;
        $currency=Account::CURRENCY_DIAMOND;
        $remark=($preRemaark?$preRemaark:"送钻活动")."，from [$sys] to [$uid] amount($amount) type[$type]";
        self::sameCurrencyTransferAccounts($sys, $uid, $amount, $currency, $type, $remark);
    }

    //大转盘游戏转账星光星钻
    public static function lottoGameSendMoney($toUid,$amount,$currency,$remark='',$ext=array())
    {
        $tradeType=Account::TRADE_TYPE_GAME_LOTTO;
        $fromUid=Account::ACTIVE_ACCOUNT1300;
        return self::sameCurrencyTransferAccounts($fromUid, $toUid, $amount, $currency, $tradeType, $remark, $ext);
    }

    //大转盘游戏收费
    public static function lottoGamePayment($fromUid,$amount,$remark='',$ext=array())
    {
        $tradeType=Account::TRADE_TYPE_GAME_LOTTO;
        $toUid=Account::ACTIVE_ACCOUNT1300;
        return self::sameCurrencyTransferAccounts($fromUid, $toUid, $amount, Account::CURRENCY_DIAMOND, $tradeType, $remark, $ext);
    }

    //直播记录 收礼统计
    public static function getAnchorIncomeByDate($uid,$sday,$eday)
    {
        $result=[];
        if ($uid<=0) { return $result;
        }
        if (!$sday||!$eday) { return $result;
        }
        if (strtotime($sday)>strtotime($eday)) { return $result;
        }

        //$sday.=' 00:00:00';
        //$eday.=' 23:59:59';

        $daoJournal=new DAOJournal($uid);
        $re=$daoJournal->getUserIncomeByTypeInDate($uid, $sday, $eday);

        $gifts=0;
        $tickets=0;
        $replays=0;
        $guards=0;
        $all=0;
        foreach ($re as $i){
            if ($i['type']==Account::TRADE_TYPE_GIFT||$i['type']==Account::TRADE_TYPE_BAG_GIFT_SEND) {
                $gifts+=$i['num'];
                $all+=$i['num'];
            }elseif ($i['type']==Account::TRADE_TYPE_DOOR_TICKET) {
                $tickets+=$i['num'];
                $all+=$i['num'];
            }elseif ($i['type']==Account::TRADE_TYPE_DOOR_TICKET_VIDEO) {
                $replays+=$i['num'];
                $all+=$i['num'];
            }elseif ($i['type']==Account::TRADE_TYPE_GUARD) {
                $guards+=$i['num'];
                $all+=$i['num'];
            }else{
                continue;
            }
        }
        $result=array(
        'gifts'=>$gifts,
        'tickets'=>$tickets,
        'replays'=>$replays,
        'guards'=>$guards,
        'all'=>$all,
        );
        return $result;
    }


    public static function recall($userid)
    {
        /*{{{守护入钻帐号1003 退还*/
        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");

        $user = new User();
        $userinfo = $user->getUserInfo($userid);
        Interceptor::ensureNotFalse(isset($userinfo['uid']), ERROR_USER_NOT_EXIST);

        $send_diamond = 10;

        $account = new Account();
        $dao_gift_log = new DAOGiftLog();
        try {
            $dao_gift_log->startTrans();

            $diamond = $account->getBalance(1014, ACCOUNT::CURRENCY_DIAMOND);
            Interceptor::ensureNotEmpty($diamond, ERROR_CUSTOM, "活动已结束");
            Interceptor::ensureNotFalse($diamond>=$send_diamond, ERROR_CUSTOM, "活动已结束");

            $orderid = Account::getOrderId(1014);
            Interceptor::ensureNotFalse($account->decrease(1014, ACCOUNT::TRADE_TYPE_DO_TASK, $orderid, $send_diamond, ACCOUNT::CURRENCY_DIAMOND, $userid . "用户回call, 加钻$send_diamond", array()),  ERROR_CUSTOM, "活动已结束");
            Interceptor::ensureNotFalse($account->increase($userid, ACCOUNT::TRADE_TYPE_DO_TASK, $orderid, $send_diamond, ACCOUNT::CURRENCY_DIAMOND, userid . "用户回call, , 加钻$send_diamond", array()), ERROR_CUSTOM, "活动已结束");

            $dao_gift_log->commit();
        } catch (Exception $e) {
            $dao_gift_log->rollback();
            throw $e;
        }

        return $orderid;
    }

    public function keng($uid, $num, $remark)
    {
        /*{{{回收钻 出票到公司帐户钻*/
        Interceptor::ensureNotEmpty($num, ERROR_PARAM_IS_EMPTY, 'num');
        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, 'uid');
        Interceptor::ensureNotFalse($uid > 10000, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotEmpty($num>0, ERROR_PARAM_IS_EMPTY, 'num');
        Interceptor::ensureNotEmpty($remark, ERROR_PARAM_IS_EMPTY, 'remark');

        $user = new User();
        $userinfo = $user->getUserInfo($uid);
        Interceptor::ensureNotFalse(isset($userinfo['uid']), ERROR_USER_NOT_EXIST);


        $account = new Account();
        $dao_gift_log = new DAOGiftLog();
        try {
            $dao_gift_log->startTrans();

            $ticket = $account->getBalance($uid, ACCOUNT::CURRENCY_TICKET);
            Interceptor::ensureNotEmpty($ticket, ERROR_BIZ_PAYMENT_TICKET_BALANCE_DUE);
            Interceptor::ensureNotFalse($ticket>=$num, ERROR_BIZ_PAYMENT_TICKET_BALANCE_DUE);


            $orderid = Account::getOrderId($uid);
            Interceptor::ensureNotFalse($account->decrease($uid, ACCOUNT::TRADE_TYPE_RECOVERY, $orderid, $num, ACCOUNT::CURRENCY_TICKET, "扣票(被回收人:$uid.原因:$remark.回收数量:$num.)", array()),  ERROR_BIZ_PAYMENT_TICKET_BALANCE_DUE);
            Interceptor::ensureNotFalse($account->increase(ACCOUNT::RECOVERY_ACCOUNT, ACCOUNT::TRADE_TYPE_RECOVERY, $orderid, $num, ACCOUNT::CURRENCY_TICKET, "扣票(被回收人:$uid.原因:$remark.回收数量:$num.)", array()), ERROR_BIZ_PAYMENT_TICKET_BALANCE_DUE);

            $dao_gift_log->commit();
        } catch (Exception $e) {
            $dao_gift_log->rollback();
            throw $e;
        }


        //$this->render();
    }/*}}}*/
}
?>
