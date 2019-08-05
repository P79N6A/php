<?php
class Pay
{
    const PAY_WECHAT    = "wechat";
    const PAY_WECHAT_H5 = "wechat5";
    const PAY_WECHAT_XIAOCHENGXU = "wx";

	const PAY_STATUS_PREPARE = 'P';
	const PAY_STATUS_SUCCESS = 'Y';
	const PAY_STATUS_FAIL    = 'N';
	const PAY_STATUS_CANCEL  = 'C';
	const PAY_STATUS_REFUND  = 'R';

	const PAY_TYPE_ORDER	 = 'ORDER';//购买或租赁
	const PAY_TYPE_MEMBER	 = 'MEMBER';//会员充值
    const PAY_TYPE_VIP       = 'VIP';//会员充值

    /**
     * 支付实例
     * @param string $source
     * @return object
     */
    public static function getInstance($source)
    {
    	Interceptor::ensureNotEmpty(in_array($source, array(self::PAY_WECHAT,self::PAY_WECHAT_H5,self::PAY_WECHAT_XIAOCHENGXU)), ERROR_BIZ_PAYMENT_DEPOSIT_SOURCE_ERROR, $source);
        static $instance = array();
        if (! isset($instance[$source])) {
            $driver = ucfirst($source) . 'Pay';
            $instance[$source] = new $driver();
        }
        return $instance[$source];
    }

    public static function memberPrepare($uid, $source, $currency, $amount, $type, $from)
    {
    	$orderid = Order::getOrderId();
    	$userinfo = User::getUserInfo($uid);
    	$openid = $userinfo['openid'];
    	$extend = [];

    	Logger::log("pay_prepare", "member_prepare :", array("amount" => $amount, "orderid" => $orderid, "userid" => $uid));
    	return self::getInstance($source)->prepare($uid, $source, $currency, $amount, $openid, $from, $orderid, 0, false, $type, $extend);
    }

    /**
     * 准备prepare
     * @param int $uid
     * @param string $source
     * @param string $currency
     * @param float $amount
     */
    public static function prepare($uid, $source, $currency, $amount, $orderid, $from = 'xiaochengxu')
    {
    	Logger::log("pay_prepare", "getopenid :", array("openid" => $openid));
    	$userinfo = User::getUserInfo($uid);
    	$openid = $userinfo['openid'];
        $coupon = 0;
    	$account = Account::getAccountList($uid);
    	$dao_pay = new DAOPay();

    	$orderInfo = Order::getOrderInfo($orderid);
    	$amount = $orderInfo['service_price'] + $orderInfo['express_price'];
    	$payinfo = $dao_pay->getPayInfo($orderid);
    	$hasPayinfo = empty($payinfo) ? false : true;

    	if (!empty($account['cash'])) {
			if ($account['cash'] >= $amount) {
			    $packageInfo = Package::getPackageInfoByPackageid($orderInfo['relateid']);
			    if(empty($packageInfo['endtime']) || $packageInfo['endtime'] == "0000-00-00 00:00:00"){
			        $num  = 1;
			    }else{
			        $num = Util::timeTransAfter($packageInfo['endtime']);
			    }

			    $option  = array(
			        'orderid' => $orderid,
			        'title'   => $packageInfo['description'],
			        'payment' => intval($orderInfo['grape']) . "葡萄",
			        'address' => $orderInfo["contact_province"] . $orderInfo["contact_city"] . $orderInfo["contact_address"]
			    );
			    $args  = array($num,$packageInfo["location"]);

			    // 租赁
			    if ($orderInfo['type'] == DAOOrders::ORDER_TYPE_RENT) {
			        Account::freezeCash($uid, $amount, $orderid, "租借冻结代金券");
			        Account::recycleFreezeCash($uid, $amount, $orderid, "租借扣住冻结代金券");
			        Rent::confirm($orderid, 0, $amount, '');

			        $dao_renting = new DAORenting();
			        $rentingInfo = $dao_renting->getRentingInfoByOrderid($orderid);
			        $param = array("orderid" => $orderid,"relateid" => $rentingInfo['relateid'], "rentid"  => $rentingInfo['rentid']);
			        WxMessage::paymentSuccess($orderInfo['uid'], $option, $param, $args);
			    }
			    // 购买
			    if ($orderInfo['type'] == DAOOrders::ORDER_TYPE_BUYING) {
			        // 支出代金券
			        Account::deductCash($uid, $amount, $orderid, "支出代金券");
			        Buying::confirm($orderid, 0, $amount, '');

			        $buyingInfo = Buying::getBuyingInfoByOrderid($orderid);
			        $param = array("buyid" => $buyingInfo['buyid']);
			        WxMessage::paymentSuccess($orderInfo['uid'], $option, $param, $args, true);
			    }
			    if ($orderInfo['type'] == DAOOrders::ORDER_TYPE_SEIZING) {
			        // 支出代金券
			        Account::deductCash($uid, $amount, $orderid, "支出代金券");
			        Buying::confirm($orderid, 0, $amount, '');

			        $buyingInfo = Buying::getBuyingInfoByOrderid($orderid);
			        $param = array("buyid" => $buyingInfo['buyid']);
			        WxMessage::paymentSuccess($orderInfo['uid'], $option, $param, $args, true);
			    }

			    // 站内消息
			    $model_message = new Message($orderInfo['uid']);
			    $model_message->sendMessage(DAOMessage::TYPE_ORDER_PAYED, array($num, $packageInfo['location']));

				return ['pay_type' => 'coupon'];//不需要支付
			} else {
				$coupon = $account['cash'];
				$need_pay_amount = $amount - $coupon;
				//冻结代金券
				Account::freezeCash($uid, $coupon, $orderid, "支付订单冻结代金券");
			}
    	} else {
    		$need_pay_amount = $amount;
    	}

    	Logger::log("pay_prepare", "payamount :", array("amount" => $amount, "need_pay" => $need_pay_amount));
    	return self::getInstance($source)->prepare($uid, $source, $currency, $need_pay_amount, $openid, $from, $orderid, $coupon, $hasPayinfo);
    }

    /**
     * 准备prepare
     * @param int $uid
     * @param string $source
     * @param string $currency
     * @param float $amount
     */
    public static function Gprepare($uid, $source, $currency, $amount, $orderid)
    {
    	$userinfo = User::getUserInfo($uid);
        $openid = $userinfo['openid'];
        $coupon = 0;
        $account = Account::getAccountList($uid);
        $dao_pay = new DAOPay();
        $payinfo = $dao_pay->getPayInfo($orderid);
        $hasPayinfo = empty($payinfo) ? false : true;
        if (!empty($account['cash'])) {
            if ($account['cash'] >= $amount) {
                return [];//不需要支付
            } else {
                $coupon = $account['cash'];
                $need_pay_amount = $amount - $coupon;
                //冻结代金券
                Account::freezeCash($uid, $coupon, $orderid, "支付订单冻结代金券");
            }
        } else {
            $need_pay_amount = $amount;
        }

    	return self::getInstance($source)->prepare($uid, $source, $currency, $amount, $openid, WxPay::TRADE_TYPE_GONGZHONGHAO, $orderid, $coupon, $hasPayinfo);
    }
    /**
     * 支付notify
     * @param string $source
     * @return unknown
     */
    public static function notify($source)
    {
        $content = file_get_contents("php://input") . '**' . json_encode($_REQUEST);
        Logger::log("pay_notify_log", "receiveparam", array("source" => "$source","data" => json_encode($content)));

        $DAOPayLog = new DAOPayLog();
        $DAOPayLog->add($source, $content);

        return self::getInstance($source)->notify();
    }

    /**
     * 支付verify
     * @param string $orderid
     * @param string $source
     */
    public static function verify($orderid, $source)
    {
        return self::getInstance($source)->verify($orderid);
    }


    /**
     * 支付完成
     * @param int $orderid
     * @param int $tradeid
     * @param string $source
     */
    public static function complete($orderid, $tradeid, $source = "")
    {
    	$dao_pay = new DAOPay();
    	$payinfo = $dao_pay->getPayInfo($orderid);

		$orderinfo = Order::getOrderInfo($orderid);

    	try {
    		$dao_pay->startTrans();

    		if (!empty($payinfo['coupon'])) {
	    		//扣除代金券
    		    Account::recycleFreezeCash($payinfo['uid'], $payinfo['coupon'], $orderid, "订单代金券扣除");
    		}
    		Interceptor::ensureNotFalse($dao_pay->setPayStatus($orderid, 'Y', $tradeid), ERROR_BIZ_PAYMENT_PAYCOMP_FALSE, $orderid);

    		$dao_pay->commit();
    	} catch (MySQLException $e) {
    		$dao_pay->rollback();
    		Logger::log("pay_notify_log", "modifyerror", array("code" => $e->getCode(),"msg" => $e->getMessage()));
    		throw new BizException(ERROR_SYS_DB_SQL);
    	}

    	if ($payinfo['type'] == self::PAY_TYPE_VIP) {
    		return Vip::complete($payinfo);
    	}

    	if ($orderinfo['type'] == DAOOrders::ORDER_TYPE_RENT) {
    		Rent::confirm($orderid, $payinfo['amount'], $payinfo['coupon'], $tradeid);
    	} else if ($orderinfo['type'] == DAOOrders::ORDER_TYPE_BUYING) {
    		Buying::confirm($orderid, $payinfo['amount'], $payinfo['coupon'], $tradeid);
    	} else if ($orderinfo['type'] == DAOOrders::ORDER_TYPE_SEIZING) {
    		Buying::confirm($orderid, $payinfo['amount'], $payinfo['coupon'], $tradeid);
    	} elseif ($orderinfo['type'] == DAOOrders::ORDER_TYPE_SEIZING) {

    		Relet::confirm($orderid, $payinfo['amount'], $payinfo['coupon'], $tradeid);
		}

    	return true;
    }

    public static function rollback($orderid)
    {
    	$dao_pay = new DAOPay();
    	$payinfo = $dao_pay->getPayInfo($orderid);

    	if (empty($payinfo['coupon'])) {
    		return true;
    	}

    	try {
    		$dao_pay->startTrans();

    		Account::unfreezeCash($payinfo['uid'], $payinfo['coupon'], $orderid, "待定支付失败解冻代金券");

    		$dao_pay->setPayCoupon($orderid, 0);
    		$dao_pay->commit();
    	} catch (Exception $e) {
    		$dao_pay->rollback();
    		Logger::log("pay_notify_log", "payerror", array("code" => $e->getCode(),"msg" => $e->getMessage()));
    		throw new BizException(ERROR_SYS_DB_SQL);
    	}

    	return true;
    }

    private function __clone()
    {

    }

}
?>