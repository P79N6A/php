<?php
class Refund {
	/**
	 * 退款实例
	 * @param string $source
	 * @return object
	 */
	public static function getInstance($source)
	{
		Interceptor::ensureNotEmpty(in_array($source, array(Pay::PAY_WECHAT,Pay::PAY_WECHAT_H5,Pay::PAY_WECHAT_XIAOCHENGXU)), ERROR_BIZ_PAYMENT_DEPOSIT_SOURCE_ERROR, $source);
		static $instance = array();
		if (! isset($instance[$source])) {
			$driver = ucfirst($source) . 'Pay';
			$instance[$source] = new $driver();
		}
		return $instance[$source];
	}

	public static function call($orderid, $source, $amount)
	{
		return self::getInstance($source)->refund($orderid, $amount);
	}

	public static function complete($orderid)
    {
    	$dao_pay = new DAOPay();
    	$payinfo = $dao_pay->getPayInfo($orderid);

    	try {
    		$dao_pay->startTrans();
    		if (!empty($payinfo['coupon'])) {
    			//退代金券
    			Account::refundCash($payinfo['uid'], $payinfo['coupon'], $payinfo['orderid'], "订单取消退代金券");
    		}
    		Interceptor::ensureNotFalse($dao_pay->setPayStatus($orderid, 'R'), ERROR_BIZ_PAYMENT_PAYCOMP_FALSE, $orderid);

    		$dao_pay->commit();
    	} catch (MySQLException $e) {
    		$dao_pay->rollback();
    		Logger::log("pay_refund_log", "modifyerror", array("code" => $e->getCode(),"msg" => $e->getMessage()));
    		throw new BizException(ERROR_SYS_DB_SQL);
    	}

    	return true;
    }

}