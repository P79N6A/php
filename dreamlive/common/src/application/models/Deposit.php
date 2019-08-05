<?php
class Deposit
{
    const DEPOSIT_WECHAT = "wechat";
    const DEPOSIT_WECHAT_H5 = "wechat5";
    const DEPOSIT_ALIPAY = "alipay";
    const DEPOSIT_PAYPAL = "paypal";
    const DESOSIT_GOOGLE = "google";
    const DEPOSIT_APPLE     = "apple";//苹果支付
    const DEPOSIT_MYCARD = "mycard";

    public static function getClient($source)
    {
        Interceptor::ensureNotEmpty(in_array($source, array(self::DEPOSIT_WECHAT, self::DEPOSIT_WECHAT_H5, self::DEPOSIT_ALIPAY, self::DESOSIT_GOOGLE, self::DEPOSIT_PAYPAL, self::DEPOSIT_APPLE, self::DEPOSIT_MYCARD)), ERROR_BIZ_PAYMENT_DEPOSIT_SOURCE_ERROR, $source);
        static $clientObj = array();
        if (! isset($clientObj[$source])) {
            $driver = ucfirst($source) . 'Pay';
            $clientObj[$source] = new $driver();
        }

        return $clientObj[$source];
    }

    public static function prepare($uid, $source, $currency, $amount)
    {
        return self::getClient($source)->prepare($uid, $source, $currency, $amount);
    }

    public static function notify($source)
    {
        $content = file_get_contents("php://input") . '**' . json_encode($_REQUEST);
        Logger::log("payment", null, array("source" => "$source", "data" => $content));
        
        $depositLog = new DAODepositLog();
        $depositLog->add($source, $content);

        return self::getClient($source)->notify();
        
    }

    public static function verify($orderid, $source)
    {
        return self::getClient($source)->verify($orderid, $source);
    }

    public function getDepositList($uid, $offset, $num)
    {
        $dao_deposit = new DAODeposit();
        $total = $dao_deposit->getDepositNum($uid);
        $list = $dao_deposit->getDepositList($uid, $offset, $num);
        foreach ($list as $value) {
            $offset = $value['id'];
        }

        return array($total, $list, $offset);
    }

    public function getDepositInfo($orderid)
    {
        $dao_deposit = new DAODeposit();
        $deposit_info = $dao_deposit->getDepositInfo($orderid);

        return $deposit_info;
    }

    public static function complete($orderid, $tradeid, $source="")
    {
        $dao_deposit = new DAODeposit();
        $deposit_info = $dao_deposit->getDepositInfo($orderid);

        if($source == self::DEPOSIT_WECHAT || $source == self::DEPOSIT_ALIPAY || $source == self::DEPOSIT_APPLE) {
            if($source == self::DEPOSIT_APPLE) {
                $deposit_num = $deposit_info['amount'] * 7;
            } else {
                $deposit_num = $deposit_info['amount'] * 10;
            }
        } elseif (($source == self::DESOSIT_GOOGLE && $deposit_info['currency'] == 'USD')) {

            $product = array('0.99'=>array('6', 'product01', 'com.dreamer.tv01', "60"),
                        '4.99'=>array('30', 'product02', 'com.dreamer.tv02', "300"),
                        '14.99'=>array('98', 'product03', 'com.dreamer.tv03', "980"),
                        '49.99'=>array('298', 'product04', 'com.dreamer.tv04', "2980"),
                        '99.99'=>array('588', 'product05', 'com.dreamer.tv05', "5880"),
                        '249.99'=>array('1598', 'product06', 'com.dreamer.tv06', "15980"),
                );
            if (isset($product["{$deposit_info['amount']}"][3])) {
                $deposit_num = $product["{$deposit_info['amount']}"][3];
            } else {
                $deposit_num = $deposit_info['amount'] * 6 * 10;
            }
        } elseif(($source == self::DEPOSIT_PAYPAL && $deposit_info['currency'] == 'USD')) {
            $deposit_num = $deposit_info['amount'] * 6 * 10;
        }elseif ($source==self::DEPOSIT_MYCARD) {
            $deposit_num=$deposit_info['amount']*2;//$deposit_info['amount']*0.2*10 
        }

        $dao_depositTaskLog = new DAODepositTaskLog();

        try {
            $dao_deposit->startTrans();

            Account::increase($deposit_info['uid'], Account::TRADE_TYPE_DEPOSIT, $orderid, $deposit_num, Account::CURRENCY_DIAMOND, "充值",  $deposit_info);
            $dao_deposit->setDepositStatus($deposit_info['orderid'], 'Y', $tradeid);
            
            $dao_depositTaskLog->addDepositTaskLog($orderid, $deposit_info['uid'], $deposit_num);

            $dao_deposit->commit();
        } catch (MySQLException $e) {
            $dao_deposit->rollback();
            throw new BizException(ERROR_SYS_DB_SQL);
        }
        

        if($source == self::DEPOSIT_APPLE) {
            $deposit_info = array(
            'orderid'=> $orderid,
            'uid'    => $deposit_info['uid'],
            'amount' => $deposit_info['amount']*10,
            );
        } else {
            $deposit_info = array(
            'orderid'=> $orderid,
            'uid'    => $deposit_info['uid'],
            'amount' => $deposit_num,
            );
        }
        

        // 信息改变发送同步到任务后台
        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("task_payment_worker", $deposit_info);
        ProcessClient::getInstance("dream")->addTask("vip_consume_add_worker", $deposit_info);
        ProcessClient::getInstance("dream")->addTask("free_revival_worker", array('uid'=>$deposit_info['uid']));
        return true;
    } 

    public static function exchangeRate()
    {
        $exchange_rate = array( 'CNY' => 1, //汇率
                                'USD' => 6, //1美元对 6人民币 
                                'TWD' => 0.2 //台币 5台币 对 1人民币
                              );

        return $exchange_rate;
    } 

    public static function operaterPlus($uid, $source, $amount, $deposit_num, $tradeid, $operater)
    {

        $orderid = Account::getOrderId($uid);

        $dao_deposit = new DAODeposit();
        $deposit     = $dao_deposit->verify($tradeid);
        Interceptor::ensureNotEmpty(!$deposit['id'], ERROR_CUSTOM, "错误的第三方id, 请确认是否填写过!");

        $dao_depositTaskLog = new DAODepositTaskLog();

        try {
            $dao_deposit->startTrans();

            $dao_deposit->prepare($uid, $orderid, $source, 'CNY', $amount, $tradeid);
            $deposit_info = $dao_deposit->getDepositInfo($orderid);

            Interceptor::ensureNotFalse(Account::increase($uid, Account::TRADE_TYPE_DEPOSIT, $orderid, $deposit_num, Account::CURRENCY_DIAMOND, "后台{$operater}通过{$source}方式充值",  $deposit_info), ERROR_BIZ_PAYMENT_DIAMOND_OUT_ACCOUNTED);

            $dao_deposit->setDepositStatus($deposit_info['orderid'], 'Y');

            $dao_depositTaskLog->addDepositTaskLog($orderid, $deposit_info['uid'], $deposit_num);

            $dao_deposit->commit();
        } catch (MySQLException $e) {
            $dao_deposit->rollback();
            throw new BizException(ERROR_SYS_DB_SQL);
        }

        $deposit_info = array(
            'orderid'=> $orderid,
            'uid'    => $uid,
            'amount' => $deposit_num,
        );

        // 信息改变发送同步到任务后台
        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("task_payment_worker", $deposit_info);
        ProcessClient::getInstance("dream")->addTask("vip_consume_add_worker", $deposit_info);

        return true;
    } 

    public static function getDepositNumByDate($uid, $date)
    {

        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, 'uid');
        $userinfo = User::getUserInfo($uid);
        Interceptor::ensureFalse(!$userinfo, ERROR_USER_NOT_EXIST, "uid");

        $dao_deposit = new DAODeposit();
        $total = $dao_deposit->getDepositNumByDate($uid, $date);

        return $total;
    }
    

}
?>