<?php
class AccountController extends BaseController
{
    public function getAccountInfoAction()
    {
        /*{{{获取账户详情*/
        $uid = intval($this->getParam('uid'));
        $userid  = Context::get("userid");

        $uid = empty($uid) ? $userid : $uid;
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");

        $account = new Account();
        $info = $account->getAccountList($uid);

        $this->render($info);
    }/*}}}*/

    public function getAccountInfoForH5Action()
    {
        /*{{{获取账户详情*/
        $uid = intval($this->getParam('uid'));
        $userid  = Context::get("userid");
        $sign     = $this->getParam('sign');

        $uid = empty($uid) ? $userid : $uid;
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotFalse(Withdraw::checkSign($uid, $sign), ERROR_BIZ_PAYMENT_WITHDRAW_TOKEN, "sign");


        $account = new Account();
        $info = $account->getAccountList($uid);

        $this->render($info);
    }/*}}}*/

    public function getAmountIncomeAction()
    {
        /*{{{获取账户详情*/
        $uid = intval($this->getParam('uid'));
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");

        $amount_income = Counter::get(Counter::COUNTER_TYPE_PAYMENT_RECEIVE_GIFT, $uid);
        $amount_income = $amount_income ? $amount_income : 0;

        $this->render(array('amount_income'=>(int)$amount_income));
    }/*}}}*/

    public function frozenAction()
    {
        /*{{{冻结账户*/
        $uid = intval($this->getParam('uid'));

        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");

        $account = new Account();
        $account->frozen($uid);

        $this->render();
    }/*}}}*/

    public function unFrozenAction()
    {
        /*{{{解除冻结*/
        $uid = intval($this->getParam('uid'));

        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        $account = new Account();
        $account->unFrozen($uid);

        $this->render();
    }/*}}}*/

    public function frozenTicketAction()
    {
        /*{{{冻结账户-票， 只有进没有出*/
        $uid = intval($this->getParam('uid'));

        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");

        $account = new Account();
        $account->frozenTicket($uid);

        $this->render();
    }/*}}}*/

    public function unFrozenTicketAction()
    {
        /*{{{解除冻结-票， 只有进没有出*/
        $uid = intval($this->getParam('uid'));

        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        $account = new Account();
        $account->unFrozenTicket($uid);

        $this->render();
    }/*}}}*/

    public function transferAction()
    {
        /*{{{票转钻*/
        $ticket = intval($this->getParam('ticket'));
        $userid  = Context::get("userid");

        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotFalse($ticket > 0, ERROR_PARAM_INVALID_FORMAT, "ticket");

        $account = new Account();
        $balance = $account->transfer($userid, $ticket);

        $account = new Account();
        $info = $account->getAccountList($userid);

        $this->render($info);
    }/*}}}*/

    public function transferListAction()
    {
        /*{{{票转钻 列表*/
        $userid  = Context::get("userid");

        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        $account = new Account();
        $list = $account->getTransferList($userid);

        $this->render($list);
    }/*}}}*/

    public function exchangeAction()
    {
        /*{{{星钻转星光*/
        /*error_reporting(E_ALL);
        ini_set('display_errors', '1');*/
        $amount = intval($this->getParam('amount'));
        $userid  = Context::get("userid");

        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotFalse($amount > 0, ERROR_PARAM_INVALID_FORMAT, "amount");

        $account = new Account();
        $balance = $account->exchange($userid, $amount);

        $account = new Account();
        $info = $account->getAccountList($userid);

        $this->render($info);
    }/*}}}*/

    public function exchangeInversionAction()
    {
        /*{{{星钻转星光*/
        /*error_reporting(E_ALL);
        ini_set('display_errors', '1');*/
        $amount = intval($this->getParam('amount'));
        $userid  = Context::get("userid");

        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotFalse($amount > 0, ERROR_PARAM_INVALID_FORMAT, "amount");

        $account = new Account();
        $balance = $account->exchangeInversion($userid, $amount);

        $account = new Account();
        $info = $account->getAccountList($userid);

        $this->render($info);
    }/*}}}*/

    public function searchAction()
    {
        /*error_reporting(E_ALL);
        ini_set('display_errors', '1');*/
        $uid      = Context::get("userid");
        $offset   = (int) $this->getParam("offset")?(int) $this->getParam("offset"):PHP_INT_MAX;
        $num      = (int) $this->getParam("num", 20);
        $currency = $this->getParam("currency"); //币种
        $startime = $this->getParam("startime"); //开始时间
        $endtime  = $this->getParam("endtime"); //截至时间
        $type     = $this->getParam("type"); //类型

        Interceptor::ensureFalse($num > 100, ERROR_PARAM_INVALID_FORMAT, "num");
        Interceptor::ensureFalse(!in_array($currency, [ 2, 4]), ERROR_PARAM_INVALID_FORMAT, "currency");

        //$diect    = 'OUT';
        list($total, $list, $offset, $more,$star_total,$diamond_total) = Account::getJournalList($uid, $offset, $num, '', $currency, $type, $startime, $endtime);

        $list_format = array();
        foreach ($list as $key => $value) {
            $offset = $value['id'];
            $infos        = $this->format($value['orderid'], $value['extend'], $value['type'], $currency);
            $list_format[$key]['remark']     = $infos['remark'];
            $list_format[$key]['goods_name'] = $infos['type_name'];
            $list_format[$key]['icon']         = $infos['icon'];
            $list_format[$key]['num']         = $infos['num'];
            $list_format[$key]['date']       = $value['addtime'];
            $list_format[$key]['amount']     = $value['amount'];
            $list_format[$key]['type']         = $value['type'];
            $list_format[$key]['direct']     = $value['direct']=='OUT'?1:2;
        }

        $this->render(
            array(
            'list' => $list_format,
            'total' => $total,
            'offset' => (int)$offset,
            'star_total' => $star_total,
            'diamond_total' => $diamond_total,
            'type'    => (int)$currency,
            'more' => $more=="Y"?true:false
            )
        );
    }

    public function format($orderid,$remark,$type,$currency)
    {
        $lan = 'zh';
        if(Context::get("region") != 'china') {
            $lan = 'en';
        }
        if($currency == 2) {
            $game        = 'Game-1';
        }else{
            $game        = 'Game-2';
        }
        $lang['zh'] = array(
            Account::TRADE_TYPE_GIFT=>'送礼',
            Account::TRADE_TYPE_SYSTERM_TOOL=>'飞屏',
            Account::TRADE_TYPE_GUARD=>'守护',
            Account::TRADE_TYPE_GAME_RUN=>'游戏',
            Account::TRADE_TYPE_DOOR_TICKET=>'门票'
        );
        $lang['en'] = array(
            Account::TRADE_TYPE_GIFT=>'gift',
            Account::TRADE_TYPE_SYSTERM_TOOL=>'system',
            Account::TRADE_TYPE_GUARD=>'guard',
            Account::TRADE_TYPE_GAME_RUN=>$game,
            Account::TRADE_TYPE_DOOR_TICKET=>'worthy'
        );
        //IMG
        switch ($type){
        case 2:
            $gift    = new DAOGift();
            $giftlog= new DAOGiftStarLog();
            $log_info    = $giftlog->getInfo($orderid);
            $gift_info    = $gift    ->getInfo($log_info['giftid']);
            $icon        = $gift_info['image'];
            $game_name    = $gift_info['name'];
            break;
        case 3:
            $gift    = new DAOGift();
            $giftlog= new DAOGiftLog();
            $log_info    = $giftlog->getInfo($orderid);
            $gift_info    = $gift    ->getInfo($log_info['giftid']);
            $icon        = $gift_info['image'];
            $game_name    = $gift_info['name'];
            break;
        case 17:
            $info    = UserGuard::$guard;
            $icon    = $info[$remark['type']]['image'];
            $game_name = $remark['type']==5?'30天守护':'365天守护';
            break;
        case 18:
            //$info    = Game::getGameInfo(1);
            //$icon    = $info['icon'];
            if(!isset($remark['type'])) { $remark['type'] = '押注';
            }
            if($remark['type'] == 1) { $remark['type'] = '抢庄';
            }
            if($remark['type'] == 2) { $remark['type'] = '押注';
            }
            $game_name = "游戏 ({$remark['type']})";
            break;
        case 6:
            $game_name = '飞屏';
            break;
        case 5:
            $game_name = '红包';
            break;
        case 30:
            $game_name = '门票';
            break;
        }
        return array(
            'type_name'    => (string)$game_name,
            'icon'            => (string)Util::joinStaticDomain($icon),
            'num'            => (string)$log_info['num'],
            'guard_type'    => (int)$remark['type'],
            'remark'        => $lang[$lan][$type]."--".$icon
        );
    }


    public function getJournalListAction()
    {
        $uid    = Context::get("userid");
        $offset = (int) $this->getParam("offset")?(int) $this->getParam("offset"):PHP_INT_MAX;
        $num    = (int) $this->getParam("num", 20);
        $direct = $this->getParam("direct", 'OUT'); //进，出

        Interceptor::ensureFalse($num > 200, ERROR_PARAM_INVALID_FORMAT, "num");

        list($total, $list, $offset) = Account::getJournalList($uid, $offset, $num, $direct, '', 4, '', '');

        $this->render(
            array(
            'list' => $list,
            'total' => $total,
            'offset' => $offset,
            )
        );
    }


    public function activeRegAction()
    {
        /*{{{活动送钻 出钻帐号1004*/
        $channel = $this->getParam('channel');
        $userid  = Context::get("userid");

        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotEmpty($channel, ERROR_PARAM_IS_EMPTY, 'channel');

        $user = new User();
        $userinfo = $user->getUserInfo($userid);
        Interceptor::ensureNotFalse(isset($userinfo['uid']), ERROR_USER_NOT_EXIST);

        $config = new Config();
        $send_diamond = $config->getConfig('china', "html5_reg_send_diamond", "server", '1.0.0.0');

        $account = new Account();
        $dao_gift_log = new DAOGiftLog();
        try {
            $dao_gift_log->startTrans();

            $diamond = $account->getBalance(1004, ACCOUNT::CURRENCY_DIAMOND);
            Interceptor::ensureNotEmpty($diamond, ERROR_CUSTOM, "活动已结束");
            Interceptor::ensureNotFalse($diamond>=$send_diamond['value'], ERROR_CUSTOM, "活动已结束");

            $orderid = Account::getOrderId(1004);
            Interceptor::ensureNotFalse($account->decrease(1004, ACCOUNT::TRADE_TYPE_GIFT, $orderid, $send_diamond['value'], ACCOUNT::CURRENCY_DIAMOND, $userid . "html5新用户用注加钻$send_diamond,渠道号$channel", array()),  ERROR_CUSTOM, "活动已结束");
            Interceptor::ensureNotFalse($account->increase($userid, ACCOUNT::TRADE_TYPE_DEPOSIT, $orderid, $send_diamond['value'], ACCOUNT::CURRENCY_DIAMOND, userid . "html5新用户用注加钻$send_diamond,渠道号$channel", array()), ERROR_CUSTOM, "活动已结束");

            $dao_gift_log->commit();
        } catch (Exception $e) {
            $dao_gift_log->rollback();
            throw $e;
        }

        $version  = Context::get("version");
        $deviceid = Context::get("deviceid");
        $model    = Context::get("model");
        self::getCurlData("http://log.dreamlive.tv/activeReg?diamond={$send_diamond['value']}&uid=$userid&channel=$channel&platform=server&version=$version&deviceid=$deviceid&model=$model");

        Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $userid, '送您10钻。', 0);

        $this->render();
    }/*}}}*/


    public function activeVoteAction()
    {
        /*{{{活动投钻 入钻帐号1000*/
        $userid  = Context::get("userid");
        $num     = 10; //投10个钻

        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");

        $user = new User();
        $userinfo = $user->getUserInfo($userid);
        Interceptor::ensureNotFalse(isset($userinfo['uid']), ERROR_USER_NOT_EXIST);

        $dao_profile = new DAOProfile($userid);
        $profiles = $dao_profile->getUserProfiles();
        Interceptor::ensureNotFalse((!(isset($profiles['frozen']) && $profiles['frozen'] == 'Y')), ERROR_BIZ_GIFT_FROZEN, "userid");

        $account = new Account();
        $dao_gift_log = new DAOGiftLog();
        try {
            $dao_gift_log->startTrans();

            $diamond = $account->getBalance($userid, ACCOUNT::CURRENCY_DIAMOND);
            Interceptor::ensureNotEmpty($diamond, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse($diamond>=$num, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

            $orderid = Account::getOrderId($userid);
            Interceptor::ensureNotFalse($account->decrease($userid, ACCOUNT::TRADE_TYPE_DO_ACTIVE, $orderid, $num, ACCOUNT::CURRENCY_DIAMOND, "html5, {$userid}投钻10.", array()),  ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse($account->increase(Account::COMAPNY_ACCOUNT, ACCOUNT::TRADE_TYPE_DO_ACTIVE, $orderid, $num, ACCOUNT::CURRENCY_DIAMOND, "html5, {$userid}投钻10", array()), ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

            $dao_gift_log->commit();
        } catch (Exception $e) {
            $dao_gift_log->rollback();
            throw $e;
        }

        $this->render();
    }/*}}}*/

    public function activeBallotAction()
    {
        /*{{{活动投票. 出钻 入钻帐号1000*/
        $userid  = Context::get("userid");
        $num     = $this->getParam('amount');
        $type    = $this->getParam('type');

        Interceptor::ensureNotFalse($num > 0, ERROR_PARAM_INVALID_FORMAT, "amount");
        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");
        Interceptor::ensureNotFalse($type > 0, ERROR_PARAM_INVALID_FORMAT, "type");
        Interceptor::ensureNotFalse($type < 10, ERROR_PARAM_INVALID_FORMAT, "type");

        $user = new User();
        $userinfo = $user->getUserInfo($userid);
        Interceptor::ensureNotFalse(isset($userinfo['uid']), ERROR_USER_NOT_EXIST);

        $dao_profile = new DAOProfile($userid);
        $profiles = $dao_profile->getUserProfiles();
        Interceptor::ensureNotFalse((!(isset($profiles['frozen']) && $profiles['frozen'] == 'Y')), ERROR_BIZ_GIFT_FROZEN, "userid");

        $account = new Account();
        $dao_gift_log = new DAOGiftLog();
        try {
            $dao_gift_log->startTrans();

            $diamond = $account->getBalance($userid, ACCOUNT::CURRENCY_DIAMOND);
            Interceptor::ensureNotEmpty($diamond, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse($diamond>=$num, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

            $orderid = Account::getOrderId($userid);
            Interceptor::ensureNotFalse($account->decrease($userid, ACCOUNT::TRADE_TYPE_DO_ACTIVE . sprintf("%02d", $type), $orderid, $num, ACCOUNT::CURRENCY_DIAMOND, "html5, {$userid}投票$num, 类型$type.", array()),  ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse($account->increase(Account::COMAPNY_ACCOUNT, ACCOUNT::TRADE_TYPE_DO_ACTIVE . sprintf("%02d", $type), $orderid, $num, ACCOUNT::CURRENCY_DIAMOND, "html5, {$userid}投票$num, 类型$type.", array()), ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

            $dao_gift_log->commit();
        } catch (Exception $e) {
            $dao_gift_log->rollback();
            throw $e;
        }

        $this->render();
    }/*}}}*/

    public function activeTransferAction()
    {
        /*{{{往活动帐号转钻从1000到1005*/
        $key = $this->getParam('key');
        $active_account = (int) $this->getParam("active_account", 1005);
        Interceptor::ensureFalse($key != 'Yjjfdasdfasf2312sdyuty43', ERROR_PARAM_INVALID_FORMAT, "key");
        Interceptor::ensureFalse($active_account > 10000, ERROR_PARAM_INVALID_FORMAT, "active_account");
        $num = (int) $this->getParam("num", 10000);

        Interceptor::ensureFalse($num > 5000000, ERROR_PARAM_INVALID_FORMAT, "num");

        if($active_account == 4000 || $active_account == 4001) { //只有4000答题或4001转现金
            $currency =  Account::CURRENCY_CASH;
        } else {
            $currency =  Account::CURRENCY_DIAMOND;
        }

        $account         = new Account();
        $dao_gift_log    = new DAOGiftLog();
        try {
            $dao_gift_log->startTrans();

            $diamond = $account->getBalance(Account::COMAPNY_ACCOUNT, $currency);
            Interceptor::ensureNotEmpty($diamond, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse($diamond>=$num, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

            $orderid = Account::getOrderId(Account::COMAPNY_ACCOUNT);
            Interceptor::ensureNotFalse($account->decrease(Account::COMAPNY_ACCOUNT, 15, $orderid, $num, $currency, "1000往{$active_account}活动帐户转$num", array()),  ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse($account->increase($active_account, ACCOUNT::TRADE_TYPE_ACCOUNT_TRANS, $orderid, $num, $currency, "1000往{$active_account}活动帐户转$num", array()), ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);


            $dao_gift_log->commit();
        } catch (Exception $e) {
            $dao_gift_log->rollback();
            throw $e;
        }

        $this->render();
    }/*}}}*/


    public function activeLotteryAction()
    {
        /*{{{抽奖送钻 入钻帐号$uid*/
        $userid  = $this->getParam('userid');
        $num     = $this->getParam('num');

        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");
        Interceptor::ensureNotEmpty($num, ERROR_PARAM_IS_EMPTY, 'num');
        Interceptor::ensureNotFalse($num > 0, ERROR_PARAM_INVALID_FORMAT, "num");

        $user = new User();
        $userinfo = $user->getUserInfo($userid);
        Interceptor::ensureNotFalse(isset($userinfo['uid']), ERROR_USER_NOT_EXIST);

        $dao_profile = new DAOProfile($userid);
        $profiles = $dao_profile->getUserProfiles();
        Interceptor::ensureNotFalse((!(isset($profiles['frozen']) && $profiles['frozen'] == 'Y')), ERROR_BIZ_GIFT_FROZEN, "userid");

        $account = new Account();
        $dao_gift_log = new DAOGiftLog();
        try {
            $dao_gift_log->startTrans();

            $diamond = $account->getBalance(Account::ACTIVE_ACCOUNT6, ACCOUNT::CURRENCY_DIAMOND);
            Interceptor::ensureNotEmpty($diamond, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse($diamond>=$num, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

            $orderid = Account::getOrderId(Account::ACTIVE_ACCOUNT6);
            Interceptor::ensureNotFalse($account->decrease(Account::ACTIVE_ACCOUNT6, ACCOUNT::TRADE_TYPE_DO_ACTIVE_LOTTERY, $orderid, $num, ACCOUNT::CURRENCY_DIAMOND, "html5, {$userid}抽奖得$num.", array()),  ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse($account->increase($userid, ACCOUNT::TRADE_TYPE_DO_ACTIVE_LOTTERY, $orderid, $num, ACCOUNT::CURRENCY_DIAMOND, "html5, {$userid}抽奖得$num", array()), ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

            $dao_gift_log->commit();
        } catch (Exception $e) {
            $dao_gift_log->rollback();
            throw $e;
        }

        $this->render();
    }/*}}}*/


    public function starTransferAction()
    {
        /*{{{从系统帐号转星光 入钻帐号$userid*/
        $userid  = $this->getParam('userid');
        $num     = $this->getParam('num');
        $key = $this->getParam('key');

        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");
        Interceptor::ensureNotEmpty($num, ERROR_PARAM_IS_EMPTY, 'num');
        Interceptor::ensureNotFalse($num > 0, ERROR_PARAM_INVALID_FORMAT, "num");
        Interceptor::ensureFalse($key != 'Yjjfdasdfasf2312sddyuty4313', ERROR_PARAM_INVALID_FORMAT, "key");

        $user = new User();
        $userinfo = $user->getUserInfo($userid);
        Interceptor::ensureNotFalse(isset($userinfo['uid']), ERROR_USER_NOT_EXIST);

        $dao_profile = new DAOProfile($userid);
        $profiles = $dao_profile->getUserProfiles();
        Interceptor::ensureNotFalse((!(isset($profiles['frozen']) && $profiles['frozen'] == 'Y')), ERROR_BIZ_GIFT_FROZEN, "userid");

        $account = new Account();
        $dao_gift_log = new DAOGiftLog();
        try {
            $dao_gift_log->startTrans();

            $coin = $account->getBalance(Account::ACTIVE_ACCOUNT2000, ACCOUNT::CURRENCY_COIN);
            Interceptor::ensureNotEmpty($coin, ERROR_BIZ_STAR_BALANCE_DUE);
            Interceptor::ensureNotFalse($coin>=$num, ERROR_BIZ_STAR_BALANCE_DUE);

            $orderid = Account::getOrderId(Account::ACTIVE_ACCOUNT2000);
            Interceptor::ensureNotFalse($account->decrease(Account::ACTIVE_ACCOUNT2000, ACCOUNT::TRADE_TYPE_STAR_TRANSFER, $orderid, $num, ACCOUNT::CURRENCY_COIN, "系统帐号2000转{$userid}星光$orderid", array()),  ERROR_BIZ_STAR_BALANCE_DUE);
            Interceptor::ensureNotFalse($account->increase($userid, ACCOUNT::TRADE_TYPE_STAR_TRANSFER, $orderid, $num, ACCOUNT::CURRENCY_COIN, "系统帐号2000转{$userid}星光$orderid", array()), ERROR_BIZ_STAR_BALANCE_DUE);

            $dao_gift_log->commit();
        } catch (Exception $e) {
            $dao_gift_log->rollback();
            throw $e;
        }

        $this->render();
    }/*}}}*/

    public function diamondTransferStarAction()
    {
        /*{{{钻转星光$userid, 定比1:100*/
        $userid  = $this->getParam('userid');
        $num     = $this->getParam('num');
        $key = $this->getParam('key');

        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");
        Interceptor::ensureNotEmpty($num, ERROR_PARAM_IS_EMPTY, 'num');
        Interceptor::ensureNotFalse($num > 0, ERROR_PARAM_INVALID_FORMAT, "num");
        Interceptor::ensureFalse($key != 'Yjjfdasdfasf2312s43ddyuty4313', ERROR_PARAM_INVALID_FORMAT, "key");

        $user = new User();
        $userinfo = $user->getUserInfo($userid);
        Interceptor::ensureNotFalse(isset($userinfo['uid']), ERROR_USER_NOT_EXIST);

        $dao_profile = new DAOProfile($userid);
        $profiles = $dao_profile->getUserProfiles();
        Interceptor::ensureNotFalse((!(isset($profiles['frozen']) && $profiles['frozen'] == 'Y')), ERROR_BIZ_GIFT_FROZEN, "userid");

        $account = new Account();
        $dao_gift_log = new DAOGiftLog();
        try {
            $dao_gift_log->startTrans();

            $diamond = $account->getBalance($userid, ACCOUNT::CURRENCY_DIAMOND);
            Interceptor::ensureNotEmpty($diamond, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse($diamond>=$num, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

            $orderid = Account::getOrderId($userid);
            $accept_num = $num*100;
            Interceptor::ensureNotFalse($account->decrease($userid, ACCOUNT::TRADE_TYPE_DIAMOND_TO_COIN, $orderid, $num, ACCOUNT::CURRENCY_DIAMOND, "{$userid}星钻买星光1:100定比. 数量$num", array()),  ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse($account->increase($userid, ACCOUNT::TRADE_TYPE_DIAMOND_TO_COIN, $orderid, $accept_num, ACCOUNT::CURRENCY_COIN, "{$userid}星钻买星光1:100定比. 数量$accept_num", array()), ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

            $dao_gift_log->commit();
        } catch (Exception $e) {
            $dao_gift_log->rollback();
            throw $e;
        }

        $this->render();
    }/*}}}*/

    public function starTransferDiamondAction()
    {
        /*{{{星星转钻$userid, 定比100:1*/
        $userid     = Context::get("userid");;
        $num     = $this->getParam('num');
        $key = $this->getParam('key');

        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");
        Interceptor::ensureNotEmpty($num, ERROR_PARAM_IS_EMPTY, 'num');
        Interceptor::ensureNotFalse($num > 0, ERROR_PARAM_INVALID_FORMAT, "num");
        Interceptor::ensureFalse($key != 'Yjjfdasdfasf23213123asda12s43', ERROR_PARAM_INVALID_FORMAT, "key");

        $user = new User();
        $userinfo = $user->getUserInfo($userid);
        Interceptor::ensureNotFalse(isset($userinfo['uid']), ERROR_USER_NOT_EXIST);

        $dao_profile = new DAOProfile($userid);
        $profiles = $dao_profile->getUserProfiles();
        Interceptor::ensureNotFalse((!(isset($profiles['frozen']) && $profiles['frozen'] == 'Y')), ERROR_BIZ_GIFT_FROZEN, "userid");

        //获取兑换星钻额度
        $config     = new Config();
        $list       = $config->getConfig("china", "star_diamond_exchange", "server", '1000000000000');
        $list_value = json_decode($list['value'], true);
        if($list_value) {
            foreach($list_value as $val){
                if($val['star'] == $num) {
                    $accept_num = $val['diamond'];
                }
            }
        }
        Interceptor::ensureNotFalse($accept_num>0, ERROR_PARAM_INVALID_FORMAT, "num");

        $account = new Account();
        $dao_gift_log = new DAOGiftLog();
        $dao_star_diamond_log = new DAOStarDiamondLog();
        $dao_star_diamond_count = new DAOStarDiamondCount();
        try {
            $dao_gift_log->startTrans();
            $star = $account->getBalance($userid, ACCOUNT::CURRENCY_STAR);
            Interceptor::ensureNotEmpty($star, ERROR_BIZ_PAYMENT_STAR_BALANCE_DUE);
            Interceptor::ensureNotFalse($star>=$num, ERROR_BIZ_PAYMENT_STAR_BALANCE_DUE);

            $orderid = Account::getOrderId($userid);

            //$accept_num = ceil($num/100);
            Interceptor::ensureNotFalse($account->decrease($userid, ACCOUNT::TRADE_TYPE_STAR_TO_DIAMOND, $orderid, $num, ACCOUNT::CURRENCY_STAR, "{$userid}星星买星钻100:1定比. 数量$num", array()),  ERROR_BIZ_PAYMENT_STAR_BALANCE_DUE);
            Interceptor::ensureNotFalse($account->decrease(ACCOUNT::ACTIVE_ACCOUNT2000, ACCOUNT::TRADE_TYPE_STAR_TO_DIAMOND, $orderid, $accept_num, ACCOUNT::CURRENCY_DIAMOND, "{$userid}星光买星钻100:1定比. 数量$num", array()),  ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse($account->increase($userid, ACCOUNT::TRADE_TYPE_STAR_TO_DIAMOND, $orderid, $accept_num, ACCOUNT::CURRENCY_DIAMOND, "{$userid}星星买星钻100:1定比. 数量$accept_num", array()), ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            $dao_star_diamond_log->addStartDiamondlog($userid, $num, $accept_num);
            $dao_star_diamond_count->del($userid);
            Interceptor::ensureNotFalse($dao_star_diamond_count->insert($userid, $accept_num, 500), ERROR_BIZ_PAYMENT_STAR_DIAMOND_LIMIT);


            $dao_gift_log->commit();
        } catch (Exception $e) {
            $dao_gift_log->rollback();
            throw $e;
        }

        $this->render();
    }/*}}}*/



    public function diamondTransferBaiyingGoldAction()
    {
        /*{{{星钻转百ying金币 入钻帐号1100*/
        $userid  = $this->getParam('uid');

        $uid = empty($uid) ? $userid : $uid;
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");

        $num     = $this->getParam('amount');
        $key = $this->getParam('key');

        Interceptor::ensureNotEmpty($num, ERROR_PARAM_IS_EMPTY, 'num');
        Interceptor::ensureNotFalse($num > 0, ERROR_PARAM_INVALID_FORMAT, "num");
        Interceptor::ensureFalse($key != 'Yjjfdasdfasf23121sddyu3ty4313', ERROR_PARAM_INVALID_FORMAT, "key");

        $user = new User();
        $userinfo = $user->getUserInfo($uid);
        Interceptor::ensureNotFalse(isset($userinfo['uid']), ERROR_USER_NOT_EXIST);

        $dao_profile = new DAOProfile($uid);
        $profiles = $dao_profile->getUserProfiles();
        Interceptor::ensureNotFalse((!(isset($profiles['frozen']) && $profiles['frozen'] == 'Y')), ERROR_BIZ_GIFT_FROZEN, "userid");

        $account = new Account();
        $dao_gift_log = new DAOGiftLog();
        try {
            $dao_gift_log->startTrans();

            $diamond = $account->getBalance($uid, ACCOUNT::CURRENCY_DIAMOND);
            Interceptor::ensureNotEmpty($diamond, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse($diamond>=$num, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

            $orderid = Account::getOrderId($uid);
            Interceptor::ensureNotFalse($account->decrease($uid, ACCOUNT::TRADE_TYPE_BAIYING_GOLD, $orderid, $num, ACCOUNT::CURRENCY_DIAMOND, "uid:{$uid}.星钻转合作方帐户金币, 百盈足球专用$num*10, 比例1vs10", array('uid'=>$uid, 'amount'=>$num)),  ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse($account->increase(Account::ACTIVE_ACCOUNT1100, ACCOUNT::TRADE_TYPE_BAIYING_GOLD, $orderid, $num, ACCOUNT::CURRENCY_DIAMOND, "uid:{$uid}.星钻转合作方帐户金币, 百盈足球专用$num*10, 比例1vs10", array('uid'=>$uid, 'amount'=>$num)), ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

            $dao_gift_log->commit();
        } catch (Exception $e) {
            $dao_gift_log->rollback();
            throw $e;
        }

        $this->render(array("orderid"=>$orderid));
    }/*}}}*/

    /**
     * 用户收入详情列表
     */
    public function revenueLogAction()
    {
        $uid      = Context::get("userid");
        $offset   = (int) $this->getParam("offset")?(int) $this->getParam("offset"):PHP_INT_MAX;
        $num      = (int) $this->getParam("num", 20);
        $currency = $this->getParam("currency"); //币种
        $startime = $this->getParam("startime"); //开始时间
        $endtime  = $this->getParam("endtime"); //截至时间
        $type     = $this->getParam("type"); //类型

        Interceptor::ensureFalse($num > 100, ERROR_PARAM_INVALID_FORMAT, "num");
        Interceptor::ensureFalse(!in_array($currency, [ 1, 5]), ERROR_PARAM_INVALID_FORMAT, "currency");

        $account    = new Account();
        list($total, $journal_list, $offset, $more, $income, $income_num)        = $account -> getRevenueLog($uid, $offset, $num, $currency, $startime, $endtime, $type);
        if($journal_list) {
            foreach($journal_list as $k=>$value){
                $list_format[$k]        = self::getJournalInfo($value);
            }
        }
        $this -> render(
            array(
            'list' => $list_format,
            'total' => $total,
            'offset' => (int)$offset,
            'income' => (int)$income,
            'income_num' => (int)$income_num,
            'currency'    => (int)$currency,
            'more' => $more=="Y"?true:false)
        );
    }
    public static function getJournalInfo($info)
    {
        if(empty($info)) { return array();
        }
        switch ($info['type']){
        case 2:
            $gift    = new DAOGift();
            $giftlog= new DAOGiftStarLog();
            $log_info    = $giftlog->getInfo($info['orderid']);
            $gift_info    = $gift    ->getInfo($log_info['giftid']);
            $infos        = array(
                'icon'        => Util::joinStaticDomain($gift_info['image']),
                'goods_name'        => $gift_info['name'],
                'num'        => $log_info['num'],
                'date'    => $log_info['addtime']
            );
            $sender        = $log_info['sender'];
            break;
        case 3:
        case 50:
        case 53:
            $gift    = new DAOGift();
            $giftlog= new DAOGiftLog();
            $log_info    = $giftlog->getInfo($info['orderid']);
            $gift_info    = $gift    ->getInfo($log_info['giftid']);
            $infos        = array(
                'icon'        => Util::joinStaticDomain($gift_info['image']),
                'goods_name'        => $gift_info['name'],
                'num'        => $log_info['num'],
                'date'    => $log_info['addtime']
            );
            $sender        = $log_info['sender'];
            if($info['type'] == 50) { $type = 50;
            }
            $info['type'] = 3;
            break;
        case 17:
            $user_guard_detail    = new DAOUserGuardDetail();
            $guard    = UserGuard::$guard;
            $remark    = $info['extend'];
            $icon    = Util::joinStaticDomain($guard[$remark['type']]['image']);
            $user_detail    = $user_guard_detail -> getUserGuardDetailByOrderid($info['orderid']);
            $infos        = array(
                'icon'        => $icon,
                'goods_name'        => $user_detail['type'] == 5?'月守护':'年守护',
                'num'        => 1,
                'date'    => $user_detail['addtime']
            );
            $sender        = $user_detail['uid'];
            break;
        case 30:
            $privacy_journal    = new DAOPrivacyJournal();
            $privacy_info        = $privacy_journal->getPrivacyJournalByOrderid($info['orderid']);
            $infos        = array(
                'icon'        => '',
                'goods_name'        => '门票',
                'num'        => 1,
                'date'    => $privacy_info['addtime']
            );
            $sender        = $privacy_info['buyer'];
            break;
        case 31:
            $privacy_journal    = new DAOPrivacyJournal();
            $privacy_info        = $privacy_journal->getPrivacyJournalByOrderid($info['orderid']);
            $infos        = array(
                'icon'        => '',
                'goods_name'        => '回放',
                'num'        => 1,
                'date'    => $privacy_info['addtime']
            );
            $sender        = $privacy_info['buyer'];
            break;
        case 59:
            $infos        = array(
                'icon'        => '',
                'goods_name'        => '奖励',
                'num'        => 1,
                'date'    => $info['addtime']
            );
            break;
        }
        if($info['type'] == 59) {
            $infos['send_nickname']    = '官方';
        }else{
            $send_info    = User::getUserInfo($sender);
            if(!$send_info['nickname']&&$type==50) {
                $infos['send_nickname'] = '背包礼物';
            }else{
                $infos['send_nickname']        = $send_info['nickname'];
            }
        }
        $infos['amount']                = $info['amount'];
        $infos['type']                    = $info['type'];
        $infos['diamond']              = $info['diamond']?$info['diamond']:0;
        return $infos;
    }
    /*
     * 获取星星与星钻列表
     * */
    public function starDiamondListAction()
    {
        $userid     = Context::get("userid");
        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");

        $config     = new Config();
        $account    = new DAOAccount($userid);
        $list       = $config->getConfig("china", "star_diamond_exchange", "server", '1000000000000');
        $account_list = $account->getAccountList();
        $data['list']   = json_decode($list['value'], true);
        if($account_list) {
            foreach($account_list as $val){
                if($val['currency'] == 2) {
                    $data['diamond']    = $val['balance'];
                }elseif($val['currency'] == 5) {
                    $data['star']    = $val['balance'];
                }
            }
        }
        $data['diamond']    = $data['diamond']?$data['diamond']:0;
        $data['star']       = $data['star']?$data['star']:0;
        $this -> render($data);
    }
    /*
     * 星钻兑换星光
     * */
    public function iconDiamondListAction()
    {
        $userid     = Context::get("userid");
        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");

        $config     = new Config();
        $account    = new DAOAccount($userid);
        $list       = $config->getConfig("china", "exchange", "server", '1000000000000');
        $account_list = $account->getAccountList();
        $data['list']   = json_decode($list['value'], true);
        if($account_list) {
            foreach($account_list as $val){
                if($val['currency'] == 2) {
                    $data['diamond']    = $val['balance'];
                }elseif($val['currency'] == 5) {
                    $data['star']    = $val['balance'];
                }elseif($val['currency'] == 4) {
                    $data['icon']    = $val['balance'];
                }
            }
        }
        $data['diamond']  = $data['diamond']?$data['diamond']:0;
        $data['star']      = $data['star']?$data['star']:0;
        $data['icon']      = $data['icon']?$data['icon']:0;
        $this -> render($data);
    }
    /*
     * 小程序分享领取星钻
     * */
    public function shareDiamondAction()
    {
        $userid     = Context::get("userid");
        $type       = $this->getParam('type', 0);
        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");

        $key1 = "share_diamond_".date("Y-m-d").'_'.$userid;
        if($type == 0 && Counter::get('login_send', $key1) == 0) {
            $this->render(array('result'=>'false'));
        }elseif($type == 0 && Counter::get('login_send', $key1) > 0) {
            $this->render(array('result'=>'true'));
        }
        Interceptor::ensureNotFalse(Counter::increase('login_send', $key1)<2, ERROR_SUMMIT_IS_DRAW);

        $account    = new Account();
        $dao_gift_log = new DAOGiftLog();
        $num        = rand(1, 3);
        try {
            $dao_gift_log->startTrans();

            $diamond = $account->getBalance(Account::ACTIVE_ACCOUNT14, Account::CURRENCY_DIAMOND);
            Interceptor::ensureNotEmpty($diamond, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse($diamond>=$num, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

            $orderid = Account::getOrderId($userid);
            Interceptor::ensureNotFalse($account->decrease(Account::ACTIVE_ACCOUNT14, Account::TRADE_TYPE_WECHAT_ACTIVE, $orderid, $num, Account::CURRENCY_DIAMOND, "微信小程序分享送钻", array()),  ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse($account->increase($userid, Account::TRADE_TYPE_WECHAT_ACTIVE, $orderid, $num, Account::CURRENCY_DIAMOND, "微信小程序分享送钻", array()), ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

            $dao_gift_log->commit();
        } catch (Exception $e) {
            $dao_gift_log->rollback();
            throw $e;
        }
        $this -> render(array('result'=>'ok','num'=>$num,'balance'=>$diamond));
    }

    public static function getCurlData($url, $xml, $useCert = false, $second = 30)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, false);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
        }
    }

}
?>
