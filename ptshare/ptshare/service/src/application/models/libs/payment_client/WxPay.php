<?php
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "wechat" . DIRECTORY_SEPARATOR . "WxPay.Api.php";

class WxPay
{
    const TRADE_TYPE_XIAOCHENGXU  = 'xiaochengxu';
    const TRADE_TYPE_GONGZHONGHAO = 'gongzhonghao';
    const TRADE_TYPE_APP          = 'APP';
    const TRADE_TYPE_H5           = 'H5';

    /**
     * 充值prepare
     * @param int $uid
     * @param string $source
     * @param string $currency
     * @param int $amount
     * @param string $openid
     * @param string $type
     * @return array|mixed
     */
    public static function prepare($uid, $source, $currency, $amount, $openid, $type, $orderid, $coupon, $hasPayinfo = false, $pay_type = 'ORDER', $extends = [])
    {
        switch ($type) {
            case self::TRADE_TYPE_XIAOCHENGXU:
            	return self::xiaochengxuPrepare($uid, $source, $currency, $amount, $openid, $orderid, $coupon, $hasPayinfo, $pay_type, $extends);
                break;
            case self::TRADE_TYPE_GONGZHONGHAO:
            	return self::gongzhonghaoPrepare($uid, $source, $currency, $amount, $openid, $orderid, $coupon, $hasPayinfo);
                break;
            default:
        }
    }

    /**
     * 支付notify
     * @return boolean
     */
    public static function notify()
    {
        $xml = file_get_contents("php://input");

        if ($xml) {
            $WxPayResults = new WxPayResults();
            $wxNotify = $WxPayResults->Init($xml);
            //print_r($wxNotify);exit;
            $orderid = $wxNotify['out_trade_no'];
        }
        if (! $orderid) {
            $orderid = $_REQUEST['orderid'];
            $status = $_REQUEST['status'];
        }

        if (strpos($orderid, '_') !== false) {
        	$environment = Context::getConfig("ENVIRONMENT");
	        $ord = explode('_', $orderid);
	        $orderid = $ord[1];
	        if ($ord[0] == 'test' && $environment == 'release') {

	        		$ch = curl_init();
	        		curl_setopt($ch, CURLOPT_URL, "http://test.putaofenxiang.com/pay/notify_wx");
	        		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	        		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	        				'X-AjaxPro-Method:ShowList',
	        				'Content-Type: application/json; charset=utf-8',
	        				'Content-Length: ' . strlen($xml))
	        				);
	        		curl_setopt($ch, CURLOPT_POST, 1);
	        		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
	        		$data = curl_exec($ch);
	        		curl_close($ch);
                    echo 'SUCCESS';
	        		exit;
	        }
        }

        $DAOPay = new DAOPay();
        $payInfo = $DAOPay->getPayInfo($orderid);
        Logger::log("pay_notify_log", "notify", array("data" => json_encode($xml)));
        if ($payInfo['status'] == 'Y') { // 成功不做任何处理
            $flags = true;
        } else {
            if ($wxNotify['result_code'] == 'SUCCESS') {
                $input = new WxPayOrderQuery();
                $input->SetTransaction_id($wxNotify['transaction_id']);

                $wxPayApi = new WxPayApi();
                $order = $wxPayApi->orderQuery($input);

                $total_fee = $order['total_fee'] / 100;
                $fee_type = $order['fee_type'];
                if ($order['result_code'] == 'SUCCESS' && $order['trade_state'] == 'SUCCESS' && $fee_type == $payInfo['currency'] && $total_fee == doubleval($payInfo['amount'])) {
                    // 执行逻辑
                	Pay::complete($orderid, $wxNotify['transaction_id'], Pay::PAY_WECHAT_XIAOCHENGXU);
                    $flags = true;
                } else {
                	$flags = false;
                }

            } else {
                if ($status == 'cancel') {
                    $DAOPay->setPayStatus($orderid, 'C');
                    Pay::rollback($orderid);
                    $flags = false;
                } else {
                    $DAOPay->setPayStatus($orderid, 'N');
                    $flags = false;
                }
            }
        }
        if ($flags) { // 微信报警功能给出的回复
            echo 'SUCCESS';
        } else {
            echo 'FAIL';
        }
        exit();
    }

    /**
     * 支付verify
     * @param string $orderid
     * @return boolean
     */
    public function verify($orderid)
    {
        $DAOPay = new DAOPay();
        $payInfo = $DAOPay->getPayInfo($orderid);
        if (in_array($payInfo['status'], array('P', 'N', 'C'))) { //查询没有充成功
            $wx = new WxPayApi();
            $input = new WxPayOrderQuery();
            $environment = Context::getConfig("ENVIRONMENT");

            $input->SetOut_trade_no($environment . '_'. $orderid);
            $order = WxPayApi::orderQuery($input);

            $total_fee = $order['total_fee']/100;
            $fee_type  = $order['fee_type'];
            Logger::log("pay_notify_log", "verify", array("data" => json_encode($order)));
            if ($order['result_code'] == 'SUCCESS' && $order['trade_state'] == 'SUCCESS' && $payInfo['currency'] == $fee_type && $total_fee == doubleval($payInfo['amount'])) {
            	Pay::complete($orderid, $order['transaction_id'], Pay::PAY_WECHAT_XIAOCHENGXU);
                $return = true;
            } else {
                $DAOPay->setPayStatus($orderid, 'N');
                $return = false;
            }
        } else {
            $return = true;
        }
        return $return;
    }

    public function refund($orderid, $amount)
    {
    	$DAOPay = new DAOPay();
    	$payInfo = $DAOPay->getPayInfo($orderid);
    	Logger::log("pay_refund_log", "refund", array("data" => $orderid));
    	$flat = false;
    	if (in_array($payInfo['status'], array('Y'))) {
    		$amount = empty($amount) ? $payInfo['amount'] : $amount;
    		if ($amount <= $payInfo['amount']) {
    			try{
    				Logger::log("pay_refund_log", "refund", array("transaction_id" => $payInfo['transaction_id'], 'total_fee' => $payInfo['amount'] * 100, 'amount' => $amount * 100));
    				$notifyWx = self::getRefoundSign($payInfo['transaction_id'], $payInfo['amount'] * 100, $amount * 100);

    			} catch (Exception $e) {
    				Logger::log("pay_refund_log", "refunderror", ['code' => $e->getCode(), 'msg' => $e->getMessage()]);
    				throw ($e);
    			}

    			if (strpos($notifyWx['out_trade_no'], '_') !== false) {
	    			$environment = Context::getConfig("ENVIRONMENT");
	    			$ord = explode('_', $notifyWx['out_trade_no']);
	    			$out_trade_no = $ord[1];
    			} else {
    				$out_trade_no = $notifyWx['out_trade_no'];
    			}
    			if ($notifyWx['result_code'] == 'SUCCESS' && $notifyWx['return_code'] == 'SUCCESS' && $orderid == $out_trade_no) {
    				Refund::complete($orderid);
    			} else {
    				Logger::log("pay_refund_log", "refunderror", ['code' => json_encode($notifyWx)]);
    			}

    			return $notifyWx;
    		}
    	} else if (in_array($payInfo['status'], array('R'))) {
    		return ['msg' => '已退款成功', '退款时间' => $payInfo['refund_time']];
    	} else {
    		Logger::log("pay_refund_log", "refunderror", ['error' => $orderid]);
    		return ['msg' => '不合法的操作'];
    	}
    }
    /**
     * 小程序prepare
     * @param int $uid
     * @param string $source
     * @param string $currency
     * @param int $amount
     * @param string $openid
     * @return array|mixed
     */
    public static function xiaochengxuPrepare($uid, $source, $currency, $amount, $openid, $orderid, $coupon, $hasPayinfo, $pay_type, $extends = [])
    {
    	try {
	        $order = self::xiaochengxuUnifiedOrder($uid, $source, $currency, $amount, $openid, $orderid);
	        Logger::log("pay_prepare", "getorder :", array("order" => json_encode($order)));
	        Interceptor::ensureNotFalse($order['return_code'] != 'FAIL', ERROR_CUSTOM, $order['return_msg']);
	        if (empty($order['prepay_id'])) {
	        	return $order;
	        }
	        //Interceptor::ensureNotNull($order['prepay_id'], ERROR_CUSTOM, $order['err_code_des']);
    	} catch (Exception $e) {
    		throw new BizException($e->getMessage());
    	}

    	if ($order['err_code'] == 'ORDERPAID' && $order['return_msg'] == 'OK' && $order['return_code'] == 'SUCCESS' && $order['result_code'] == 'FAIL') {
    		return $order;
    	}

		try {
	        $DAOPay = new DAOPay();
	        if ($hasPayinfo) {
	        	$result = $DAOPay->modify($uid, $orderid, $source, $currency, $amount, $order['prepay_id'], $coupon);
	        } else {
	        	$result = $DAOPay->prepare($uid, $orderid, $source, $currency, $amount, $order['prepay_id'], $coupon, $extends, $pay_type);
	        }
	        if ($pay_type == Pay::PAY_TYPE_ORDER) {
	        	Order::defray($orderid, $order['prepay_id'], $amount, $coupon);
	        }
		} catch(Exception $e) {
		    Logger::log("pay_prepare", "pay :", array("order" => json_encode($order),"code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
			throw new BizException($e->getMessage());
		}
		$return_order = self::getXiaochengxuSign($order);
		$return_order['orderid'] = $orderid;
		return $return_order;
    }

    /**
     * 小程序prepare
     * @param int $uid
     * @param string $source
     * @param string $currency
     * @param int $amount
     * @param string $openid
     * @return array|mixed
     */
    public static function gongzhonghaoPrepare($uid, $source, $currency, $amount, $openid, $orderid, $coupon, $hasPayinfo)
    {
    	try {
    		$order = self::xiaochengxuUnifiedOrder($uid, $source, $currency, $amount, $openid, $orderid);
    		Logger::log("account", "getorder :", array("order" => json_encode($order)));
    		Interceptor::ensureNotFalse($order['return_code'] != 'FAIL', ERROR_CUSTOM, $order['return_msg']);
    	} catch (Exception $e) {
    		throw ($e);
    	}
    	$DAOPay = new DAOPay();
    	if ($hasPayinfo) {
    		$result = $DAOPay->modify($uid, $orderid, $source, $currency, $amount, $order['prepay_id'], $coupon);
    	} else {
    		$result = $DAOPay->prepare($uid, $orderid, $source, $currency, $amount, $order['prepay_id'], $coupon);
    	}

    	$return_order = self::GetJsApiParameters($order);
    	$return_order['orderid'] = $orderid;

    	return $return_order;
    }
    /**
     *
     * 获取jsapi支付的参数
     * @param array $UnifiedOrderResult 统一支付接口返回的数据
     * @throws WxPayException
     *
     * @return json数据，可直接填入js函数作为参数
     */
    static public function GetJsApiParameters($UnifiedOrderResult)
    {
    	if(!array_key_exists("appid", $UnifiedOrderResult)
    			|| !array_key_exists("prepay_id", $UnifiedOrderResult)
    			|| $UnifiedOrderResult['prepay_id'] == "")
    	{
    		throw new WxPayException("参数错误");
    	}
    	$jsapi = new WxPayJsApiPay();
    	$jsapi->SetAppid($UnifiedOrderResult["appid"]);
    	$timeStamp = time();
    	$jsapi->SetTimeStamp("$timeStamp");
    	$jsapi->SetNonceStr(WxPayApi::getNonceStr());
    	$jsapi->SetPackage("prepay_id=" . $UnifiedOrderResult['prepay_id']);
    	$jsapi->SetSignType("MD5");
    	$jsapi->SetPaySign($jsapi->MakeSign());
    	$parameters = json_encode($jsapi->GetValues());
    	return $parameters;
    }
    /**
     * 小程序unifiedOrder
     * @param int $uid
     * @param string $source
     * @param string $currency
     * @param int $amount
     * @param string $openid
     * @param string $openid
     * @return 成功时返回，其他抛异常
     */
    public static function xiaochengxuUnifiedOrder($uid, $source, $currency, $amount, $openid, $orderid)
    {
        $input = new WxPayUnifiedOrder();
        $environment = Context::getConfig("ENVIRONMENT");

        $input->SetBody("支付-小程序-" . $orderid);
        $input->SetAttach($orderid);
        $input->SetOut_trade_no($environment . '_'. $orderid);
        $input->SetTotal_fee($amount * 100);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 900));
        $input->SetOpenid($openid);
        $input->SetNotify_url("https://api.putaofenxiang.com/pay/notify_wx"); // js
        $input->SetTrade_type("JSAPI");
        Logger::log("account", "getorder :", array("body" => "支付-小程序-" . $orderid, "amount" => $amount * 100));
        return WxPayApi::unifiedOrder($input);
    }

    /**
     * 小程序再次签名
     * @param array $source
     * @return 成功时返回，其他抛异常
     */
    public static function getXiaochengxuSign($order)
    {
        $jsapi = new WxPayJsApiPay();
        $jsapi->SetAppid($order["appid"]);
        $timeStamp = time();
        $jsapi->SetTimeStamp("$timeStamp");
        $jsapi->SetNonceStr(WxPayApi::getNonceStr());
        $jsapi->SetPackage("prepay_id=" . $order['prepay_id']);
        $jsapi->SetSignType("MD5");
        $jsapi->SetPaySign($jsapi->MakeSign());
        return $jsapi->GetValues();
    }


    public static function getRefoundSign($transaction_id, $total_fee, $refund_fee)
    {
    	$input = new WxPayRefund();
    	$input->SetTransaction_id($transaction_id);
    	$input->SetTotal_fee($total_fee);
    	$input->SetRefund_fee($refund_fee);
    	$input->SetOut_refund_no(WxPayConfig::MCHID.date("YmdHis"));
    	$input->SetOp_user_id(WxPayConfig::MCHID);

    	return WxPayApi::refund($input);
    }
}