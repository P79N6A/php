<?php
class PayController extends BaseController
{
    public function prepareAction()
    {/*{{{预支付订单*/
        $uid 		= Context::get("userid");
        $source 	= $this->getParam('source');
        $currency 	= $this->getParam('currency') ? $this->getParam('currency') : 'CNY';
        $amount 	= $this->getParam('amount');
        $orderid 	= $this->getParam('orderid');
        $groupid 	= $this->getParam('groupid');
        $from		= $this->getParam('from') 		? $this->getParam('from') 		: 'xiaochengxu';//支付类型
        $type 		= $this->getParam('type') 		? $this->getParam('type') 		: 'ORDER';//支付类型


        Logger::log("account", "get pay param :", array("source" => $source,"uid" => $uid, "amount" => $amount, "orderid" => $orderid));
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        //Interceptor::ensureNotEmpty($amount, ERROR_PARAM_IS_EMPTY, 'amount');
        Interceptor::ensureNotEmpty($source, ERROR_PARAM_IS_EMPTY, 'source');
        if ($type == Pay::PAY_TYPE_ORDER) {
        	Interceptor::ensureNotEmpty($orderid, ERROR_PARAM_IS_EMPTY, 'orderid');
        }

        if ($type == Pay::PAY_TYPE_VIP) {
            $amount = 0.02;
        } elseif ($type == Pay::PAY_TYPE_MEMBER) {
            $amount = 299;
        }
        $user = new User();
        $userinfo = $user->getUserInfo($uid);
        Interceptor::ensureNotFalse(isset($userinfo['uid']), ERROR_USER_NOT_EXIST);
		if ($type == Pay::PAY_TYPE_ORDER) {
			$sign = Pay::prepare($uid, $source, $currency, $amount, $orderid, $from);
		} else {
            //if ($type == Pay::PAY_TYPE_VIP)
			$sign = Pay::memberPrepare($uid, $source, $currency, $amount, $type, $from);
		}

        $this->render($sign);
        //{"errno":0,"errmsg":"操作成功","consume":358,"time":1526987106,"md5":"61c165d70d90114f216b99cb946ab363","data":{"appId":"wx0998703598fcc712","nonceStr":"lz7pv3f92bdgz5hkj1mgloqmj6f5lrk9","package":"prepay_id=wx2219050633001147926355710394326936","signType":"MD5","timeStamp":"1526987106","paySign":"07CBD331B879CA6772D498757CBB287D"}}
    }/*}}}*/

    public function notifyAction()
    {/*{{{通知支付成功回调*/
        $source = $this->getParam('source');
        Interceptor::ensureNotEmpty($source, ERROR_PARAM_IS_EMPTY, 'source');

        $result = Pay::notify($source);

        $this->render(array("result" => $result));
    }/*}}}*/

    public function verifyAction()
    {/*{{{验证支付*/
        $orderid = $this->getParam('orderid');
        $uid = Context::get("userid");

        Interceptor::ensureNotEmpty($orderid, ERROR_PARAM_IS_EMPTY, 'orderid');
		$pay = new DAOPay();
		$payInfo = $pay->getPayInfo($orderid);
        $result = Pay::verify($orderid, $payInfo['source']);

        $this->render($result);
    }/*}}}*/

    public function refundAction()
    {/*{{{退款*/
    	$orderid = $this->getParam('orderid');
    	$source = $this->getParam('source');
    	$amount = $this->getParam('amount');
    	$uid = Context::get("userid");

    	Interceptor::ensureNotEmpty($orderid, ERROR_PARAM_IS_EMPTY, 'orderid');

    	$result = Refund::call($orderid, $source, $amount);

    	$this->render(array("result" => $result));
    }/*}}}*/


    // 取消支付
    public function cancelAction()
    {
        $userid  = Context::get("userid");
        $orderid = $this->getParam("orderid") ? intval($this->getParam("orderid")) : "";
        Interceptor::ensureNotFalse(! empty($orderid), ERROR_PARAM_IS_EMPTY, "orderid");

        $orderInfo = Order::getOrderInfo($orderid);
        Interceptor::ensureNotEmpty($orderInfo, ERROR_BIZ_ORDER_NOT_EXIST, $orderid);

		Pay::rollback($orderid);

        // 站内消息
        $message = new Message($userid);
        $penalty = $orderInfo['grape'] * 0.2;
        $message->sendMessage(DAOMessage::TYPE_ORDER_NO_PAY, array($penalty));

        // 微信消息
        $packageInfo = Package::getPackageInfoByPackageid($orderInfo['relateid']);
        $option = array(
            'orderid' => $orderInfo['orderid'],
            'title'   => $packageInfo['description'],
            'payment' => intval($orderInfo['grape']) . "葡萄"
        );
        $args  = array($penalty);
        // 租赁
        if ($orderInfo['type'] == DAOOrders::ORDER_TYPE_RENT) {

        }
        // 续租
        if ($orderInfo['type'] == DAOOrders::ORDER_TYPE_RELET) {

        }
        // 购买
        if ($orderInfo['type'] == DAOOrders::ORDER_TYPE_BUYING) {
            $buyingInfo  = Buying::getBuyingInfoByOrderid($orderid);
            $param       = array("buyid" => $buyingInfo['buyid']);
            WxMessage::cancelBuySuccess($userid, $option, $param, $args);
        }

        $this->render();
    }

    // 支付成功
    public function successAction()
    {
        $userid = Context::get("userid");
        $orderid = $this->getParam("orderid") ? intval($this->getParam("orderid")) : "";

        Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_IS_EMPTY, "userid");
        Interceptor::ensureNotEmpty($orderid, ERROR_PARAM_IS_EMPTY, 'orderid');

        // 站内消息
        $orderInfo   = Order::getOrderInfo($orderid);
        $packageInfo = Package::getPackageInfoByPackageid($orderInfo['relateid']);
        if (empty($packageInfo['endtime']) || $packageInfo['endtime'] == "0000-00-00 00:00:00") {
            $num = 1;
        } else {
            $num = Util::timeTransAfter($packageInfo['endtime']);
        }
        $model_message = new Message($orderInfo['uid']);
        $model_message->sendMessage(DAOMessage::TYPE_ORDER_PAYED, array($num,$packageInfo['location']));


        // 微信消息
        $option = array(
            'orderid' => $orderInfo['orderid'],
            'title'   => $packageInfo['description'],
            'payment' => $orderInfo['grape'] . "葡萄",
            'address' => $orderInfo["contact_province"] . $orderInfo["contact_city"] . $orderInfo["contact_address"]
        );
        $args  = array($num,$packageInfo['location']);
        // 租赁
        if ($orderInfo['type'] == DAOOrders::ORDER_TYPE_RENT) {

        }
        // 续租
        if ($orderInfo['type'] == DAOOrders::ORDER_TYPE_RELET) {

        }
        // 购买
        if ($orderInfo['type'] == DAOOrders::ORDER_TYPE_BUYING) {
            $buyingInfo  = Buying::getBuyingInfoByOrderid($orderid);
            $param       = array("buyid" => $buyingInfo['buyid']);
            WxMessage::paymentSuccess($userid, $option, $param, $args, true);
        }

        $this->render();
    }

   	//测试用的随时可以删除
    public function completeAction()
    {
    	$orderid 	= $this->getParam("orderid") ? intval($this->getParam("orderid")) : "";
    	$tradeid	= $this->getParam("tradeid") ? intval($this->getParam("tradeid")) : "";

    	$this->render(Pay::complete($orderid, $tradeid, 'wx'));
    }

    public function getJsApiTicketAction()
    {
    	$wx = new WxProgram();
        $ticket = $wx->getJsApiTicket();

    	$this->render(
            array("ticket" => $ticket)
        );
    }
}
?>
