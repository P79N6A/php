<?php
class Withdraw
{
    const WITHDRAW_STATUS_FAMILY     = 1;
    const WITHDRAW_STATUS_OPERATION  = 2;
    const WITHDRAW_STATUS_FINANCE    = 3;
    const WITHDRAW_STATUS_ACCEPT     = 4;
    const WITHDRAW_STATUS_REJECT     = 5;

    public function add($uid, $source, $amount)
    {

        //Interceptor::ensureNotFalse(false, ERROR_BIZ_PAYMENT_WITHDRAW_CLOSE);
        $config = new Config();
        $config_info = $config->getConfig('china', "payment_withdraw", "server", '1.0.0.0');

        $dao_withdraw = new DAOWithdraw();
        $total_amount = $dao_withdraw->getWithdrawTodayTotal($uid);
        $notlimit = array();
        if($config_info) {
            $notlimit = explode("|", $config_info['value']);
        }

        $corporate = new Employe();
        Interceptor::ensureFalse($corporate->isCorporate($uid), ERROR_BIZ_PAYMENT_WITHDRAW_NO_POWER, "uid");


        if(in_array($uid, $notlimit)) {
        } else {
            $config = new Config();
            $config_info_max = $config->getConfig('china', "payment_withdraw_max", "server", '1.0.0.0');
            Interceptor::ensureNotFalse(($total_amount['amount']+$amount)<=$config_info_max['value'], ERROR_CUSTOM, "今日提现金额不能大于{$config_info_max['value']}");
        }
        Interceptor::ensureNotFalse($amount>=50, ERROR_BIZ_PAYMENT_WITHDRAW_AMOUNT_SMALL, "amount");

        $orderid = Account::getOrderId($uid);

        $dao_account = new DAOAccount($uid);
        $account_info = $dao_account->getBalance(Account::CURRENCY_TICKET);

        $dao_employe = new DAOEmploye();
        $employe_info = $dao_employe->getEmployePercent($uid);

        if ($dao_employe->isEmploye($uid)) {
            $dao_family = new DAOFamily();
            $family_info = $dao_family->getFamilyInfoById($employe_info['fid']);
            $merge_percent   = $family_info["family_percent"]/100;
            $employe_percent = $employe_info["percent"]/100;
            $author_percent = $merge_percent * $employe_percent;
        } else {
            $config = new Config();
            $config_info = $config->getConfig('china', "payment", "server", '1.0.0.0');
            $author_percent = $config_info['passerby'];
            if (!isset($author_percent)) {
                $author_percent = 0.4;
            }
        }

        $dao_user_medal = new DAOUserMedal($uid);
        $user_medal = $dao_user_medal->getUserMedal("founder");
        $author_percent_medal = 0;
        if ($user_medal) {
            $author_percent_medal = 0.02;
        }
        $tickets = ceil($amount * 10 / ($author_percent + $author_percent_medal)); //总票

        $dao_profile = new DAOProfile($uid);
        $frozen = $dao_profile->getUserProfile('frozen');
        $frozen_ticket = $dao_profile->getUserProfile('frozenTicket');

        Interceptor::ensureNotFalse((!($frozen == 'Y')), ERROR_BIZ_PAYMENT_WITHDRAW_FROZEN, "uid");
        Interceptor::ensureNotFalse((!($frozen_ticket == 'Y')), ERROR_BIZ_PAYMENT_TICKET_FROZEN, "uid"); //冻结票
        Interceptor::ensureNotFalse(($account_info >= $tickets), ERROR_BIZ_PAYMENT_TICKET_BALANCE_DUE, "amount");
        //Interceptor::ensureNotFalse((!(isset($employe_info['frozen']) && $employe_info['frozen'] == 'Y')), ERROR_BIZ_PAYMENT_WITHDRAW_FAMILY_FROZEN, "uid");

        $damage_tickets = $tickets - $amount * 10 / ($author_percent + $author_percent_medal); //折损票

        if ($dao_employe->isEmploye($uid)) {
            //公司占比
            $company_percent = 1 - $merge_percent;
            //家族占比
            $family_percent = 1 - $company_percent - $author_percent;
            $status = 1; //家族审核
            if(in_array($employe_info['fid'], array(20012, 20089, 20022, 30202, 30226))) { //三个公会跳过验证. 
                $status = 2;
            }
        } else {
            $company_percent = 1 - $author_percent;
            $status = 2; //运营审核
        }

        if(self::isSex($uid)) { //非公对公公会在xs主播提现成功进行分账的那一刻，对分账到公会长的那部分钱进行19%的扣税后到达公会长资金账户，然后正常结算
            //$xs_rate = 0.81;
            $xs_rate = 1;
            $family_tickets        = ceil($tickets * $family_percent * $xs_rate);
            $family_price          = floor($tickets * $family_percent * $xs_rate / 10*100)/100;
            $family_damage_price = $tickets * $family_percent * $xs_rate/10 - floor($tickets * $family_percent * $xs_rate / 10*100)/100; //家族折损钱
        } else {
            //三个比例, $author_percent $company_percent, $family_percent
            $xs_rate = 1;
            $family_tickets        = ceil($tickets * $family_percent);
            $family_price          = $tickets * $family_percent / 10;
            $family_damage_price = $tickets * $family_percent/10 - floor($tickets * $family_percent * $xs_rate / 10*100)/100; //家族折损钱
        }

        $company_price   = $tickets * $company_percent / 10 + $tickets * $family_percent * (1-$xs_rate) / 10;

        if ($user_medal) {
            $author_percent_medal_tickets        = ceil($tickets * $author_percent_medal);
            $author_percent_medal_price          = $tickets * $author_percent_medal / 10;
            $author_percent_medal_damage_tickets = $author_percent_medal - $tickets * $author_percent_medal; //家族折损票

        }

        $split_info = array(
            "author" => array("accountid" => $uid, "tickets" => $tickets, "direct" => "decrease", "author" => $uid, "price" => $amount * $author_percent/($author_percent+$author_percent_medal), "author_tickets" => $amount * 10, 'author_percent'=>$author_percent),
            "company" => array("accountid" => Account::COMAPNY_ACCOUNT, "price" => $company_price, "direct" => "increase", 'company_percent'=>$company_percent),
            "damage" => array("accountid" => Account::DAMAGE_ACCOUNT, "tickets" => $damage_tickets, "direct" => "increase"),
        );
        if ($family_percent) {
            $split_info["family"] = array(
                "family_account" => array("accountid" => $family_info['owner'], "account" => $family_info['owner'], "price" => $family_price, "family_tickets" => $family_tickets, 'family_percent'=>$family_percent),
                "family_damage"  => array("accountid" => Account::DAMAGE_ACCOUNT, "price" => $family_damage_price, "direct" => "increase"),
            "xs_rate" => $xs_rate,
                );
        };

        if ($user_medal) {
            $split_info["user_medal"] = array(
                "medal_company" => array("accountid" => Account::COMAPNY_ACCOUNT, "price" => $author_percent_medal_price, "direct" => "decrease"),
                );
        }

        $dao_paybind = new DAOPayBind();
        $dao_paybind = $dao_paybind->getPayBindList($uid);
        if (is_array($dao_paybind)) {
            $paybind_str_defaulted = '';
            foreach ($dao_paybind as $key => $value) {
                if($value['defaulted'] == 'Y') {
                    $paybind_str_defaulted = "{$value[source]}:{$value[account]};";
                }
                $paybind_str .= "{$value[source]}:{$value[account]};";
            }
            if($paybind_str_defaulted) {
                $paybind_str = $paybind_str_defaulted;
            }
        }

        if(!$employe_info['fid']) {
            $employe_info['fid'] = 0;
        }

        try {
            $dao_withdraw = new DAOWithdraw();

            $dao_withdraw->startTrans();

            $account = new Account($uid);
            $account->decrease($uid, Account::TRADE_TYPE_WITHDWAW, $orderid, $tickets, Account::CURRENCY_TICKET, "提现申请(用户id:$uid:提现金额:$amount:提现扣减票:$tickets;提现帐号:$paybind_str;)",  $split_info);

            $dao_withdraw->add($uid, $orderid, $source, $amount, json_encode($split_info), $status, $employe_info['fid'], Account::CURRENCY_TICKET);

            $dao_withdraw->commit();
        } catch (MySQLException $e) {
            $dao_withdraw->rollback();

            throw $e;
        }

        return true;
    }

    public function addCash($uid, $source, $amount)
    {
        //Interceptor::ensureNotFalse(false, ERROR_BIZ_PAYMENT_WITHDRAW_CLOSE);
        //验证提现用户是否为运营号
        $operations     = new DAOOperations();
        $is_check       = $operations->checkUid($uid);
        Interceptor::ensureNotFalse(empty($is_check), ERROR_BIZ_PAYMENT_IS_OPERATION, "userid");
        $dao_withdraw = new DAOWithdraw();
        $total_amount = $dao_withdraw->getWithdrawTodayTotal($uid);
        Interceptor::ensureNotFalse($amount>=10, ERROR_BIZ_PAYMENT_WITHDRAW_CASH_AMOUNT_SMALL, "amount");

        $config = new Config();
        $config_info = $config->getConfig('china', "payment_withdraw", "server", '1.0.0.0');
        $notlimit = array();
        if($config_info) {
            $notlimit = explode("|", $config_info['value']);
        }

        if(in_array($uid, $notlimit)) {
        } else {
            $config = new Config();
            $config_info_max = $config->getConfig('china', "payment_withdraw_max", "server", '1.0.0.0');
            Interceptor::ensureNotFalse(($total_amount['amount']+$amount)<=$config_info_max['value'], ERROR_CUSTOM, "今日提现金额不能大于{$config_info_max['value']}"); 
        }

        $orderid = Account::getOrderId($uid);

        $dao_account = new DAOAccount($uid);
        $account_info = $dao_account->getBalance(Account::CURRENCY_CASH);

        $dao_profile = new DAOProfile($uid);
        $frozen = $dao_profile->getUserProfile('frozen');

        Interceptor::ensureNotFalse((!($frozen == 'Y')), ERROR_BIZ_PAYMENT_WITHDRAW_FROZEN, "uid");
        Interceptor::ensureNotFalse(($account_info >= $amount), ERROR_BIZ_PAYMENT_CASH_BALANCE_DUE, "amount");

        $dao_paybind = new DAOPayBind();
        $dao_paybind = $dao_paybind->getPayBindList($uid);
        if (is_array($dao_paybind)) {
            foreach ($dao_paybind as $key => $value) {
                $paybind_str .= "{$value[source]}:{$value[account]};";
            }
        }

        $dao_employe = new DAOEmploye();
        $employe_info = $dao_employe->getEmployePercent($uid);
        if(!$employe_info['fid']) {
            $employe_info['fid'] = 0;
        }


        try {
            $dao_withdraw = new DAOWithdraw();

            $dao_withdraw->startTrans();

            $split_info = array(
                "type" => 3,
                "remark" => "提现申请(用户id:$uid:提现金额:$amount:提现帐号:$paybind_str;)",
            );

            $account = new Account($uid);
            $account->decrease($uid, Account::TRADE_TYPE_WITHDWAW, $orderid, $amount, Account::CURRENCY_CASH, "提现申请(用户id:$uid:提现金额:$amount:提现帐号:$paybind_str;)",  $split_info);

            $dao_withdraw->add($uid, $orderid, $source, $amount, json_encode($split_info), 2, $employe_info['fid'], Account::CURRENCY_CASH);

            $dao_withdraw->commit();
        } catch (MySQLException $e) {
            $dao_withdraw->rollback();

            throw $e;
        }

        return true;
    }

    public function accept($orderid)
    {
        try {
            $dao_withdraw = new DAOWithdraw();

            $withdraw_info = $this->getWithdrawInfo($orderid);

            $uid = $withdraw_info['uid'];
            $dao_profile = new DAOProfile($uid);
            $frozen = $dao_profile->getUserProfile('frozen');
            $frozen_ticket = $dao_profile->getUserProfile('frozenTicket');

            Interceptor::ensureNotFalse((!($frozen == 'Y')), ERROR_BIZ_PAYMENT_WITHDRAW_FROZEN, "uid"); //帐户冻结的
            Interceptor::ensureNotFalse((!($frozen_ticket == 'Y')), ERROR_BIZ_PAYMENT_TICKET_FROZEN, "uid"); //冻结票


            $extends = json_decode($withdraw_info['extends'], true);

            Interceptor::ensureNotFalse(!in_array($withdraw_info['status'], array(4 ,5)), ERROR_BIZ_PAYMENT_WITHDRAW_EXIST, "status");

            $dao_withdraw->startTrans();

            $dao_withdraw->updateStatus($orderid, 4, "交易成功");

            if ($extends['type'] != 3) {
                if (is_array($extends['family'])) {
                    //家族钱
                    $account = new Account($extends['family']['family_account']['accountid']);
                    $account->increase($extends['family']['family_account']['accountid'], Account::TRADE_TYPE_WITHDWAW, $orderid, $extends['family']['family_account']['price'], Account::CURRENCY_CASH, "提现申请-家族收钱(订单:$orderid;家族id:{$extends['family']['family_account']['accountid']};金额:{$extends['family']['family_account']['price']});",  $extends['family']['family_account']);
                    
                    if($extends['family']['family_damage']['price']) {
                        //家族折损
                        $account_family_damage = new Account($extends['family']['family_damage']['accountid']);
                        $account_family_damage->increase($extends['family']['family_damage']['accountid'], Account::TRADE_TYPE_WITHDWAW, $orderid, $extends['family']['family_damage']['price'], Account::CURRENCY_CASH, "提现申请-家族折损(订单:$orderid;折损帐户:{$extends['family']['family_damage']['accountid']};折损票:{$extends['family']['family_damage']['price']})",  $extends['family']['family_damage']);

                    }
                    
                }

                //公司钱
                $account = new Account($extends['company']['accountid']);
                $account->increase($extends['company']['accountid'], Account::TRADE_TYPE_WITHDWAW, $orderid, $extends['company']['price'], Account::CURRENCY_CASH, "提现申请-公司现金(订单号:$orderid;帐号:{$extends['company']['accountid']};金额:{$extends['company']['price']})",  $extends['company']);

                if($extends['damage']['accountid']) {
                    //折损
                    $account = new Account($extends['damage']['accountid']);
                    $account->increase($extends['damage']['accountid'], Account::TRADE_TYPE_WITHDWAW, $orderid, $extends['damage']['tickets'], Account::CURRENCY_TICKET, "提现申请-公司折损(订单:$orderid;折损帐户:{$extends['damage']['accountid']};折损票:{$extends['damage']['tickets']})",  $extends['damage']);
                }

                if (is_array($extends['user_medal'])) {
                    //用户勋章 => 扣减公司帐户的收入
                    $account = new Account($extends['user_medal']['medal_company']['accountid']);
                    $account->decrease($extends['user_medal']['medal_company']['accountid'], Account::TRADE_TYPE_WITHDWAW, $orderid, $extends['user_medal']['medal_company']['price'], Account::CURRENCY_CASH, "提现申请-用户勋章(订单号:$orderid;金额:{$extends['user_medal']['medal_company']['price']})",  $extends['user_medal']);
                }


            }

            $dao_withdraw->commit();

            $withdraw_info = $this->getWithdrawInfo($orderid);
            Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $withdraw_info['uid'], "您的提现【订单号{$orderid}】已通过", "您的提现【订单号{$orderid}】，已通过审核并打款，1-3日内注意查收", 0, $extends);
        } catch (MySQLException $e) {
            $dao_withdraw->rollback();

            throw $e;
        }

        return true;
    }


    public function reject($orderid, $reason)
    {
        try {
            $dao_withdraw = new DAOWithdraw();

            $withdraw_info = $this->getWithdrawInfo($orderid);

            $extends = json_decode($withdraw_info['extends'], true);

            Interceptor::ensureNotFalse(!in_array($withdraw_info['status'], array(4 ,5)), ERROR_BIZ_PAYMENT_WITHDRAW_EXIST, "status");

            $dao_withdraw->startTrans();

            $dao_withdraw->updateStatus($orderid, 5, $reason);

            if ($extends['type'] != 3) {
                $uid     = $extends['author']['accountid'];
                $tickets = $extends['author']['tickets'];

                $account = new Account($uid);
                $account->increase($uid, Account::TRADE_TYPE_WITHDWAW, $orderid, $tickets, Account::CURRENCY_TICKET, "提现申请-拒绝$reason",  array());
            } else {
                $uid = $withdraw_info['uid'];
                $account = new Account($uid);
                $account->increase($uid, Account::TRADE_TYPE_WITHDWAW, $orderid, $withdraw_info['amount'], Account::CURRENCY_CASH, "提现申请-拒绝$reason",  array());

            }

            $dao_withdraw->commit();

            Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $uid, "您的提现【订单号{$orderid}】已拒绝", "您的提现【订单号{$orderid}】，已拒绝, 理由: $reason.", 0, $extends);

        } catch (MySQLException $e) {
            $dao_withdraw->rollback();

            throw $e;
        }

        return true;
    }

    public static function getWithdrawList($uid, $offset, $num)
    {
        $dao_withdraw = new DAOWithdraw();
        $list        = $dao_withdraw->getWithdrawList($uid, $offset, $num);
        $total       = $dao_withdraw->getWithdrawNum($uid);

        foreach ($list as $value) {
            $offset = $value['id'];
        }

        return array($total, $list, $offset);
    }

    public function getWithdrawInfo($orderid)
    {
        $dao_withdraw  = new DAOWithdraw();
        $withdraw_info = $dao_withdraw->getWithdrawInfo($orderid);

        return $withdraw_info;
    }

    public static function getWithdrawPrice($uid)
    {
        $dao_account = new DAOAccount($uid);
        $ticket      = $dao_account->getBalance(Account::CURRENCY_TICKET);

        $dao_employe = new DAOEmploye();
        $employe_info = $dao_employe->getEmployePercent($uid);

        $familyid = $employe_info['fid'];

        if ($dao_employe->isEmploye($uid)) {

            $dao_family = new DAOFamily();
            $family_info = $dao_family->getFamilyInfoById($familyid);

            $percent = $family_info["family_percent"];

            $employe_info = $dao_employe->getEmployeInfo($familyid, $uid);

            $employe_percent = $employe_info["author_percent"];

            $author_percent = $percent/100 * $employe_percent/100;
        } else {
            $config = new Config();
            $config_info = $config->getConfig(Context::get("region"), "payment", "server", '1.0.0.0');
            $author_percent = $config_info['passerby'];
            if (!isset($author_percent)) {
                $author_percent = 0.4;
            }
        }

        $dao_user_medal = new DAOUserMedal($uid);
        $user_medal = $dao_user_medal->getUserMedal("founder");

        $author_percent_medal = 0;
        if ($user_medal) {
            $author_percent_medal = 0.02;
        }

        return array('ticket'=>(int)$ticket,
                    'total_price'=>floor($ticket * ($author_percent+$author_percent_medal)/10),
                    'price'=>floor($ticket/10 * $author_percent),
                    'medal'=>floor($ticket/10 * $author_percent_medal),
                    'percent'=>array('author'=>$author_percent+$author_percent_medal, 'family'=>$percent/100, 'employe'=>$employe_percent/100, 'author_percent_medal' => $author_percent_medal));
    }


    public function updateStatus($orderid, $status, $reason)
    {
        $dao_withdraw = new DAOWithdraw();

        return $dao_withdraw->updateStatus($orderid, $status, $reason);
    }

    const SIGN_SECRET = 'JHG*fiop*&JU*OO%&*B$()%GHKb*K#hT';
    public static function getSign($uid, $phone, $code)
    {
        $sign = md5($uid . $phone . $code .self::SIGN_SECRET.time());

        $key = "withdraw:code:" . $uid;
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $cache->set($key, $sign, 600);

        return $sign;
    }

    public static function checkSign($uid, $sign)
    {
        $key = "withdraw:code:" . $uid;
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $data = $cache->get($key);

        if (false === $data) {
            return false;
        }elseif($sign  === $data) {
            return true;
        }

        return false;
    }

    private function getSplit($uid)
    {
        // 计算金额
        $dao_employe = new DAOEmploye();
        $employe_info = $dao_employe->getEmployePercent($uid);

        if(!$employe_info) {
            throw new BizException(ERROR_BIZ_PAYMENT_FAMILY_ISNOTEMPLOYE);
        }

        $dao_family = new DAOFamily();
        $family_info = $dao_family->getFamilyInfoById($employe_info['fid']);
        $merge_percent   = $family_info["family_percent"] / 100;
        $employe_percent = $employe_info["percent"] / 100;
        $author_percent = $merge_percent * $employe_percent;

        $dao_user_medal = new DAOUserMedal($uid);
        $user_medal = $dao_user_medal->getUserMedal("founder");
        $author_percent_medal = 0;
        if ($user_medal) {
            $author_percent_medal = 0.02;
        }

        $dao_account = new DAOAccount($uid);
        $account_info = $dao_account->getBalance(Account::CURRENCY_TICKET);

        // 可以兑换的所有现金
        $amount = floor($account_info * ($author_percent + $author_percent_medal) / 10);

        if($amount <= 0) {
            return [];
        }

        $tickets = ceil($amount * 10 / ($author_percent + $author_percent_medal)); //总票
        $damage_tickets = $tickets - $amount * 10 / ($author_percent + $author_percent_medal); //折损票

        //公司占比
        $company_percent = 1 - $merge_percent;
        //家族占比
        $family_percent = 1 - $company_percent - $author_percent;


        $family_tickets        = ceil($tickets * $family_percent);
        $family_price          = $tickets * $family_percent / 10;
        $family_damage_tickets = $family_tickets - $tickets * $family_percent; //家族折损票

        $company_price   = $tickets * $company_percent / 10;

        if ($user_medal) {
            $author_percent_medal_tickets        = ceil($tickets * $author_percent_medal);
            $author_percent_medal_price          = $tickets * $author_percent_medal / 10;
            $author_percent_medal_damage_tickets = $author_percent_medal - $tickets * $author_percent_medal;
        }

        $split_info = array(
            "author" => array("accountid" => $uid, "tickets" => $tickets, "direct" => "decrease", "author" => $uid, "price" => $amount * $author_percent/($author_percent+$author_percent_medal), "author_tickets" => $amount * 10, 'author_percent'=>$author_percent),
            "company" => array("accountid" => Account::COMAPNY_ACCOUNT, "price" => $company_price, "direct" => "increase", 'company_percent'=>$company_percent),
            "damage" => array("accountid" => Account::DAMAGE_ACCOUNT, "tickets" => $damage_tickets, "direct" => "increase"),
        );
        if ($family_percent) {
            $split_info["family"] = array(
                "family_account" => array("accountid" => $family_info['owner'], "account" => $family_info['owner'], "price" => $family_price, "family_tickets" => $family_tickets, 'family_percent' => $family_percent, 'familyid' => $employe_info['fid']),
                "family_damage"  => array("accountid" => Account::DAMAGE_ACCOUNT, "tickets" => $family_damage_tickets, "direct" => "increase"),
            );
        };

        if ($user_medal) {
            $split_info["user_medal"] = array(
                "medal_company" => array("accountid" => Account::COMAPNY_ACCOUNT, "price" => $author_percent_medal_price, "direct" => "decrease"),
            );
        }

        $split_info['user'] = array("accountid" => $uid, "direct" => "increase", "price" => $amount);

        return $split_info;
    }

    private function authorIncome($uid, $extends)
    {
        $status = 5;
        $source = 'alipay';

        $dao_withdraw = new DAOWithdraw();
        $orderid = Account::getOrderId($uid);

        $tickets = $extends['author']['tickets'];

        $text = [
            "[familyid:{$extends['family']['family_account']['familyid']}]",
            "[author:{$uid}:{$extends['author']['tickets']}票]",
            "[user:{$extends['user']['accountid']}:{$extends['user']['price']}元]",
        ];

        if(array_key_exists('family', $extends)) {
            $text[] = "[family:{$extends['family']['family_account']['accountid']}:{$extends['family']['family_account']['price']}元]";
        }

        $text = implode("", $text);

        try {
            $dao_withdraw->startTrans();
            // 主播扣票
            Account::decrease($uid, Account::TRADE_TYPE_WITHDWAW, $orderid, $tickets, Account::CURRENCY_TICKET, "家族解约-主播扣票-{$text}", $extends);

            if(array_key_exists('family', $extends)) {
                //家族钱
                Account::increase($extends['family']['family_account']['accountid'], Account::TRADE_TYPE_WITHDWAW, $orderid, $extends['family']['family_account']['price'], Account::CURRENCY_CASH, "家族解约-家族分成-{$text}", $extends);
            }

            //公司钱
            Account::increase($extends['company']['accountid'], Account::TRADE_TYPE_WITHDWAW, $orderid, $extends['company']['price'], Account::CURRENCY_CASH, "家族解约-平台收入-{$text}",  $extends);

            //折损
            Account::increase($extends['damage']['accountid'], Account::TRADE_TYPE_WITHDWAW, $orderid, $extends['damage']['tickets'], Account::CURRENCY_TICKET, "家族解约-折损-{$text}",  $extends);

            if (is_array($extends['user_medal'])) {
                //用户勋章 => 扣减公司帐户的收入
                Account::decrease($extends['user_medal']['medal_company']['accountid'], Account::TRADE_TYPE_WITHDWAW, $orderid, $extends['user_medal']['medal_company']['price'], Account::CURRENCY_CASH, "家族解约-用户勋章扣减平台-[familyid:{$extends['family']['family_account']['familyid']}]",  $extends);
            }

            // 主播增加现金收入
            Account::increase($extends['user']['accountid'], Account::TRADE_TYPE_WITHDWAW, $orderid, $extends['user']['price'], Account::CURRENCY_CASH, "家族解约-主播现金分成-{$text}", $extends);

            $dao_withdraw->add($uid, $orderid, $source, $extends['user']['price'], json_encode($extends), $status);

            $dao_withdraw->commit();
        } catch (MySQLException $e) {
            $dao_withdraw->rollback();

            throw $e;
        }
    }

    public function familyEmplyeRelease($uid)
    {
        $extends = $this->getSplit($uid);
        if($extends) {
            $this->authorIncome($uid, $extends);
        }

        return $extends;
    }
    
    //通过familyid得到所有人的票
    public function countFamilyTicket($familyid)
    {
        $family       = new DAOFamily();
        $family_info  = $family->getFamilyInfoById($familyid);

        Interceptor::ensureNotEmpty($family_info, ERROR_PARAM_INVALID_FORMAT, 'familyid');
        Interceptor::ensureNotFalse($family_info['corporate'] === 'Y', ERROR_PARAM_INVALID_FORMAT, 'familyid');

        $employe      = new DAOEmploye();
        $employe_info = $employe->getAllEmployeByFamilyid($familyid);
        $sum          = 0;
        if($family_info['owner'] && is_array($employe_info)) {
            //所有的票都转入主id
            $account = new Account();
            
            foreach ($employe_info as $key => $value) {
                $sum += $account->getBalance($value['authorid'], ACCOUNT::CURRENCY_TICKET);
            }
        }

        return array('ticket'=>$sum, 'family_percent'=>$family_info['family_percent']/100);

    }
    

    //所有票转入家族id
    public static function switchTo($familyid)
    {
        $family       = new DAOFamily();
        $family_info  = $family->getFamilyInfoById($familyid);
        $employe      = new DAOEmploye();
        $employe_info = $employe->getAllEmployeByFamilyid($familyid);
        $orderid      = 0;
        $sum          = 0;
        if($family_info['owner'] && is_array($employe_info)) {
            //所有的票都转入主id
            $account = new Account();
            $orderid = $account->getOrderId($family_info['owner']);
            $dao_gift_log = new DAOGiftLog();
            try {
                $dao_gift_log->startTrans();
                foreach ($employe_info as $key => $value) {
                    $type    = 51;
                    $ticket = $account->getBalance($value['authorid'], ACCOUNT::CURRENCY_TICKET);
                    if($ticket >= 500) { //小于500的票不结算
                        if(self::isSex($value['authorid'])) { //对于公对公的公会，在每周自动结算时，xs主播的票进入公会长账户时，也是扣除19%的票后进入公会长账户，然后正常结算；
                            $xs_rate = 1;
                        } else {
                            $xs_rate = 1;
                        }
                        $extends = [
                        'family_uid' => $family_info['owner'],
                        'employe_uid' => $value['authorid'],
                        'ticket' => $ticket,
                        'orderid' => $orderid,
                        'xs_rate' => $xs_rate,
                        ];

                        $account->decrease($value['authorid'], $type, $orderid, $ticket, ACCOUNT::CURRENCY_TICKET, "家族公对公, 将用户{$value['authorid']}的票{$ticket}收到{$family_info['owner']}家族长的票", $extends);

                        $company_ticket = 0;
                        $family_ticket  = 0;

                        if($xs_rate < 1) {
                            $family_ticket = floor($ticket * $xs_rate);
                            $company_ticket = $ticket - $family_ticket;
                        } else {
                            $family_ticket = $ticket;
                        }

                        $account->increase($family_info['owner'], $type, $orderid, $family_ticket, ACCOUNT::CURRENCY_TICKET, "家族公对公, 将用户{$value['authorid']}的票{$ticket}收到{$family_info['owner']}家族长的票", $extends);

                        if($company_ticket) {
                            $account->increase(Account::COMAPNY_ACCOUNT, $type, $orderid, $company_ticket, ACCOUNT::CURRENCY_TICKET, "家族公对公, xs主播在转家族票时直接收税{$xs_rate}，票{$company_ticket}. 主播id:{$value['authorid']}. 家族长id:{$family_info['owner']}.", $extends);
                        }

                        $sum += $family_ticket;
                    }
                }
                $dao_gift_log->commit();
            } catch (Exception $e) {
                $dao_gift_log->rollback();
                throw $e;
            }
        }

        return array('ticket'=>$sum, 'family_percent'=>$family_info['family_percent']/100, 'family_uid'=>$family_info['owner'], 'orderid'=>$orderid);
    }

    
    public function familyTicketToAmount($familyid, $family_ticket)
    {
        $family_amount = floor($family_ticket['ticket'] * $family_ticket['family_percent'] / 10 * 1000)/1000; //家族所得的钱
        $company_amount = floor($family_ticket['ticket'] * (1-$family_ticket['family_percent']) / 10 * 1000)/1000; //公司所得的钱
        $uid = $family_ticket['family_uid'];

        if($family_ticket['ticket']) {
            $account = new Account();
            $dao_gift_log = new DAOGiftLog();
            try {
                $dao_gift_log->startTrans();
                $orderid = $account->getOrderId($uid);
                $type    = 51;
                $ticket  = $family_ticket['ticket'];
                $extends = [
                 'family'=>$familyid,
                 'family_uid' => $uid,
                 'ticket' => $ticket,
                 'orderid' => $orderid,
                 'family_amount' => $family_amount,
                 'company_amount' => $company_amount,
                ];
                $account->decrease($uid, $type, $orderid, $ticket, ACCOUNT::CURRENCY_TICKET, "家族公对公转非公对公, 家族id:$familyid, 将用户{$uid}的票{$ticket}转化成现金$family_amount, 公司收益$company_amount", $extends);
                $account->increase($uid, $type, $orderid, $family_amount, ACCOUNT::CURRENCY_CASH, "家族公对公转非公对公, 家族id:$familyid, 将用户{$uid}的票{$ticket}转化成现金$family_amount, 公司收益$company_amount", $extends);
                $account->increase(ACCOUNT::COMAPNY_ACCOUNT, $type, $orderid, $family_amount, ACCOUNT::CURRENCY_CASH, "家族公对公转非公对公, 家族id:$familyid, 将用户{$uid}的票{$ticket}转化成现金$family_amount, 公司收益$company_amount", $extends);
                $dao_gift_log->commit();
            } catch (Exception $e) {
                $dao_gift_log->rollback();
                throw $e;
            }
        } else {
            $extends = [
            'family'=>$familyid,
            'family_uid' => $uid,
            'ticket' => 0,
            'orderid' => 0,
            'family_amount' => 0,
            'company_amount' => 0,
            ];
        }

        return $extends;
    }

    //公对公结算申请
    public function familyAdd($familyid, $uid, $source, $amount, $admin, $orderid)
    {

        $config = new Config();
        $config_info = $config->getConfig('china', "payment_withdraw", "server", '1.0.0.0');

        $dao_withdraw = new DAOWithdraw();
        $total_amount = $dao_withdraw->getWithdrawTodayTotal($uid);
        $notlimit = array();
        if($config_info) {
            $notlimit = explode("|", $config_info['value']);
        }

        $corporate = new Employe();
        Interceptor::ensureNotFalse($corporate->isCorporate($uid), ERROR_BIZ_PAYMENT_WITHDRAW_NO_POWER, "uid");

        if(!$orderid) {
            $orderid = Account::getOrderId($uid);
        }

        $dao_account  = new DAOAccount($uid);
        $account_info = $dao_account->getBalance(Account::CURRENCY_TICKET);
        $tickets      = $account_info;


        $dao_employe = new DAOEmploye();
        $employe_info = $dao_employe->getEmployePercent($uid);

        $dao_family     = new DAOFamily();
        $family_info    = $dao_family->getFamilyInfoById($employe_info['fid']);
        $author_percent = $family_info["family_percent"]/100;

        $dao_profile = new DAOProfile($uid);
        $frozen = $dao_profile->getUserProfile('frozen');
        $frozen_ticket = $dao_profile->getUserProfile('frozenTicket');

        Interceptor::ensureNotFalse((!($frozen == 'Y')), ERROR_BIZ_PAYMENT_WITHDRAW_FROZEN, "uid");
        Interceptor::ensureNotFalse(($tickets > 0), ERROR_BIZ_PAYMENT_TICKET_BALANCE_DUE, "amount");
        //Interceptor::ensureNotFalse((!(isset($employe_info['frozen']) && $employe_info['frozen'] == 'Y')), ERROR_BIZ_PAYMENT_WITHDRAW_FAMILY_FROZEN, "uid");

        $company_percent = 1 - $author_percent;
        $status = 2; //运营审核

        //两个比例, $author_percent $company_percent
        $company_price   = $tickets * $company_percent / 10;
        $amount          = $tickets * $author_percent / 10;


        $split_info = array(
        'familyid' => $familyid,
            "author" => array("accountid" => $uid, "tickets" => $tickets, "direct" => "decrease", "author" => $uid, "price" => $amount, "author_tickets" => $amount * 10, 'author_percent'=>$author_percent),
            "company" => array("accountid" => Account::COMAPNY_ACCOUNT, "price" => $company_price, "direct" => "increase", 'company_percent'=>$company_percent),
        );

        $dao_corporate = new DAOWithdrawCorporate();
        $corporate_info = $dao_corporate->get($familyid);

        if(isset($corporate_info['id'])) {
            if($corporate_info['pay_percent']) { //实付比例
                $pay_percent = $corporate_info['pay_percent'];
                $split_info['author']['pay_percent'] = $pay_percent; //实付比例
                $split_info['author']['pay_amount'] = floor($split_info['author']['price'] * $pay_percent * 100)/100; //保留两位小数其他的舍去

                $split_info['author']['pay_amount_damage'] = $split_info['author']['price'] * $pay_percent - floor($split_info['author']['price'] * $pay_percent * 100)/100; //折损的钱
            }

            if($corporate_info['three_pay_percent']) { //三方实付比例
                $three_pay_percent = $corporate_info['three_pay_percent'];
                $split_info['author']['three_pay_percent'] = $three_pay_percent; //实付比例
                $split_info['author']['three_pay_amount'] = floor($split_info['author']['price'] * $three_pay_percent * 100)/100; //保留两位小数其他的舍去


                $split_info['author']['three_pay_amount_damage'] = $split_info['author']['price'] * $three_pay_percent - floor($split_info['author']['price'] * $three_pay_percent * 100)/100; //折损的钱
            }
            $split_info['damage']['price'] = $split_info['author']['pay_amount_damage'];
            if($split_info['author']['three_pay_amount_damage']) {
                $split_info['damage']['price'] = $split_info['author']['three_pay_amount_damage'];
            }
        }


        $dao_paybind = new DAOPayBind();
        $dao_paybind = $dao_paybind->getPayBindList($uid);
        if (is_array($dao_paybind)) {
            $paybind_str_defaulted = '';
            foreach ($dao_paybind as $key => $value) {
                if($value['defaulted'] == 'Y') {
                    $paybind_str_defaulted = "{$value[source]}:{$value[account]};";
                }
                $paybind_str .= "{$value[source]}:{$value[account]};";
            }
            if($paybind_str_defaulted) {
                $paybind_str = $paybind_str_defaulted;
            }
        }

        try {
            $dao_withdraw = new DAOWithdraw();
            $dao_withdraw_familysettlement = new DAOWithdrawFamilysettlement();

            $dao_withdraw->startTrans();

            $account = new Account($uid);
            $account->decrease($uid, Account::TRADE_TYPE_WITHDWAW, $orderid, $tickets, Account::CURRENCY_TICKET, "家族公对公提现申请(家族id:$familyid.用户id:$uid:提现金额:$amount:提现扣减票:$tickets;提现帐号:$paybind_str;)",  $split_info);
            

            $withdrawid = $dao_withdraw->add($uid, $orderid, $source, $amount, json_encode($split_info), $status, $employe_info['fid'], Account::CURRENCY_TICKET, 'Y');
            $withdraw_family = $dao_withdraw_familysettlement->add($withdrawid, $orderid, $familyid, $family_info['name'], $family_info['owner'], $family_info['family_percent'], $tickets, $amount, $admin);

            $dao_withdraw->commit();
        } catch (MySQLException $e) {
            $dao_withdraw->rollback();

            throw $e;
        }

        $result = $split_info;
        $result['orderid'] = $orderid;

        return $split_info;
    }

    //非公对公转公对公：执行对该工会旗下所有主播的账户可提现星票的强制结算，将星票按照每个主播的有效提现比例折算到其现金账户中
    public function familyNoCorporateChange($familyid)
    {
        //| 1. 有主播在提现过程中的判断
        $employe      = new DAOEmploye();
        $employe_info = $employe->getAllEmployeByFamilyid($familyid);
        $uid_arr = array();
        if(is_array($employe_info)) {
            foreach ($employe_info as $key => $value) {
                $uid_arr[] = $value['authorid'];
            }
        }
        $dao_withdraw_familysettlement = new DAOWithdrawFamilysettlement();

        $employe_data = $dao_withdraw_familysettlement->getWithdrawApply(implode(",", $uid_arr));

        Interceptor::ensureNotFalse(count($employe_data)<1, ERROR_BIZ_PAYMENT_WITHDRAW_APPLY);

        //| 2. 票发起提现. 
        if(is_array($employe_info)) {

            $dao_family = new DAOFamily();
            $family_info = $dao_family->getFamilyInfoById($familyid);

            $dao_withdraw = new DAOWithdraw();
            $orderid = Account::getOrderId($family_info['owner']);
            try {
                $dao_withdraw->startTrans();
                foreach ($employe_info as $key => $value) {
                    $uid = $value['authorid'];
                    $extends = $this->getSplit($uid);
                    $tickets = $extends['author']['tickets'];
                    if($tickets) {
                        $text = [
                        "[familyid:{$extends['family']['family_account']['familyid']}]",
                        "[author:{$uid}:{$extends['author']['tickets']}票]",
                        "[user:{$extends['user']['accountid']}:{$extends['user']['price']}元]",
                        ];

                        if(array_key_exists('family', $extends)) {
                            $text[] = "[family:{$extends['family']['family_account']['accountid']}:{$extends['family']['family_account']['price']}元]";
                        }
                        $text = implode("", $text);

                        //主播扣票
                        Account::decrease($uid, Account::TRADE_TYPE_FAMILY, $orderid, $tickets, Account::CURRENCY_TICKET, "非公对公转公对公-主播扣票-{$text}", $extends);

                        if(array_key_exists('family', $extends)) {
                            //家族钱
                            Account::increase($extends['family']['family_account']['accountid'], Account::TRADE_TYPE_FAMILY, $orderid, $extends['family']['family_account']['price'], Account::CURRENCY_CASH, "非公对公转公对公-家族分成-{$text}", $extends);
                        }

                        //公司钱
                        Account::increase($extends['company']['accountid'], Account::TRADE_TYPE_FAMILY, $orderid, $extends['company']['price'], Account::CURRENCY_CASH, "非公对公转公对公-平台收入-{$text}",  $extends);

                        //折损
                        Account::increase($extends['damage']['accountid'], Account::TRADE_TYPE_FAMILY, $orderid, $extends['damage']['tickets'], Account::CURRENCY_TICKET, "非公对公转公对公-折损-{$text}",  $extends);

                        if (is_array($extends['user_medal'])) {
                            //用户勋章 => 扣减公司帐户的收入
                            Account::decrease($extends['user_medal']['medal_company']['accountid'], Account::TRADE_TYPE_FAMILY, $orderid, $extends['user_medal']['medal_company']['price'], Account::CURRENCY_CASH, "非公对公转公对公-用户勋章扣减平台-[familyid:{$extends['family']['family_account']['familyid']}]",  $extends);
                        }

                        //主播增加现金收入
                        Account::increase($extends['user']['accountid'], Account::TRADE_TYPE_FAMILY, $orderid, $extends['user']['price'], Account::CURRENCY_CASH, "非公对公转公对公-主播现金分成-{$text}", $extends);
                    }
                }
                $dao_withdraw->commit();
            } catch (MySQLException $e) {
                $dao_withdraw->rollback();

                throw $e;
            }

            return ['orderid' => $orderid];
        }
        

    }

    //得到uid的分成比例
    public function get_author_percent($uid)
    {
        $config = new Config();
        $config_info = $config->getConfig('china', "payment_withdraw", "server", '1.0.0.0');

        $dao_employe = new DAOEmploye();
        $employe_info = $dao_employe->getEmployePercent($uid);

        if ($dao_employe->isEmploye($uid)) {
            $dao_family = new DAOFamily();
            $family_info = $dao_family->getFamilyInfoById($employe_info['fid']);
            $merge_percent   = $family_info["family_percent"]/100;
            $employe_percent = $employe_info["percent"]/100;
            $author_percent = $merge_percent * $employe_percent;
        } else {
            $config = new Config();
            $config_info = $config->getConfig('china', "payment", "server", '1.0.0.0');
            $author_percent = $config_info['passerby'];
            if (!isset($author_percent)) {
                $author_percent = 0.4;
            }
        }

        $dao_user_medal = new DAOUserMedal($uid);
        $user_medal = $dao_user_medal->getUserMedal("founder");
        $author_percent_medal = 0;
        if ($user_medal) {
            $author_percent_medal = 0.02;
        }

        if ($dao_employe->isEmploye($uid)) {
            //公司占比
            $company_percent = 1 - $merge_percent;
            //家族占比
            $family_percent = $merge_percent;
        } else {
            $company_percent = 1 - $author_percent;
        }

        //三个比例, $author_percent $company_percent, $family_percent
        $split_info = array(
            "author" => $author_percent,
            "company" => $company_percent,
        );
        if ($family_percent) {
            $split_info["family"] = $family_percent;
        };

        if ($user_medal) {
            $split_info["user_medal"] = $author_percent_medal;
        }

        return $split_info;
    }

    //自由主播加入到工会时， 结算主播的票到主播的资金. 
    public function addToFamily($uid)
    {
        $dao_employe = new DAOEmploye();
        $employe_info = $dao_employe->getEmployePercent($uid);

        if($employe_info) {
            throw new BizException(ERROR_BIZ_PAYMENT_FAMILY_ISEMPLOYE);
        }

        $dao_account = new DAOAccount($uid);
        $tickets = $dao_account->getBalance(Account::CURRENCY_TICKET);
        
        $config = new Config();
        $config_info = $config->getConfig('china', "payment", "server", '1.0.0.0');
        $author_percent = $config_info['passerby'];
        if (!isset($author_percent)) {
            $author_percent = 0.4;
        }

        $dao_user_medal = new DAOUserMedal($uid);
        $user_medal = $dao_user_medal->getUserMedal("founder");
        if ($user_medal) {
            $author_percent += 0.02;
        }

        $dao_profile = new DAOProfile($uid);
        $frozen = $dao_profile->getUserProfile('frozen');
        $frozen_ticket = $dao_profile->getUserProfile('frozenTicket');

        Interceptor::ensureNotFalse((!($frozen == 'Y')), ERROR_BIZ_PAYMENT_WITHDRAW_FROZEN, "uid");
        Interceptor::ensureNotFalse((!($frozen_ticket == 'Y')), ERROR_BIZ_PAYMENT_TICKET_FROZEN, "uid"); //冻结票
        
        $company_percent = 1 - $author_percent;
        $extends = array();

        if($tickets > 0) {
            $author_amount  = $tickets * $author_percent/10;
            $company_amount = $tickets * $company_percent/10;

            $dao_withdraw = new DAOWithdraw();
            $orderid = Account::getOrderId($uid);
            try {
                $dao_withdraw->startTrans();

                $text = "[author:{$uid}:{$tickets}票],
							[author_percent:$author_percent;company_percent:$company_percent],
							[author_amount:$author_amount;company_amount:$company_amount]";
                $extends = array('tickets'=>$tickets, 'author_percent'=>$author_percent, 'company_percent'=>$company_percent, 'author_amount'=>$author_amount, 'company_amount'=>$company_amount);
                //主播扣票
                Account::decrease($uid, Account::TRADE_TYPE_FAMILY, $orderid, $tickets, Account::CURRENCY_TICKET, "加入公会时对自由主播的票进行结算-{$text}", $extends);
                //主播增加现金收入
                Account::increase($uid, Account::TRADE_TYPE_FAMILY, $orderid, $author_amount, Account::CURRENCY_CASH, "加入公会时对自由主播的票进行结算-{$text}", $extends);
                //公司钱
                Account::increase(Account::COMAPNY_ACCOUNT, Account::TRADE_TYPE_FAMILY, $orderid, $company_amount, Account::CURRENCY_CASH, "加入公会时对自由主播的票进行结算-{$text}",  $extends);

                $dao_withdraw->commit();
            } catch (MySQLException $e) {
                $dao_withdraw->rollback();

                throw $e;
            }

        } else {
            $extends = array('tickets'=>0);
        }

        return $extends;
    }

    //公对公提现参数编辑, 同时更新订单数据
    public function corporateEdit($orderid, $familyid,$author_percent,$pay_percent,$three_pay_percent,$is_receipt,$is_receipt_real, $rate, $settlement)
    {
        //|================================================================================================|
        //| 1. 公对公提现参数编辑
        //| 2. 同时更新订单数据
        //| 
        $DAOWithdrawCorporate = new DAOWithdrawCorporate();
        //1. 公对公提现参数编辑
        $DAOWithdrawCorporate->replaceinto($familyid, $author_percent, $pay_percent, $three_pay_percent, $is_receipt, $is_receipt_real, $rate, $settlement);

        if($orderid>1) {
            //| 2. 同时更新订单数据
            $DAOWithdraw = new DAOWithdraw();
            $data = $DAOWithdraw->getWithdrawInfo($orderid);
            $extends = json_decode($data['extends'], true);
            if($author_percent) { //家族比例改变
                $extends['author']['author_percent'] = $author_percent; //比例
                $extends['author']['price'] = $extends['author']['tickets'] * $author_percent/10;
                $extends['author']['author_tickets'] = $extends['author']['tickets'] * $author_percent; //个人所得票
                

                $extends['company']['company_percent'] = 1 - $author_percent; //公司比例
                $extends['company']['price'] = $extends['company']['company_percent'] * $extends['author']['tickets']/10; //公司所得钱
            }

            if($pay_percent) { //实付比例
                $extends['author']['pay_percent'] = $pay_percent; //实付比例
                $extends['author']['pay_amount'] = floor($extends['author']['price'] * $pay_percent * 100)/100; //保留两位小数其他的舍去

                $extends['author']['pay_amount_damage'] = $extends['author']['price'] * $pay_percent - floor($extends['author']['price'] * $pay_percent * 100)/100; //折损的钱
            }

            if($three_pay_percent) { //三方实付比例
                $extends['author']['three_pay_percent'] = $three_pay_percent; //实付比例
                $extends['author']['three_pay_amount'] = floor($extends['author']['price'] * $three_pay_percent * 100)/100; //保留两位小数其他的舍去


                $extends['author']['three_pay_amount_damage'] = $extends['author']['price'] * $three_pay_percent - floor($extends['author']['price'] * $three_pay_percent * 100)/100; //折损的钱
            }
            if($extends['author']['three_pay_amount_damage']) {
                $extends['damage']['price'] = $extends['author']['three_pay_amount_damage'];
            } else {
                $extends['damage']['price'] = $extends['author']['pay_amount_damage'];
            }
            
            $extends_json = json_encode($extends);

            $DAOWithdraw->updateExtends($orderid, $extends_json, $extends['author']['price']);
        }
        

        return true;
    }

    //公对公提现参数编辑, 同时更新订单数据
    public function corporateEditDetail($id, $real_tickets)
    {
        
        $DAOWithdraw = new DAOWithdraw();
        $data = $DAOWithdraw->getWithdrawInfoById($id);
        $extends_arr = json_decode($data['extends'], true);
        $extends_arr['detail'] = $real_tickets;
        
        $real_tickets_arr = json_decode($real_tickets, true);

        $s = 0;
        foreach ($real_tickets_arr as $key => $value) {
            $s = $s + $value[0];
        }

        $extends_arr['real_tickets'] = $s;


        $extends_str = json_encode($extends_arr);

        return $DAOWithdraw->updateExtendsDetail($id, $extends_str);
    }

    public function corporateAccept($orderid)
    {
        try {
            $dao_withdraw = new DAOWithdraw();
            

            $withdraw_info = $this->getWithdrawInfo($orderid);
            $uid = $withdraw_info['uid'];
            $dao_profile = new DAOProfile($uid);
            $frozen = $dao_profile->getUserProfile('frozen');
            $frozen_ticket = $dao_profile->getUserProfile('frozenTicket');

            Interceptor::ensureNotFalse((!($frozen == 'Y')), ERROR_BIZ_PAYMENT_WITHDRAW_FROZEN, "uid"); //帐户冻结的
            Interceptor::ensureNotFalse((!($frozen_ticket == 'Y')), ERROR_BIZ_PAYMENT_TICKET_FROZEN, "uid"); //冻结票

            $extends = json_decode($withdraw_info['extends'], true);
            $dao_journal = new DAOJournal($uid);

            Interceptor::ensureNotFalse(!in_array($withdraw_info['status'], array(4 ,5)), ERROR_BIZ_PAYMENT_WITHDRAW_EXIST, "status");

            $dao_withdraw->startTrans();

            $detail = json_decode($extends['detail'], true);
            if(is_array($detail)) {
                foreach ($detail as $key => $value) {
                    if($value[3]!=$value[0]) {
                        $remark = "充帐记录: 提现数据审核扣减票数:" . ($value[3] - $value[0]) . ". 扣减原因: " . $value[1];
                        $dao_journal->add($orderid, $uid, 51, 'OUT', 1, 0, $remark, $value);
                    }
                }

                foreach ($detail as $key => $value) {
                    if($value[3]!=$value[0]) {
                        $dao_journal = new DAOJournal($value[4]);
                        $remark = "充帐记录: 提现数据审核扣减票数:" . ($value[3] - $value[0]) . ". 扣减原因: " . $value[1];
                        $dao_journal->add($orderid, $value[4], 51, 'OUT', 1, 0, $remark, $value);
                    }
                }
            }
            $dao_withdraw->updateStatus($orderid, 4, "交易成功");

            if ($extends['type'] != 3) {
                if (is_array($extends['family'])) {
                    //家族钱
                    $account = new Account($extends['family']['family_account']['accountid']);
                    $account->increase($extends['family']['family_account']['accountid'], Account::TRADE_TYPE_WITHDWAW, $orderid, $extends['family']['family_account']['price'], Account::CURRENCY_CASH, "提现申请-家族收钱(订单:$orderid;家族id:{$extends['family']['family_account']['accountid']};金额:{$extends['family']['family_account']['price']});",  $extends['family']['family_account']);
                }

                //公司钱
                $account = new Account($extends['company']['accountid']);
                $account->increase($extends['company']['accountid'], Account::TRADE_TYPE_WITHDWAW, $orderid, $extends['company']['price'], Account::CURRENCY_CASH, "提现申请-公司现金(订单号:$orderid;帐号:{$extends['company']['accountid']};金额:{$extends['company']['price']})",  $extends['company']);

                if(!isset($extends['damage']['accountid'])) {
                    $extends['damage']['accountid'] = 1001;
                }

                if($extends['damage']['price']) {
                    //折损
                    $account = new Account($extends['damage']['accountid']);
                    $account->increase($extends['damage']['accountid'], Account::TRADE_TYPE_WITHDWAW, $orderid, $extends['damage']['price'], Account::CURRENCY_CASH, "提现申请-公司折损(订单:$orderid;折损帐户:{$extends['damage']['accountid']};金额:{$extends['damage']['price']})",  $extends['damage']);
                }

            }

            $dao_withdraw->commit();

            $withdraw_info = $this->getWithdrawInfo($orderid);
            //Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $withdraw_info['uid'], "您的提现【订单号{$orderid}】已通过", "您的提现【订单号{$orderid}】，已通过审核并打款，1-3日内注意查收", 0, $extends);
        } catch (MySQLException $e) {
            $dao_withdraw->rollback();

            throw $e;
        }

        return true;
    }

    //是否是性感主播
    public static function isSex($uid)
    {
        $redis_cache = Cache::getInstance('REDIS_CONF_CACHE');
        
        try {
            $key_active = "big_liver_keys_set";
            $exist = $redis_cache->zScore($key_active, $uid);
            if (empty($exist)) {
                return false;
            } else {
                return true;
            }
        } catch (Exception $e) {
            
        }
        
        return false;
    }



}
