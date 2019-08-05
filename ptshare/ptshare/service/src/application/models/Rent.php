<?php
class Rent
{
    public static function order($uid, $id, $month, $contact)
    {
        // package验证
        $packageInfo = Package::getPackageInfo($id);
        Interceptor::ensureNotEmpty($packageInfo, ERROR_BIZ_PACKET_NOT_EXIST);
        if (in_array($packageInfo['sell_user_id'], Package::$user_sellout)) {
            $DAOPackage = new DAOPackage();
            $DAOPackage->updatePackageOnline($packageInfo['packageid'], DAOPackage::STATUS_SELLOUT);
        }
        $packageInfo = Package::getPackageInfo($id);
        Interceptor::ensureNotFalse(DAOPackage::STATUS_ONLINE == $packageInfo['status'], ERROR_BIZ_PACKET_NOT_ONLINE);
        //Interceptor::ensureNotFalse($packageInfo['endtime'] > date('Y-m-d H:i:s'), ERROR_BIZ_PACKET_NOT_EXPIRE);
        $packageid = $packageInfo['packageid'];

        // 用户信息
        $userInfo = User::getUserInfo($uid);
        if (1 == $packageInfo['vip']) {
            Interceptor::ensureNotFalse($userInfo['vip'], ERROR_BIZ_PACKET_VIP_NOT_BUYR_OR_ENT);
        }

        // 葡萄币是否够支付
        $account = Account::getAccountList($uid);
        $rent_price = $packageInfo['rent_price'] * $month;
        if ($userInfo['vip']) {
            $deposit_price = 0;
            $grape = $deposit_price + $rent_price;
        } else {
            $deposit_price = $packageInfo['deposit_price'];
            $grape = $deposit_price + $rent_price;
        }
        Interceptor::ensureNotFalse($account['grape'] >= $grape, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

        // 是否重复租用
        $DAORenting = new DAORenting();
        Interceptor::ensureFalse($DAORenting->isExistRenting($uid, $packageid, $packageInfo['sn'], $month), ERROR_BIZ_RANTING_REPEAT_ORDER);

        // package下架
        $orderid = Order::getOrderId();
        Interceptor::ensureNotFalse(Package::updatePackageOnline($packageid, DAOPackage::STATUS_SELLOUT), ERROR_SYS_UNKNOWN);

        // 快递类型
        if($packageInfo['source'] == DAOPackage::SOURCE_SELL){
            $express_type  = UserRenting::USER_RENTING_EXPRESS_TYPE_FIRST;
        }
        if($packageInfo['source'] == DAOPackage::SOURCE_PACKAGE){
            $express_type  = UserRenting::USER_RENTING_EXPRESS_TYPE_TRANSMIT;
        }

        $orderid = Order::getOrderId();
        $express_price = Package::PACKET_EXPRESS_FEE;
        $service_price = Package::PACKET_SERVICES_FEE;
        $price         = $express_price + $service_price;

        // 葡萄+代金券支付
        if ($account['cash'] >= $price) {
            $pay_price  = 0;
            $pay_coupon = $price;

            // 租赁处理
            list ($relateid, $rentid, $rentlogid) = UserRenting::addUserRentings($uid, $orderid, $packageid, $packageInfo['sn'], $packageInfo['num'], $month, UserRenting::USER_RENTING_TYPE_RENT, $express_type, $rent_price, $deposit_price, $pay_price,$pay_coupon, $service_price, $express_price);

            // 订单、租金、押金处理
            $DAOOrders = new DAOOrders();
            try {
                $DAOOrders->startTrans();

                // 创建订单
                Order::addRentOrder($orderid, $packageid, $rent_price, $deposit_price, $service_price, $express_price, DAOOrders::ORDER_STATUS_CONFIRM, DAOOrders::ORDER_PAY_STATUS_PAYOFF, DAOOrders::ORDER_PAY_TYPE_COUPON, $pay_price,$pay_coupon, $contact, (int)$userInfo['vip']);

                // 冻结押金；支出租金；
                Account::freeze($uid, $deposit_price, $orderid, "冻结押金");
                Account::deductGrape($uid, $rent_price, $orderid, "支出租金");

                // 支出冻结代金券
                Account::deductCash($uid, $pay_coupon, $orderid, "支出代金券");

                $DAOOrders->commit();
            } catch (Exception $e) {
                $DAOOrders->rollback();
                Package::updatePackageOnline($packageid, DAOPackage::STATUS_ONLINE);
                Logger::log("user_renting", "rent_order_coupon", array("uid"=>$uid,"packageid"=>$packageid,"code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
                throw new BizException($e->getMessage());
            }

            // 租赁状态
            list ($relateid, $rentid, $rentlogid) = UserRenting::updateUserRentingsRentFinish($relateid, $rentid, $orderid, UserRenting::USER_RENTING_TYPE_RECEIVED, UserRenting::USER_RENTING_TYPE_RECEIVED_ST_NO_SEND);

            // 添加package的orderid
            Package::updatePackageOrderid($packageid, $orderid);

            // 快递处理
            $result = self::express($uid, $orderid, $packageInfo['num'], $contact, $packageInfo['contact'], $packageInfo['description']);

            // 站内消息
            if(empty($packageInfo['endtime']) || $packageInfo['endtime'] == "0000-00-00 00:00:00"){
                $num  = 1;
            }else{
                $num = Util::timeTransAfter($packageInfo['endtime']);
            }
            $model_message = new Message($uid);
            $model_message->sendMessage(DAOMessage::TYPE_ORDER_PAYED, array($num, $packageInfo['location']));

            // 微信消息
            $option = array(
                'orderid' => $orderid,
                'title'   => $packageInfo['description'],
                'payment' => intval($rent_price) . "葡萄",
                'address' => $contact["contact_province"] . $contact["contact_city"] . $contact["contact_address"]
            );
            $param = array("orderid"  => $orderid,"relateid" => $relateid,"rentid"  => $rentid);
            $args  = array($num,$packageInfo["location"]);
            WxMessage::paymentSuccess($uid, $option, $param, $args);

            // 执行任务
            try {
                Task::execute($uid, Task::TASK_ID_NINE, 1, json_encode(array('grape'=>$grape)));
            } catch (Exception $e) {}

            return array($orderid, $relateid, $rentid, false, array());
        }else{

            // user_renting处理
            $pay_price  = $price-$account['cash'];
            $pay_coupon = $account['cash'];
            list($relateid, $rentid) = UserRenting::addUserRentings($uid, $orderid, $packageid, $packageInfo['sn'], $packageInfo['num'], $month, UserRenting::USER_RENTING_TYPE_RENT, $express_type, $rent_price, $deposit_price, $pay_price,$pay_coupon, $service_price, $express_price);

            // 订单、租金、押金处理
            $DAOOrders = new DAOOrders();
            try {
                $DAOOrders->startTrans();

                // 创建订单
                Order::addRentOrder($orderid, $packageid, $rent_price, $deposit_price, $service_price, $express_price, DAOOrders::ORDER_PAY_STATUS_INITIAL, DAOOrders::ORDER_PAY_STATUS_INITIAL, DAOOrders::ORDER_PAY_TYPE_MIXED, $pay_price, $pay_coupon, $contact);

                // 冻结押金；支出租金;
                Account::freeze($uid, $deposit_price, $orderid, "冻结押金");
                Account::deductGrape($uid, $rent_price, $orderid, "支出租金");

                $DAOOrders->commit();
            } catch (Exception $e) {
                $DAOOrders->rollback();
                Package::updatePackageOnline($packageid, DAOPackage::STATUS_ONLINE);
                Logger::log("user_renting", "rent_order_payment", array("uid"=>$uid,"packageid"=>$packageid, "code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
                throw new BizException($e->getMessage());
            }

            // 租赁状态
            list ($relateid, $rentid, $rentlogid) = UserRenting::updateUserRentingsRentFinish($relateid, $rentid, $orderid, UserRenting::USER_RENTING_TYPE_RECEIVED, UserRenting::USER_RENTING_TYPE_RECEIVED_ST_PAY);

            // 添加package的orderid
            Package::updatePackageOrderid($packageid, $orderid);

            // 支付prepare
            $result = Pay::prepare($uid, Pay::PAY_WECHAT_XIAOCHENGXU, 'CNY', $pay_price, $orderid);

            return array($orderid, $relateid, $rentid, true, $result);
        }
    }

    // 取消订单
    public static function cancel($rentid, $userRentingInfo, $rentingInfo)
    {
        Interceptor::ensureNotFalse(!empty($rentingInfo), ERROR_BIZ_RANTING_DATA_NOT_EXIST);
    	Interceptor::ensureNotFalse(in_array($rentingInfo['status'], array(UserRenting::USER_RENTING_TYPE_RECEIVED_ST_PAY,UserRenting::USER_RENTING_TYPE_RECEIVED_ST_NO_SEND)), ERROR_BIZ_RANTING_NOT_ALLOW_CANCEL);

    	$orderInfo = Order::getOrderInfo($rentingInfo['orderid']);
    	Interceptor::ensureNotFalse(in_array($orderInfo["status"],[DAOOrders::ORDER_STATUS_INITIAL,DAOOrders::ORDER_STATUS_CONFIRM]), ERROR_BIZ_ORDER_STATUS_UNVALID, $rentingInfo['orderid']);

    	// 状态处理
    	list ($relateid, $rentid, $rentlogid) = UserRenting::updateUserRentingsStatus($rentingInfo['relateid'], $rentid, $rentingInfo['orderid'], UserRenting::USER_RENTING_TYPE_FINISH, UserRenting::USER_RENTING_TYPE_FINISH_ST_CANCEL, "取消订单");

    	// 订单、资金处理
    	$DAOOrders = new DAOOrders();
    	try {
    	    // 快递未发货且支付有支付现金，退支付金额
    	    if ($orderInfo['express_status'] == DAOOrders::ORDER_EXPRESS_STATUS_INITIAL && $orderInfo['pay_price'] > 0) {
    	        $DAOPay  = new DAOPay();
    	        $payInfo = $DAOPay->getPayInfo($rentingInfo['orderid']);
    	        if ($payInfo['status'] == Pay::PAY_STATUS_SUCCESS) {
    	            Refund::call($rentingInfo['orderid'], Pay::PAY_WECHAT_XIAOCHENGXU, $payInfo['amount']);
                }
            }

            $DAOOrders->startTrans();

            // 交罚金
            $percent = Penalty::PENALTY_PERCENT; // 罚金比例
            $penalty_amount = ceil(($rentingInfo['rent_price'] + $rentingInfo['deposit_price']) * $percent);

            // 退(租金-罚金);解冻押金;扣除罚金
            Account::unfreeze($rentingInfo['uid'], $rentingInfo['deposit_price'], $rentingInfo['orderid'], "押金解冻");
            Account::refundGrape($rentingInfo['uid'], $rentingInfo['rent_price'], $rentingInfo['orderid'], "取消订单退回");
            Account::deductGrape($rentingInfo['uid'], $penalty_amount, $rentingInfo['orderid'], "取消订单扣除");

            // 订单取消退代金券
            if ($orderInfo['pay_status'] == DAOOrders::ORDER_PAY_STATUS_PAYOFF && $orderInfo['pay_status'] == DAOOrders::ORDER_PAY_TYPE_COUPON) {
                Account::refundCash($rentingInfo['uid'], $rentingInfo['pay_coupon'], $rentingInfo['orderid'], "订单取消退代金券");
            }

	    	//设置订单状态
	    	Order::cancel($rentingInfo['orderid']);

	    	$DAOOrders->commit();
    	} catch (Exception $e) {
    	    $DAOOrders->rollback();
    		Logger::log("user_renting", "rent_cancel", array("uid"=>$rentingInfo['uid'], "rentid"=>$rentid, "code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
    		throw new BizException($e->getMessage());
    	}

    	$DAOPenalty = new DAOPenalty();
	    try {
	        $DAOPenalty->startTrans();

	    	// 宝贝上架
	    	Interceptor::ensureNotFalse(Package::updatePackageOnline($rentingInfo['packageid'], 'ONLINE'), ERROR_SYS_UNKNOWN);

	    	// 处罚记录
	    	$DAOPenalty->add($rentingInfo['uid'], Penalty::PENALTY_TYPE_CANCEL, $rentid, "订单被取消扣罚金", $penalty_amount);

	    	// 状态确认
	    	$DAORentingLog = new DAORentingLog();
	    	$DAORentingLog->updateRentingLogResult($rentlogid, Renting::STATUS_FINISH);

	    	$DAOPenalty->commit();
	    } catch (Exception $e) {
	        $DAOPenalty->rollback();
	        Logger::log("user_renting", "rent_cancel_status", array("uid"=>$rentingInfo['uid'], "rentid"=>$rentid, "code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
	    	throw new BizException($e->getMessage());
    	}

    	return true;
    }

    //支付确认
    public static function confirm($orderid, $pay_price, $pay_coupon, $pay_no)
    {
        $DAORenting  = new DAORenting();
        $rentingInfo = $DAORenting->getRentingInfoByOrderid($orderid);

        // 状态处理
        list ($relateid, $rentid, $rentlogid) = UserRenting::updateUserRentingsStatus($rentingInfo['relateid'], $rentingInfo['rentid'], $orderid, UserRenting::USER_RENTING_TYPE_RECEIVED, UserRenting::USER_RENTING_TYPE_RECEIVED_ST_NO_SEND, "支付确认");

        // 订单处理
        $DAOOrders = new DAOOrders();
    	try{
    	    $DAOOrders->startTrans();

	    	if (!empty($pay_coupon)) {
	    		$pay_type = 3;
	    	} else {
	    		$pay_type = 2;
	    	}

	    	//订单确认
	    	Order::confirm($orderid);

	        //订单改状态
	    	Order::payoff($orderid, $pay_no, $pay_type, $pay_coupon, $pay_price);

	    	$DAOOrders->commit();
    	} catch (Exception $e) {
    	    $DAOOrders->rollback();
    		Logger::log("user_renting", "rent_confirm", array("uid"=>$rentingInfo['uid'], "orderid"=>$orderid, "code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
    		throw new BizException($e->getMessage());
    	}

    	// 状态确认
    	$DAORentingLog = new DAORentingLog();
    	$DAORentingLog->updateRentingLogResult($rentlogid, Renting::STATUS_FINISH);

    	// 快递处理
    	$packageInfo = Package::getPackageInfoByPackageid($rentingInfo['packageid']);
    	$orderInfo   = Order::getOrderInfo($orderid);
    	$result      = self::express($rentingInfo['uid'], $orderid, $rentingInfo['num'], $orderInfo, $packageInfo['contact'], $packageInfo['description']);

    	// 执行任务
    	try {
    	    Task::execute($orderInfo['uid'], Task::TASK_ID_NINE, 1, json_encode(array('grape'=>intval($orderInfo['grape'] + $orderInfo['deposit_price']))));
    	} catch (Exception $e) {}

    	return true;
    }

    // 已发货(快递回调)
    public static function expressDeliver($orderid){
        $DAORenting  = new DAORenting();
        $rentingInfo = $DAORenting->getRentingInfoByOrderid($orderid);
        Interceptor::ensureNotEmpty($rentingInfo, ERROR_BIZ_RANTING_DATA_NOT_EXIST);

        // 状态处理
        list ($relateid, $rentid, $rentlogid) = UserRenting::updateUserRentingsStatus($rentingInfo['relateid'], $rentingInfo['rentid'], $orderid, UserRenting::USER_RENTING_TYPE_RECEIVED, UserRenting::USER_RENTING_TYPE_RECEIVED_ST_SEND, "已发货");

        // 已发货
        try {
            Order::deliver($orderid);
        } catch (Exception $e) {
            Logger::log("user_renting", "rent_expressDeliver_order", array("orderid"=>$orderid,"code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
            throw new BizException($e->getMessage());
        }

        // 发消息
        try {
            if ($rentingInfo['express_type'] == UserRenting::USER_RENTING_EXPRESS_TYPE_FIRST) {
                $Message = new Message($rentingInfo['uid']);
                $Message->sendMessage(DAOMessage::TYPE_ORDER_SEND);
            }
            if ($rentingInfo['express_type'] == UserRenting::USER_RENTING_EXPRESS_TYPE_TRANSMIT) {
                $DAOUserRenting = new DAOUserRenting();
                $prevUserRentingInfo = $DAOUserRenting->getPrevUserRentingInfoBySn($rentingInfo['uid'], $rentingInfo['sn']);
                if (! empty($prevUserRentingInfo)) {
                    $prevRentingInfo = $DAORenting->getRentingInfo($prevUserRentingInfo['rentid']);
                    $Message = new Message($prevRentingInfo['uid']);
                    $Message->sendMessage(DAOMessage::TYPE_ORDER_RENT_FINISH, array($prevRentingInfo['deposit_price']));
                }
            }
        } catch (Exception $e) {
            Logger::log("user_renting", "rent_expressDeliver_message", array("orderid"=>$orderid,"code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
            throw new BizException($e->getMessage());
        }

        // 状态确认
        $DAORentingLog = new DAORentingLog();
        $DAORentingLog->updateRentingLogResult($rentlogid, Renting::STATUS_FINISH);

        return true;
    }


    // 已收货(快递回调)
    public static function expressReceive($orderid){
        return true;
        // 以用户确认收货为准


        // renting验证
        $DAORenting  = new DAORenting();
        $rentingInfo = $DAORenting->getRentingInfoByOrderid($orderid);
        Interceptor::ensureNotEmpty($rentingInfo, ERROR_BIZ_RANTING_DATA_NOT_EXIST);

        // user_renting验证
        $userRentingInfo = UserRenting::getUserRentingInfo($rentingInfo['relateid']);
        Interceptor::ensureNotEmpty($userRentingInfo, ERROR_BIZ_RANTING_DATA_NOT_EXIST);

        // order验证
        $orderInfo = Order::getOrderInfo($orderid);
        Interceptor::ensureNotEmpty($orderInfo, ERROR_BIZ_ORDER_STATUS_UNVALID, $orderid);

        // 租赁处理
        list($relateid, $rentid, $rentlogid) = UserRenting::receiveUserRentings($rentingInfo['relateid'], $rentingInfo['rentid'], $rentingInfo['orderid'], $rentingInfo['month'], $userRentingInfo['startime']);

        // 快递签收
        try {
            Order::receive($orderid);
        } catch (Exception $e) {
            Logger::log("user_renting", "rent_expressReceive_order", array("orderid"=>$orderid,"code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
            throw new BizException($e->getMessage());
        }

        // 发消息
        try {
            if ($rentingInfo['express_type'] == UserRenting::USER_RENTING_EXPRESS_TYPE_FIRST) {
                $startime = date('Y-m-d H:i:s');
                $endtime = date('Y-m-d H:i:s', strtotime($startime . " +" . $rentingInfo['month'] * 30 . " day"));
                $Message = new Message($rentingInfo['uid']);
                $Message->sendMessage(DAOMessage::TYPE_ORDER_RECEIVE, array($rentingInfo['month'], $endtime));
            }
            if ($rentingInfo['express_type'] == UserRenting::USER_RENTING_EXPRESS_TYPE_TRANSMIT) {
                $DAOUserRenting = new DAOUserRenting();
                $prevUserRentingInfo = $DAOUserRenting->getPrevUserRentingInfoBySn($rentingInfo['uid'], $rentingInfo['sn']);
                if (! empty($prevUserRentingInfo)) {
                    $prevRentingInfo = $DAORenting->getRentingInfo($prevUserRentingInfo['rentid']);

                    // 发消息
                    $Message = new Message($prevRentingInfo['uid']);
                    $Message->sendMessage(DAOMessage::TYPE_ORDER_RENT_FINISH_RECEIVE, array($prevRentingInfo['deposit_price']));
                }
            }
        } catch (Exception $e) {
            Logger::log("user_renting", "rent_expressReceive_message", array("orderid"=>$orderid,"code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
            throw new BizException($e->getMessage());
        }

        // 状态确认
        $DAORentingLog = new DAORentingLog();
        $DAORentingLog->updateRentingLogResult($rentlogid, Renting::STATUS_FINISH);

        return true;
    }

    // 确认收货(api调用)
    public static function confirmReceive($orderid){
        // renting验证
        $DAORenting  = new DAORenting();
        $rentingInfo = $DAORenting->getRentingInfoByOrderid($orderid);
        Interceptor::ensureNotEmpty($rentingInfo, ERROR_BIZ_RANTING_DATA_NOT_EXIST, $rentingInfo['relateid']);

        // user_renting验证
        $userRentingInfo = UserRenting::getUserRentingInfo($rentingInfo['relateid']);
        Interceptor::ensureNotEmpty($userRentingInfo, ERROR_BIZ_RANTING_DATA_NOT_EXIST, $rentingInfo['relateid']);

        // order验证
        $orderInfo = Order::getOrderInfo($orderid);
        Interceptor::ensureNotEmpty($orderInfo, ERROR_BIZ_ORDER_STATUS_UNVALID, $orderid);
        if ($orderInfo['vip'] > 0) {
            $packageInfo = Package::getPackageInfoByOrderid($orderid);
            $price = $packageInfo['deposit_price'];
            if ($price > 0) {
                try {
                    Account::discountToSystem($orderid, $price, "补还用户折扣葡萄");
                } catch (MySQLException $e) {
                    // throw new BizException($e->getMessage());
                }
            }
        }
        // 租赁处理
        list($relateid, $rentid, $rentlogid) = UserRenting::receiveUserRentings($rentingInfo['relateid'], $rentingInfo['rentid'], $rentingInfo['orderid'], $rentingInfo['month'], $userRentingInfo['startime']);

        // 快递签收
        try {
            Order::receive($orderid);
        } catch (Exception $e) {
            Logger::log("user_renting", "rent_confirmReceive", array("orderid"=>$orderid,"code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
            throw new BizException($e->getMessage());
        }

        $DAORentingLog = new DAORentingLog();
        if($rentingInfo['express_type'] == UserRenting::USER_RENTING_EXPRESS_TYPE_FIRST){
            $packageModel = new Package();
            $packageModel->doExpressReceiveByPackageId($rentingInfo['packageid']);

            $startime = date('Y-m-d H:i:s');
            $endtime  = date('Y-m-d H:i:s', strtotime($startime . " +" . $rentingInfo['month'] * 30 . " day"));
            $Message = new Message($rentingInfo['uid']);
            $Message->sendMessage(DAOMessage::TYPE_ORDER_RECEIVE, array($rentingInfo['month'], $endtime));
        }
        if($rentingInfo['express_type'] == UserRenting::USER_RENTING_EXPRESS_TYPE_TRANSMIT){
            $DAOUserRenting = new DAOUserRenting();
            $prevUserRentingInfo = $DAOUserRenting->getPrevUserRentingInfoBySn($rentingInfo['uid'], $rentingInfo['sn']);
            if (! empty($prevUserRentingInfo)) {

                // 修改上一个用户租借的状态
                $prevRentingInfo = $DAORenting->getRentingInfo($prevUserRentingInfo['rentid']);
                list($preRelateid, $preRentid, $preRentlogid) = UserRenting::updateUserRentingsStatus($prevRentingInfo['relateid'], $prevRentingInfo['rentid'], $orderInfo['orderid'], UserRenting::USER_RENTING_TYPE_FINISH, UserRenting::USER_RENTING_TYPE_FINISH_ST_NO_COMMENT, "租赁流转");

                // 退上一个用户租借的押金
                $DAOOrders = new DAOOrders();
                $orderInfo = $DAOOrders->getRentOrderInfoByRelateid($prevUserRentingInfo['packageid']);
                if(!empty($orderInfo)){
                    Account::unfreeze($orderInfo['uid'], $orderInfo['deposit_price'], $orderInfo['orderid'], "押金解冻");
                }

                // 发消息告知租赁完成
                $Message = new Message($prevRentingInfo['uid']);
                $Message->sendMessage(DAOMessage::TYPE_ORDER_RENT_FINISH_RECEIVE, array($orderInfo['deposit_price']));

                // 状态确认
                $DAORentingLog->updateRentingLogResult($preRentlogid, Renting::STATUS_FINISH);
            }
        }

        // 微信消息
        $packageInfo = Package::getPackageInfoByPackageid($userRentingInfo['packageid']);
        $option = array(
            'orderid' => $rentingInfo['orderid'],
            'title'   => $packageInfo['description'],
            'payment' => ceil($rentingInfo['rent_price']) . "葡萄"
        );
        $userRentingInfo = UserRenting::getUserRentingInfo($rentingInfo['relateid']);
        $param = array("orderid" => $rentingInfo['orderid'], "relateid" => $relateid,"rentid" => $rentid);
        $args  = array($userRentingInfo['month'], date('Y-m-d H:i:s', strtotime($userRentingInfo['endtime'] . " -15 day")));
        WxMessage::receiveSuccess($rentingInfo['uid'], $option, $param, $args);

        // 状态确认
        $DAORentingLog->updateRentingLogResult($rentlogid, Renting::STATUS_FINISH);

        return true;
    }

    // 运营撤销订单
    public static function revoker($rentid)
    {
        // renting验证
        $rentingInfo = Renting::getRentingInfo($rentid);
        Interceptor::ensureNotEmpty($rentingInfo, ERROR_BIZ_RANTING_DATA_NOT_EXIST, $rentid);

        // user_renting验证
        $userRentingInfo = UserRenting::getUserRentingInfo($rentingInfo['relateid']);
        Interceptor::ensureNotEmpty($userRentingInfo, ERROR_BIZ_RANTING_DATA_NOT_EXIST, $rentingInfo['relateid']);

        // order验证
        $orderInfo = Order::getOrderInfo($rentingInfo['orderid']);
        Interceptor::ensureNotEmpty($orderInfo, ERROR_BIZ_ORDER_STATUS_UNVALID, $rentingInfo['orderid']);

        // 租赁处理
        list($relateid, $rentid, $rentlogid) = UserRenting::updateUserRentingsStatus($rentingInfo['relateid'], $rentid, $rentingInfo['orderid'], UserRenting::USER_RENTING_TYPE_FINISH, UserRenting::USER_RENTING_TYPE_FINISH_ST_REVOKE, "撤销订单");

        // 资金处理
        $DAOOrders = new DAOOrders();
        try {
            // 退现金
            if ($orderInfo['pay_price'] > 0 && $orderInfo['status'] == DAOOrders::ORDER_STATUS_CONFIRM) {
                Refund::call($orderInfo['orderid'], Pay::PAY_WECHAT_XIAOCHENGXU, $orderInfo['pay_price']);
            }

            $DAOOrders->startTrans();

            // 退押金
            if ($rentingInfo['deposit_price'] > 0) {
                Account::unfreeze($rentingInfo['uid'], $rentingInfo['deposit_price'], $rentingInfo['orderid'], "订单撤销退押金");
            }

            // 退租金
            if($rentingInfo['rent_price'] > 0){
                Account::unfreeze($rentingInfo['uid'], $rentingInfo['rent_price'], $rentingInfo['orderid'], "订单撤销退押金");
            }

            // 退代金券
            if ($rentingInfo['pay_coupon'] > 0) {
                if($rentingInfo['type'] == UserRenting::USER_RENTING_TYPE_RECEIVED && $rentingInfo['status'] == UserRenting::USER_RENTING_TYPE_RECEIVED_ST_PAY){
                    Account::unfreezeCash($rentingInfo['uid'], $rentingInfo['pay_coupon'], $rentingInfo['orderid'], "订单撤销退代金券");
                }else{
                    Account::refundCash($rentingInfo['uid'], $rentingInfo['pay_coupon'], $rentingInfo['orderid'], "订单撤销退代金券");
                }
            }

            $DAOOrders->commit();
        } catch (Exception $e) {
            $DAOOrders->rollback();
            Logger::log("user_renting", "userRenting_revoker", array("rentid"=>$rentid,"code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
            throw new BizException($e->getMessage());
        }

        // 状态确认
        $DAORentingLog = new DAORentingLog();
        $DAORentingLog->updateRentingLogResult($rentlogid, Renting::STATUS_FINISH);

        return true;
    }

    // 快递处理
    public static function express($uid, $orderid, $num, $receiveContact, $sendContact, $desc)
    {
        if(empty($receiveContact) || empty($sendContact)){
            Logger::log("user_renting", "express", array("uid" => $uid,"orderid" => $orderid));
        }
        $recName      = $receiveContact["contact_name"];
        $recPrintAddr = $receiveContact["contact_city"] . $receiveContact["contact_county"] . $receiveContact["contact_address"];
        if (preg_match("/^(13|15|18)d{9}$/", $receiveContact["contact_phone"])) {
            $recMobile = $receiveContact["contact_phone"];
            $recTel    = "";
        } else {
            $recMobile = "";
            $recTel    = $receiveContact["contact_phone"];
        }

        $sendNamek     = $sendContact["contact_name"];
        $sendPrintAddr = $sendContact["contact_city"] . $sendContact["contact_county"] . $sendContact["contact_address"];
        $sendCity      = $sendContact["contact_city"];
        if (preg_match("/^(13|15|18)d{9}$/", $sendContact["contact_phone"])) {
            $sendMobile = $sendContact["contact_phone"];
            $sendTel    = "";
        } else {
            $sendMobile = "";
            $sendTel    = $sendContact["contact_phone"];
        }
        Logger::log("user_renting", "express", array("uid" => $uid,"orderid" => $orderid,"recName"=>$recName,"recPrintAddr"=>$recPrintAddr,"recMobile"=>$recMobile,"recTel"=>$recTel,"sendNamek"=>$sendNamek,"sendPrintAddr"=>$sendPrintAddr,"sendMobile"=>$sendMobile,"sendTel"=>$sendTel));
        return Express::eorder(Express::JD, "jd", $orderid, $recName, $recPrintAddr, $recMobile, $recTel, $sendNamek, $sendPrintAddr, $sendMobile, $sendTel, 5, $num);
    }

    public static function cancelPay($rentingInfo, $relateid, $rentid, $userid)
    {
    	$message = new Message($userid);
    	$penalty = ceil($rentingInfo['rent_price'] * 0.2);
    	$message->sendMessage(DAOMessage::TYPE_ORDER_NO_PAY, array($penalty));
    	return true;
    }
}
?>