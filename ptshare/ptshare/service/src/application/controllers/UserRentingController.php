<?php
class UserRentingController extends BaseController
{
    // 租用列表
    public function getRentingListAction()
    {
        $userid = Context::get("userid");
        $offset = $this->getParam("offset") ? intval($this->getParam("offset")) : 0;
        $num    = $this->getParam("num")    ? intval($this->getParam("num"))    : 20;
        $type   = $this->getParam("type");

        Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_IS_EMPTY, "userid");
        Interceptor::ensureNotFalse(in_array($type, array(UserRenting::USER_RENTING_TYPE_RECEIVED,UserRenting::USER_RENTING_TYPE_RENTING,UserRenting::USER_RENTING_TYPE_TRANSMIT,UserRenting::USER_RENTING_TYPE_FINISH)), ERROR_PARAM_IS_EMPTY, "type");

        list($list,$total,$offset,$more) = UserRenting::getRentingList($userid, $type, $num, $offset);

        $this->render(array('list' => $list, 'total' => $total, 'offset'=>$offset, 'more'=>$more, 'type'=>$type, 'userid'=>$userid));
    }

    // 租用详情
    public function detailsAction()
    {
        $userid   = Context::get("userid");
        $relateid = $this->getParam("relateid") ? intval($this->getParam("relateid")) : 0;

        Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_IS_EMPTY, "userid");
        Interceptor::ensureNotFalse(is_numeric($relateid) && $relateid > 0, ERROR_PARAM_IS_EMPTY, "relateid");

        $userid   = Context::get("userid");
        $userInfo = array();
        if($userid){
            $userInfo = User::getUserInfo($userid);
        }
        list($userRentingInfo, $rentingInfo, $orderInfo, $packageInfo, $pay) = UserRenting::getRentingDetails($relateid);

        $this->render(array('userRentingInfo' => $userRentingInfo,'userInfo'=>(object)$userInfo, 'rentingInfo' => $rentingInfo, 'orderInfo' => $orderInfo, 'packageInfo'=>$packageInfo, 'pay'=>$pay));
    }

    // 续租
    public function orderAction()
    {
        $userid   = Context::get("userid");
        $relateid = $this->getParam("relateid") ? intval($this->getParam("relateid")) : 0;
        $month    = $this->getParam("month")    ? intval($this->getParam("month"))    : 0;

        Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_IS_EMPTY, "userid");
        Interceptor::ensureNotFalse(is_numeric($relateid) && $relateid > 0, ERROR_PARAM_IS_EMPTY, "packageid");
        Interceptor::ensureNotFalse((is_numeric($month) && 7 > $month && $month > 0), ERROR_BIZ_RANTING_MONTH_NOT_RANGE, "month");

        list($orderid, $relateid, $rentid, $ispay, $payment) = Relet::order($userid, $relateid, $month);

        $this->render(array('orderid' => (string)$orderid, 'relateid'=>$relateid, 'rentid'=>$rentid,'ispay' => $ispay, 'payment'=>$payment));
    }

    // 取消订单
    public function cancelAction()
    {
        $userid    = Context::get("userid");
        $relateid  = $this->getParam("relateid") ? intval($this->getParam("relateid")) : 0;
        $rentid    = $this->getParam("rentid")   ? intval($this->getParam("rentid")) : 0;

        Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_IS_EMPTY, "userid");
        Interceptor::ensureNotFalse(is_numeric($relateid) && $relateid > 0, ERROR_PARAM_IS_EMPTY, "relateid");
        Interceptor::ensureNotFalse(is_numeric($rentid) && $rentid > 0, ERROR_PARAM_IS_EMPTY, "rentid");

        $userRentingInfo = UserRenting::getUserRentingInfo($relateid);
        Interceptor::ensureNotEmpty($userRentingInfo, ERROR_BIZ_RANTING_DATA_NOT_EXIST);
        Interceptor::ensureNotFalse($userRentingInfo['uid'] == $userid, ERROR_BIZ_RANTING_NO_CANCEL);

        $rentingInfo = Renting::getRentingInfo($rentid);
        Interceptor::ensureNotEmpty($rentingInfo, ERROR_BIZ_RANTING_DATA_NOT_EXIST);
        Interceptor::ensureNotFalse(in_array($rentingInfo['status'], array(UserRenting::USER_RENTING_TYPE_RECEIVED_ST_PAY,UserRenting::USER_RENTING_TYPE_RECEIVED_ST_NO_SEND)), ERROR_BIZ_RANTING_NOT_ALLOW_CANCEL);
        Interceptor::ensureNotFalse($rentingInfo['relateid'] == $relateid, ERROR_BIZ_RANTING_DATA_NOT_EXIST);

        if($rentingInfo['rent_type'] == UserRenting::USER_RENTING_TYPE_RENT){
            $result = Rent::cancel($rentid, $userRentingInfo, $rentingInfo);
        }
        if($rentingInfo['rent_type'] == UserRenting::USER_RENTING_TYPE_RELET){
            $result = Relet::cancel($rentid, $userRentingInfo, $rentingInfo);
        }

        // 微信消息
        $packageInfo = Package::getPackageInfoByPackageid($rentingInfo['packageid']);
        $option = array(
            'orderid' => $rentingInfo['orderid'],
            'title'   => $packageInfo['description'],
        	'payment' => intval($rentingInfo['rent_price']) . "葡萄"
        );
        $param = array("orderid" => $rentingInfo['orderid'],"relateid" => $relateid,"rentid" => $rentid);
        $args  = array($rentingInfo['deposit_price'], ceil($rentingInfo['rent_price'] * 0.2));
        WxMessage::cancelOrderSuccess($rentingInfo['uid'], $option, $param, $args);

        $this->render();
    }

    //取消支付
    public function cancelPayAction()
    {
    	$userid    = Context::get("userid");
    	$relateid  = $this->getParam("relateid") ? intval($this->getParam("relateid")) : 0;
    	$rentid    = $this->getParam("rentid")   ? intval($this->getParam("rentid"))   : 0;
    	$orderid   = $this->getParam("orderid")  ? intval($this->getParam("orderid"))  : "";

    	Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_IS_EMPTY, "userid");
    	Interceptor::ensureNotFalse(is_numeric($relateid) && $relateid > 0, ERROR_PARAM_IS_EMPTY, "relateid");
    	Interceptor::ensureNotFalse(is_numeric($rentid) && $rentid > 0, ERROR_PARAM_IS_EMPTY, "rentid");

    	$userRentingInfo = UserRenting::getUserRentingInfo($relateid);
    	Interceptor::ensureNotEmpty($userRentingInfo, ERROR_BIZ_RANTING_DATA_NOT_EXIST);
    	Interceptor::ensureNotFalse($userRentingInfo['uid'] == $userid, ERROR_BIZ_RANTING_NO_CANCEL);

    	$rentingInfo = Renting::getRentingInfo($rentid);
    	Interceptor::ensureNotEmpty($rentingInfo, ERROR_BIZ_RANTING_DATA_NOT_EXIST);
    	Interceptor::ensureNotFalse($rentingInfo['relateid'] == $relateid, ERROR_BIZ_RANTING_DATA_NOT_EXIST);

    	if($rentingInfo['rent_type'] == UserRenting::USER_RENTING_TYPE_RENT){
    		$result = Rent::cancelPay($rentingInfo, $relateid, $rentid, $userid);
    	}
    	if($rentingInfo['rent_type'] == UserRenting::USER_RENTING_TYPE_RELET){
    		$result = Relet::cancelPay($rentingInfo, $relateid, $rentid, $userid);
    	}

        // 微信消息
        $packageInfo = Package::getPackageInfoByPackageid($rentingInfo['packageid']);
        $option = array(
            'orderid' => $rentingInfo['orderid'],
            'title'   => $packageInfo['description'],
        	'payment' => intval($rentingInfo['rent_price']) . "葡萄"
        );
        $param = array("orderid" => $rentingInfo['orderid'], "relateid" => $relateid,"rentid" => $rentid);
        $args  = array(ceil($rentingInfo['rent_price'] * 0.2));
        WxMessage::cancelPaymentSuccess($rentingInfo['uid'], $option, $param, $args);

    	$this->render();
    }

    //支付成功
    public function paySuccessAction()
    {
        $userid    = Context::get("userid");
        $orderid   = $this->getParam("orderid")  ? intval($this->getParam("orderid"))  : "";
        Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_IS_EMPTY, "userid");
        Interceptor::ensureNotEmpty($orderid, ERROR_PARAM_IS_EMPTY, 'orderid');
        $renting_model = new DAORenting();
        $rentingInfo = $renting_model->getRentingInfoByOrderid($orderid);
        Interceptor::ensureNotEmpty($rentingInfo, ERROR_BIZ_RANTING_DATA_NOT_EXIST);
        $relateid = $rentingInfo['relateid'];
        $rentid = $rentingInfo['id'];
        $userRentingInfo = UserRenting::getUserRentingInfo($relateid);
        Interceptor::ensureNotEmpty($userRentingInfo, ERROR_BIZ_RANTING_DATA_NOT_EXIST);
        Interceptor::ensureNotFalse($userRentingInfo['uid'] == $userid, ERROR_BIZ_RANTING_NO_CANCEL);

        // 发消息
        $orderInfo   = Order::getOrderInfo($orderid);
        $packageInfo = Package::getPackageInfoByPackageid($rentingInfo['packageid']);
        if(empty($packageInfo['endtime']) || $packageInfo['endtime'] == "0000-00-00 00:00:00"){
            $num  = 1;
        }else{
            $num = Util::timeTransAfter($packageInfo['endtime']);
        }
        $model_message = new Message($rentingInfo['uid']);
        $model_message->sendMessage(DAOMessage::TYPE_ORDER_PAYED, array($num, $packageInfo['location']));

        // 微信消息
        $option  = array(
            'orderid' => $orderid,
            'title'   => $packageInfo['description'],
            'payment' => intval($rentingInfo['rent_price']) . "葡萄",
            'address' => $orderInfo["contact_province"] . $orderInfo["contact_city"] . $orderInfo["contact_address"]
        );
        $param = array("orderid" => $orderid,"relateid" => $relateid,"rentid"  => $rentingInfo['rentid']);
        $args  = array($num,$packageInfo["location"]);
        WxMessage::paymentSuccess($rentingInfo['uid'], $option, $param, $args);
    }


    // 收货确认
    public function receiveAction()
    {
        $userid    = Context::get("userid");
        $relateid  = $this->getParam("relateid") ? intval($this->getParam("relateid")) : 0;
        $rentid    = $this->getParam("rentid")   ? intval($this->getParam("rentid"))   : 0;

        Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_IS_EMPTY, "userid");
        Interceptor::ensureNotFalse(is_numeric($relateid) && $relateid > 0, ERROR_PARAM_IS_EMPTY, "packageid");
        Interceptor::ensureNotFalse(is_numeric($rentid) && $rentid > 0, ERROR_PARAM_IS_EMPTY, "packageid");

        $userRentingInfo = UserRenting::getUserRentingInfo($relateid);
        Interceptor::ensureNotEmpty($userRentingInfo, ERROR_BIZ_RANTING_DATA_NOT_EXIST,$relateid);
        Interceptor::ensureNotFalse(in_array($userRentingInfo['type'], array(UserRenting::USER_RENTING_TYPE_RECEIVED)), ERROR_PARAM_FLOOD_REQUEST, "relateid");

        $result = Rent::confirmReceive($userRentingInfo['orderid']);

        $modelPackage = new Package();
        $updateResult = $modelPackage->doExpressReceiveByPackageId($userRentingInfo['packageid']);

        $this->render();
    }

    // 快递列表
    public function getExpressAction()
    {
        $userid    = Context::get("userid");
        $relateid  = $this->getParam("relateid") ? intval($this->getParam("relateid")) : 0;
        $rentid    = $this->getParam("rentid")   ? intval($this->getParam("rentid"))   : 0;
        $orderid   = $this->getParam("orderid")  ? intval($this->getParam("orderid"))  : "";

        $userRentingInfo = UserRenting::getUserRentingInfo($relateid);
        Interceptor::ensureNotEmpty($userRentingInfo, ERROR_BIZ_RANTING_DATA_NOT_EXIST);

        $packageInfo = Package::getPackageInfoByPackageid($userRentingInfo['packageid']);

        $expressList = Express::getExpressList($orderid);
        $expressList = array_values($expressList);
        $express = isset($expressList[0]) ? $expressList[0] : array();

        $this->render(array('packageInfo'=>$packageInfo,'express' => $express));
    }

    // 租用撤销
    public function adminRevokeAction()
    {
        $relateid  = $this->getParam("relateid") ? intval($this->getParam("relateid")) : 0;

        Interceptor::ensureNotFalse(is_numeric($relateid) && $relateid > 0, ERROR_PARAM_IS_EMPTY, "packageid");

        $userRentingInfo = UserRenting::getUserRentingInfo($relateid);
        Interceptor::ensureNotEmpty($userRentingInfo, ERROR_BIZ_RANTING_DATA_NOT_EXIST);

        try {
            Rent::revoker($userRentingInfo['rentid']);
        } catch (Exception $e) {
            Logger::log("user_renting", "revoker", array("code" => $e->getCode(),"msg" => $e->getMessage()));
            throw new BizException($e->getMessage());
        }
        $this->render();
    }

}
