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
        //验证提现用户是否为运营号
        //$operations     = new DAOOperations();
        //$is_check       = $operations->checkUid($uid);
        //Interceptor::ensureNotFalse(empty($is_check), ERROR_BIZ_PAYMENT_IS_OPERATION, "userid");
        $config = new Config();
        $config_info = $config->getConfig('china', "payment_withdraw", "server", '1.0.0.0');

        $dao_withdraw = new DAOWithdraw();
        $total_amount = $dao_withdraw->getWithdrawTodayTotal($uid);
        $notlimit = array();
        if($config_info) {
            $notlimit = explode("|", $config_info['value']);
        }

        if(in_array($uid, $notlimit)) {
        } else {
            Interceptor::ensureNotFalse(($total_amount['amount']+$amount)<=3000, ERROR_BIZ_PAYMENT_WITHDRAW_AMOUNT_TOTAL_OUT, "amount");
        }
        Interceptor::ensureNotFalse($amount>=10, ERROR_BIZ_PAYMENT_WITHDRAW_AMOUNT_SMALL, "amount");

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
                $author_percent = 0.64;
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
        Interceptor::ensureNotFalse((!(isset($employe_info['frozen']) && $employe_info['frozen'] == 'Y')), ERROR_BIZ_PAYMENT_WITHDRAW_FAMILY_FROZEN, "uid");

        $damage_tickets = $tickets - $amount * 10 / ($author_percent + $author_percent_medal); //折损票

        if ($dao_employe->isEmploye($uid)) {
            //公司占比
            $company_percent = 1 - $merge_percent;
            //家族占比
            $family_percent = 1 - $company_percent - $author_percent;
            $status = 1; //家族审核
        } else {
            $company_percent = 1 - $author_percent;
            $status = 2; //运营审核
        }

        //三个比例, $author_percent $company_percent, $family_percent

        $family_tickets        = ceil($tickets * $family_percent);
        $family_price          = $tickets * $family_percent / 10;
        $family_damage_tickets = $family_tickets - $tickets * $family_percent; //家族折损票

        $company_price   = $tickets * $company_percent / 10;

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
                "family_damage"  => array("accountid" => Account::DAMAGE_ACCOUNT, "tickets" => $family_damage_tickets, "direct" => "increase"),
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

        try {
            $dao_withdraw = new DAOWithdraw();

            $dao_withdraw->startTrans();

            $account = new Account($uid);
            $account->decrease($uid, Account::TRADE_TYPE_WITHDWAW, $orderid, $tickets, Account::CURRENCY_TICKET, "提现申请(用户id:$uid:提现金额:$amount:提现扣减票:$tickets;提现帐号:$paybind_str;)",  $split_info);

            $dao_withdraw->add($uid, $orderid, $source, $amount, json_encode($split_info), $status);

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
        Interceptor::ensureNotFalse($amount>=100, ERROR_BIZ_PAYMENT_WITHDRAW_CASH_AMOUNT_SMALL, "amount");
        Interceptor::ensureNotFalse(($total_amount['amount']+$amount)<=3000, ERROR_BIZ_PAYMENT_WITHDRAW_AMOUNT_TOTAL_OUT, "amount");

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

        try {
            $dao_withdraw = new DAOWithdraw();

            $dao_withdraw->startTrans();

            $split_info = array(
                "type" => 3,
                "remark" => "提现申请(用户id:$uid:提现金额:$amount:提现帐号:$paybind_str;)",
            );

            $account = new Account($uid);
            $account->decrease($uid, Account::TRADE_TYPE_WITHDWAW, $orderid, $amount, Account::CURRENCY_CASH, "提现申请(用户id:$uid:提现金额:$amount:提现帐号:$paybind_str;)",  $split_info);

            $dao_withdraw->add($uid, $orderid, $source, $amount, json_encode($split_info), 2);

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

                    //家族折损
                    //$account_family_damage = new Account($extends['family']['family_damage']['accountid']);
                    //$account_family_damage->increase($extends['family']['family_damage']['accountid'], Account::TRADE_TYPE_WITHDWAW, $orderid, $extends['family']['family_damage']['tickets'], Account::CURRENCY_TICKET, "提现申请-家族折损(订单:$orderid;折损帐户:{$extends['family']['family_damage']['accountid']};折损票:{$extends['family']['family_damage']['tickets']})",  $extends['family']['family_damage']);
                }

                //公司钱
                $account = new Account($extends['company']['accountid']);
                $account->increase($extends['company']['accountid'], Account::TRADE_TYPE_WITHDWAW, $orderid, $extends['company']['price'], Account::CURRENCY_CASH, "提现申请-公司现金(订单号:$orderid;帐号:{$extends['company']['accountid']};金额:{$extends['company']['price']})",  $extends['company']);

                //折损
                $account = new Account($extends['damage']['accountid']);
                $account->increase($extends['damage']['accountid'], Account::TRADE_TYPE_WITHDWAW, $orderid, $extends['damage']['tickets'], Account::CURRENCY_TICKET, "提现申请-公司折损(订单:$orderid;折损帐户:{$extends['damage']['accountid']};折损票:{$extends['damage']['tickets']})",  $extends['damage']);

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
                $author_percent = 0.64;
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
}
