<?php
class Account
{
    const CURRENCY_DIAMOND = 2; //钻
    const CURRENCY_TICKET  = 1; //票
    const CURRENCY_CASH    = 3; //现金
    const CURRENCY_COIN    = 4; //星光
    const CURRENCY_STAR    = 5; //星星

    const COMAPNY_ACCOUNT = 1000; //系统收钱账户
    const DAMAGE_ACCOUNT  = 1001; //折损账户    
    const ACTIVE_ACCOUNT2 = 1002; //运营币
    const ACTIVE_ACCOUNT3 = 1003; //守护系统号
    const ACTIVE_ACCOUNT4 = 1004; //注册送钻, 运营帐号.
    const ACTIVE_ACCOUNT5 = 1005; //任务活动总帐户
    const ACTIVE_ACCOUNT6 = 1006; //抽奖送钻
    const ACTIVE_ACCOUNT7 = 1007; //游戏机器人下注用户
    const ACTIVE_ACCOUNT8 = 1008; //测试号中转帐号
    const ACTIVE_ACCOUNT9 = 1009; //门票
    const RECOVERY_ACCOUNT = 1010; //回收帐户. 用于惩罚

    const ACTIVE_ACCOUNT15 = 1015; //活动奖励, 奖钻, 将星星
    const ACTIVE_ACCOUNT25 = 1025; //活动币
    const ACTIVE_ACCOUNT35 = 1035; //吴总币
    const ACTIVE_ACCOUNT1115 = 1115; //vip送座驾

    const ACTIVE_ACCOUNT1100 = 1100; //合作方帐户金币, 百盈足球专用. 星钻和金币之间的转化比率为1：10.
    const ACTIVE_ACCOUNT1300 = 1300; //游戏大转盘, 需运营出星光, 出星钻的帐户. 钻由运营申请， 转入


    const ACTIVE_ACCOUNT1800 = 1800; //红包接收帐号00到99
    const ACTIVE_ACCOUNT18xx = 1850; //中间帐号00-99
    const ACTIVE_ACCOUNT1899 = 1899; //红包接收帐号00到99

    const ACTIVE_ACCOUNT1900 = 1900; //跑马游戏中间帐户, 单独一个.
    const ACTIVE_ACCOUNT19xx = 1950; //游戏中间备用帐号
    const ACTIVE_ACCOUNT1999 = 1999; //游戏中间备用帐号0-99

    const ACTIVE_ACCOUNT2000 = 2000; //系统星光总帐号

    const ACTIVE_ACCOUNT3000 = 3000; //游戏总帐号, 游戏的收入钻, 星光

    const ANSWER_ACCOUNT = 4000; //答题帐号

    const ACTIVE_ACCOUNT7000 = 7000; //运营后台出1钻礼物的后台控制
    const ACTIVE_ACCOUNT7001 = 7001; //背包赠送2017/12/18, 为保留存, 运营送的可达ya. 

    const ACTIVE_ACCOUNT8000 = 8000; //商城总帐号, 用于商城的收入
    

    const TRADE_TYPE_DEPOSIT           = 1; //充值
    const TRADE_TYPE_WITHDWAW          = 2; //提现
    const TRADE_TYPE_GIFT              = 3; //送礼
    const TRADE_TYPE_TICKET_TO_DIAMOND = 4; //票买钻
    const TRADE_TYPE_RED_PACKET        = 5; //红包
    const TRADE_TYPE_SYSTERM_TOOL      = 6; //系统道具

    const TRADE_TYPE_FROZEN_TICKET       = 8; //旧系统冻结， 运营冻结, 就是出了一笔票，不再给了. 出票的一种类型
    const TRADE_TYPE_ACCOUNT_MERGE_ICKET = 9; //3.1到3.20.16点为平多的票, 增加的记录.
    const TRADE_TYPE_ACCOUNT_MERGE       = 10; //账户合并 新老账户
    const TRADE_TYPE_ACCOUNT_COUNT       = 11; //账户合并 新老账户

    const TRADE_TYPE_DO_TASK             = 12;//做送钻任务 1004注册送钻
    const TRADE_TYPE_DO_ACTIVE           = 13;//活动投钻 从用户投到公司帐号 10个钻
    const TRADE_TYPE_PRODUCT_ORDER       = 14;//产品购买, 比如购买faceu等产品系统
    const TRADE_TYPE_ACCOUNT_TRANS       = 15;//系统帐户之间进行转帐
    const TRADE_TYPE_DO_ACTIVE_LOTTERY   = 16;//活动抽奖, 从系统1006到用户帐号
    const TRADE_TYPE_GUARD               = 17;//守护
    const TRADE_TYPE_GAME_RUN            = 18;//游戏跑马
    const TRADE_TYPE_GAME_LOTTO          = 181;//游戏大转盘

    const TRADE_TYPE_INTERNALECONOMIC    = 19;//运营币
    const TRADE_TYPE_ACTIVE              = 49;//活动币
    const TRADE_TYPE_AWARD               = 59;//奖励币
    const TRADE_TYPE_WU                  = 69;//吴总币

    const TRADE_TYPE_COIN_GIFT           = 20;//星星购买产品
    const TRADE_TYPE_STAR_TO_DIAMOND     = 24;//星星买星钻

    const TRADE_TYPE_DOOR_TICKET         = 30;//门票
    const TRADE_TYPE_DOOR_TICKET_VIDEO   = 31;//私密回放

    const TRADE_TYPE_TESTER              = 40;//测试号
    const TRADE_TYPE_RECOVERY            = 41;//回收
    const TRADE_TYPE_RECOVERY_FAMILY     = 4101;//家族月结单向回收家族内人员的票
    const TRADE_TYPE_DIAMOND_TO_COIN     = 42;//星钻买星光
    const TRADE_TYPE_GUARD_BACK          = 43;//收回守护的钻，给主播加票
    const TRADE_TYPE_STAR_TRANSFER       = 45;//系统帐号2000转星光帐号2000
    const TRADE_TYPE_BAIYING_GOLD        = 46;//星钻转百ying金币

    const TRADE_TYPE_BAG_GIFT            = 50;//背包礼物
    const TRADE_TYPE_FAMILY              = 51;//家族处理
    const TRADE_TYPE_BAG_GIFT_7000       = 52;//7000扣背包礼物给送礼
    const TRADE_TYPE_BAG_GIFT_SEND       = 53;//送礼者从扣钻给主播

    const TRADE_TYPE_ANSWER              = 60;//答题

    const TRADE_TYPE_VIP_SEND_RIDE       = 81;//商城vip送座驾对应帐号1115


    //账户翻译
    public static $ACCOUNT_TYPE_KEY = array(
            1     => "ticket",
            2     => "diamond",
            3    => "cash",
            4   => "coin",//星光
    5   => "star",//星星
    );

    //票转钻列表
    public static $ACCOUNT_TRANSFER_LIST = array(
            10 => 6,
            100 => 64,
            300 => 192,
            1000 => 640,
            3000 => 1920,
    );

    public static function getOrderId($uid='')
    {
        return SnowFlake::nextId();
        //return date('ymdHis') . sprintf("%05d", substr($uid, -5)) . rand(100, 999);
    }


    public function getTransferList($uid)
    {
        $list = array();
        $ticket_balace = $this->getBalance($uid, Account::CURRENCY_TICKET);

        foreach (Account::$ACCOUNT_TRANSFER_LIST AS $key => $val) {
            $bool = 0;
            if (!empty($ticket_balace) && $ticket_balace >= $key) {
                $bool = 1;
            }
            $item = array(
            'diamond'     => $val,
            'ticket'    => $key,
            'is_convert'=> $bool
            );
            $list[] = $item;
        }

        return $list;
    }

    public static function getBalance($uid, $currency, $locked=false)
    {
        /* {{{ 获取币种余额*/
        $dao_account = new DAOAccount($uid);
        $balance = $dao_account->getBalance($currency, $locked);

        return $balance;
    }/* }}} */

    public  function getAccountList($uid)
    {
        /* {{{ 获取各种币种余额*/
        $dao_account = new DAOAccount($uid);
        $list = $dao_account->getAccountList();

        $account = array();
        $account = array_flip(self::$ACCOUNT_TYPE_KEY);
        array_walk(
            $account, function (&$value, $key) {
                $value = 0;
            }
        );

        if ($list) {
            foreach ($list as $k => $v) {
                $account[self::$ACCOUNT_TYPE_KEY[$v["currency"]]] = intval($v["balance"]);
            }
        }

        return $account;
    }/* }}} */

    public function frozen($uid)
    {
        /* {{{ 冻结*/

        try {
            $dao_profile = new DAOProfile($uid);
            $dao_profile->modProfile(array("item" => "frozen","value" => "Y"));

            Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $uid, "你的帐号被冻结", "你的帐号被冻结", $uid);

        } catch (Exception $e) {
            throw new BizException($e->getMessage());
        }

        return true;
    }/* }}} */

    public function unFrozen($uid)
    {
        /* {{{ 解冻*/
        try {
            $dao_profile = new DAOProfile($uid);
            $dao_profile->modProfile(array("item" => "frozen","value" => "N"));

            Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $uid, "你的帐号已解除冻结", "你的帐号已解除冻结", $uid);

        } catch (Exception $e) {
            throw new BizException($e->getMessage());
        }

        return true;
    }/* }}} */

    public function frozenTicket($uid)
    {
        /* {{{ 冻结*/

        try {
            $dao_profile = new DAOProfile($uid);
            $dao_profile->modProfile(array("item" => "frozenTicket","value" => "Y"));

            Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $uid, "你的帐号-[票]被冻结", "你的帐号-[票]被冻结", $uid);

        } catch (Exception $e) {
            throw new BizException($e->getMessage());
        }

        return true;
    }/* }}} */

    public function unFrozenTicket($uid)
    {
        /* {{{ 解冻*/
        try {
            $dao_profile = new DAOProfile($uid);
            $dao_profile->modProfile(array("item" => "frozenTicket","value" => "N"));

            Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $uid, "你的帐号-[票]已解除冻结", "你的帐号-[票]已解除冻结", $uid);

        } catch (Exception $e) {
            throw new BizException($e->getMessage());
        }

        return true;
    }/* }}} */

    /**
     * 账户金额增加
     *
     * @param  int    $uid      目标用户
     * @param  int    $type     账变类型
     * @param  int    $orderid  相关订单号
     * @param  double $amount   账变金额
     * @param  string $currency 交易币种 1代表ticket星票,2代表diamond星钻,3代表cash现金,4代表coin金币(星光)，5代表星星
     * @param  string $remark   交易详情文字方式描述
     * @param  array  $extends  交易详情数组形式
     *                          交易现场快照     
     * @return balance
     */
    public static function increase($uid, $type, $orderid, $amount, $currency, $remark, $extends = array())
    {
        /* {{{ 增加余额*/
        $dao_account = new DAOAccount($uid);
        $balance = $dao_account->getBalance($currency);

        //之前之后的总额
        $extends['balance_before'] =  $balance;
        $extends['balance_after']  =  $balance + $amount;


        $dao_account->insert($uid, $currency, $amount);
        if($currency == 4 || $currency == 5) {
            $dao_journal = new DAOStarJournal($uid);
            $dao_journal->add($orderid, $type, 'IN', $currency, $amount, $remark, $extends);
        } else {
            $dao_journal = new DAOJournal($uid);
            $dao_journal->add($orderid, $uid, $type, 'IN', $currency, $amount, $remark, $extends);
        }
        $balance += $amount;

        return $balance;
    }/* }}} */

    /**
     * 账户金额减少
     *
     * @param  int    $uid      目标用户
     * @param  int    $type     账变类型
     * @param  int    $orderid  相关订单号
     * @param  double $amount   账变金额
     * @param  string $currency 交易币种 1代表ticket星票,2代表diamond星钻,3代表cash现金,4代表coin金币
     * @param  string $remark   交易详情文字方式描述
     * @param  array  $extends  交易详情数组形式
     *                          交易现场快照     
     * @return balance
     */
    public static function decrease($uid, $type, $orderid, $amount, $currency, $remark, $extends = array())
    {
        /* {{{减少余额 */
        $dao_account = new DAOAccount($uid);
        $balance = $dao_account->getBalance($currency);

        Interceptor::ensureNotFalse($dao_account->decrease($uid, $currency, $amount), ERROR_BIZ_PAYMENT_ACCOUNT_TICKET_LACK, self::$ACCOUNT_TYPE_KEY[$currency]);
        if ($currency == self::CURRENCY_DIAMOND && $uid > 10000000 && in_array($type, [Account::TRADE_TYPE_GIFT, Account::TRADE_TYPE_SYSTERM_TOOL, Account::TRADE_TYPE_GUARD, Account::TRADE_TYPE_DOOR_TICKET])) {
            Counter::increase(Counter::COUNTER_TYPE_CONSUME_MONEY, $uid, $amount);//用户消费金额数计数器(每个用户都有一个)
        }

        //之前之后的总额
        $extends['balance_before'] =  $balance;
        $extends['balance_after']  =  $balance - $amount;

        if($currency == 4 || $currency == 5) {
            $dao_journal = new DAOStarJournal($uid);
            $dao_journal->add($orderid, $type, 'OUT', $currency, $amount, $remark, $extends);
        } else {
            $dao_journal = new DAOJournal($uid);
            $dao_journal->add($orderid, $uid, $type, 'OUT', $currency, $amount, $remark, $extends);
        }

        $balance -= $amount;

        return $balance;
    }/* }}} */


    public function transfer($uid, $ticket)
    {
        /* {{{ 票转钻 */
        $dao_profile = new DAOProfile($uid);
        $frozen = $dao_profile->getUserProfile('frozen');
        $frozen_ticket = $dao_profile->getUserProfile('frozenTicket');

        Interceptor::ensureNotFalse((!($frozen == 'Y')), ERROR_BIZ_PAYMENT_WITHDRAW_FROZEN, "uid"); //帐户冻结的
        Interceptor::ensureNotFalse((!($frozen_ticket == 'Y')), ERROR_BIZ_PAYMENT_TICKET_FROZEN, "uid"); //冻结票

        $dao_account = new DAOAccount($uid);
        $balance = $dao_account->getBalance(self::CURRENCY_TICKET);

        $orderid = Account::getOrderId($uid);

        $dao_employe = new DAOEmploye();
        $fid = $dao_employe->isEmploye($uid);
        Interceptor::ensureNotFalse(!($fid['fid']>0 && ($fid['fid']!=20089 && $fid['fid']!=20022 && $fid['fid']!=20012 && $fid['fid']!=30202)), ERROR_BIZ_PAYMENT_ACCOUNT_TRANSFER_FAMILY_FALSE);

        //余额不足
        Interceptor::ensureNotFalse(!($ticket > $balance), ERROR_BIZ_PAYMENT_ACCOUNT_TICKET_LACK, "ticket");

        try {
            $config = new Config();
            $config_info = $config->getConfig(Context::get("region"), "payment", "server", '1.0.0.0');
            $percent = $config_info['transfer_percent'];
            if (!isset($percent)) {
                $percent = 0.64;
            }
            $amount = $ticket * $percent;
            $increase_diamond = floor($amount);
            $damage_diamond = ($amount - $increase_diamond);
            $company_diamond = ($ticket - $amount);

            $dao_account->startTrans();
            $this->decrease($uid, self::TRADE_TYPE_TICKET_TO_DIAMOND, $orderid, $ticket, self::CURRENCY_TICKET, "用户($uid)-票转钻-减票", array("decrease_ticket" => $ticket, "increase_diamond" => $increase_diamond, "transfer_percent" => $percent, "damage_diamond" => $damage_diamond, "userid" => $uid, "company_diamond" => $company_diamond));

            $this->increase($uid, self::TRADE_TYPE_TICKET_TO_DIAMOND, $orderid, $increase_diamond, self::CURRENCY_DIAMOND, "用户($uid)-票转钻-加钻", array("decrease_ticket" => $ticket, "increase_diamond" => $increase_diamond, "transfer_percent" => $percent, "damage_diamond" => $damage_diamond, "userid" => $uid, "company_diamond" => $company_diamond));
            //平台抽成
            $this->increase(Account::COMAPNY_ACCOUNT, self::TRADE_TYPE_TICKET_TO_DIAMOND, $orderid, $company_diamond, self::CURRENCY_DIAMOND, "用户($uid)-票转钻-平台抽成", array("decrease_ticket" => $ticket, "increase_diamond" => $increase_diamond, "transfer_percent" => $percent, "damage_diamond" => $damage_diamond, "userid" => $uid, "company_diamond" => $company_diamond));
            //损耗
            if ($damage_diamond > 0) {
                $this->increase(Account::DAMAGE_ACCOUNT, self::TRADE_TYPE_TICKET_TO_DIAMOND, $orderid, $damage_diamond, self::CURRENCY_DIAMOND, "用户($uid)-票转钻-折损", array("decrease_ticket" => $ticket, "increase_diamond" => $increase_diamond, "transfer_percent" => $percent, "damage_diamond" => $damage_diamond, "userid" => $uid, "company_diamond" => $company_diamond));
            }

            $dao_account->commit();
        } catch (Exception $e) {
            $dao_account->rollback();
            throw new BizException($e->getMessage());
        }

        return $balance - $ticket;
    }/* }}} */


    public function exchange($uid,$amount)
    {
        /* {{{ 星钻转星光 */
        $config = new Config();
        $config_infos = $config->getConfig(Context::get("region"), "exchange", "server", '1.0.0.0');
        $config_info_value    = json_decode($config_infos['value'], true);
        if($config_info_value) {
            foreach($config_info_value as $value){
                $config_info[$value['diamond']]   = $value['star'];
            }
        }
        $coin = $config_info[$amount];
        Interceptor::ensureNotFalse($coin > 0, ERROR_PARAM_INVALID_FORMAT, "amount");

        $dao_account = new DAOAccount($uid);
        $balance = $dao_account->getBalance(self::CURRENCY_DIAMOND);
        $orderid = Account::getOrderId($uid);
        //余额不足
        Interceptor::ensureNotFalse(!($amount > $balance), ERROR_BIZ_PAYMENT_ACCOUNT_BALANCE_LACK, "amount");

        try {
            $dao_account->startTrans();
            $this->decrease($uid, self::TRADE_TYPE_DIAMOND_TO_COIN, $orderid, $amount, self::CURRENCY_DIAMOND, "用户($uid)-星钻买星光-减钻", array("decrease_diamond" => $amount, "increase_diamond" => $coin, "userid" => $uid));
            $this->increase($uid, self::TRADE_TYPE_DIAMOND_TO_COIN, $orderid, $coin, self::CURRENCY_COIN, "用户($uid)-星钻买星光-加星光", array("decrease_diamond" => $amount, "increase_diamond" => $coin, "userid" => $uid));
            $dao_account->commit();
        } catch (Exception $e) {
            $dao_account->rollback();
            throw new BizException($e->getMessage());
        }

        return $balance - $amount;
    }/* }}} */

    public function exchangeInversion($uid,$amount)
    {
        /* {{{ 星光买星钻 */
        $config = new Config();
        $config_infos = $config->getConfig(Context::get("region"), "exchangeInversion", "server", '1.0.0.0');
        $config_info_value    = json_decode($config_infos['value'], true);
        if($config_info_value) {
            foreach($config_info_value as $value){
                $config_info[$value['star']]   = $value['diamond'];
            }
        }
        $diamond = $config_info[$amount];
        Interceptor::ensureNotFalse($diamond > 0, ERROR_PARAM_INVALID_FORMAT, "amount");

        $dao_account = new DAOAccount($uid);
        $balance = $dao_account->getBalance(self::CURRENCY_COIN);
        $orderid = Account::getOrderId($uid);
        //余额不足
        Interceptor::ensureNotFalse(!($amount > $balance), ERROR_BIZ_PAYMENT_ACCOUNT_BALANCE_LACK, "amount");
        //单日兑换额100钻, 
        //今日还可兑换xx星钻，请重新选择
        $dao_star_diamond_log = new DAOStarDiamondLog();

        try {
            $dao_account->startTrans();
            $this->decrease($uid, self::TRADE_TYPE_COIN_TO_DIAMOND, $orderid, $amount, self::CURRENCY_COIN, "用户($uid)-星光买星钻-减星光", array("decrease_icon" => $amount, "increase_diamond" => $diamond, "userid" => $uid));
            Interceptor::ensureNotFalse($account->decrease(ACCOUNT::ACTIVE_ACCOUNT2000, ACCOUNT::TRADE_TYPE_COIN_TO_DIAMOND, $orderid, $diamond, ACCOUNT::CURRENCY_DIAMOND, "{$userid}星光买星钻100:1定比. 数量$num", array()),  ERROR_BIZ_PAYMENT_STAR_BALANCE_DUE);
            $this->increase($uid, self::TRADE_TYPE_COIN_TO_DIAMOND, $orderid, $diamond, self::CURRENCY_DIAMOND, "用户($uid)-星光买星钻-加钻", array("decrease_icon" => $amount, "increase_diamond" => $diamond, "userid" => $uid));
            $dao_star_diamond_log->addStartDiamondlog($userid, $num, $accept_num);
            $dao_account->commit();
        } catch (Exception $e) {
            $dao_account->rollback();
            throw new BizException($e->getMessage());
        }

        return $balance - $amount;
    }/* }}} */


    public static function getJournalList($uid, $offset, $num, $direct, $currency, $type, $addtime, $endtime)
    {
        if($currency == 4 || $currency == 5) {
            $dao_journal = new DAOStarJournal($uid);
        } else {
            $dao_journal = new DAOJournal($uid);
        }
        $list        = $dao_journal->getJournalList($uid, $offset, $num, $direct, $currency, $type, $addtime, $endtime);
        $total       = $dao_journal->getJournalNum($uid, $direct, $currency, $type, $addtime, $endtime);
        //获取消费总数
        $star_journal    = new DAOStarJournal($uid);
        $journal        = new DAOJournal($uid);
        $star_total        = $star_journal -> getTotalNum($uid, $addtime, $endtime, 'OUT');
        $diamond_total    = $journal -> getTotalNum($uid, $addtime, $endtime, 'OUT');

        foreach ($list as $key => $value) {
            $offset = $value['id'];
            $list[$key]['extend'] = json_decode($value['extend'], true);
        }
        $more = 'Y';
        if(count($list) < $num) {
            $more = 'N';
        }

        return array($total, $list, $offset, $more,$star_total,$diamond_total);
    }
    /**
     * 获取用户收入记录
     */
    public function getRevenueLog($uid, $offset, $num, $currency, $startime, $endtime, $type)
    {
        if($currency == 1) {//星钻
            $dao_journal    = new DAOJournal($uid);
            $all    = '3,17,30,31,59,50,53';
        }elseif($currency == 5) {//星光
            $dao_journal    = new DAOStarJournal($uid);
            $all    = '2';
        }

        $journal_list        = $dao_journal -> getJournalList($uid, $offset, $num, 'IN', $currency, $type, $startime, $endtime, $all);
        $total                = $dao_journal -> getJournalNum($uid, 'IN', $currency, $type, $startime, $endtime, $all);
        $income                = $dao_journal -> getTotalNum($uid, $startime, $endtime, 'IN', $all);
        $income_num            = $dao_journal -> getIncomeNum($uid, $startime, $endtime, 'IN', $all);

        foreach ($journal_list as $key => $value) {
            $offset = $value['id'];
            $journal_list[$key]['extend'] = json_decode($value['extend'], true);
        }
        $more = 'Y';
        if(count($journal_list) < $num) {
            $more = 'N';
        }

        return array($total, $journal_list, $offset, $more, $income, $income_num);
    }
}
?>
