<?php
class Relet
{
    public static function order($uid, $relateid, $month)
    {
        // user_renting验证
        $userRentingInfo = UserRenting::getUserRentingInfo($relateid);
        Interceptor::ensureNotEmpty($userRentingInfo, ERROR_BIZ_RANTING_DATA_NOT_EXIST);
        Interceptor::ensureNotFalse(intval($userRentingInfo['month'] + $month) <= 6, ERROR_BIZ_RANTING_MONTH_NOT_RANGE);
        Interceptor::ensureNotFalse($userRentingInfo['endtime'] > date('Y-m-d H:i:s', strtotime("-15 day")), ERROR_BIZ_RANTING_MONTH_EEPIRE);

        // renting验证
        $DAORenting  = new DAORenting();
        $rentingInfo = $DAORenting->getPrevRentingInfoByRelateid($relateid);
        Interceptor::ensureNotEmpty($rentingInfo, ERROR_BIZ_RANTING_DATA_NOT_EXIST);

        // package验证
        $packageid   = $userRentingInfo['packageid'];
        $packageInfo = Package::getPackageInfoByPackageid($packageid);
        Interceptor::ensureNotEmpty($packageInfo, ERROR_BIZ_PACKET_NOT_EXIST);

        // 葡萄币是否够支付租金
        $account    = Account::getAccountList($uid);
        $rent_price = $packageInfo['rent_price'] * $month;
        Interceptor::ensureNotFalse($account['grape'] >= $rent_price, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

        // 是否重复租用
        Interceptor::ensureFalse($DAORenting->isExistRenting($uid, $packageid, $packageInfo['sn'], $month), ERROR_BIZ_RANTING_REPEAT_ORDER);

        // 取上次订单联系信息
        $prevOrderInfo = Order::getOrderInfo($rentingInfo['orderid']);
        $contact = array(
            "contact_name"     => $prevOrderInfo["contact_name"],
            "contact_zipcode"  => $prevOrderInfo["contact_zipcode"],
            "contact_province" => $prevOrderInfo["contact_province"],
            "contact_city"     => $prevOrderInfo["contact_city"],
            "contact_county"   => $prevOrderInfo["contact_county"],
            "contact_address"  => $prevOrderInfo["contact_address"],
            "contact_national" => $prevOrderInfo["contact_national"],
            "contact_phone"    => $prevOrderInfo["contact_phone"]
        );

        $orderid = Order::getOrderId();
        //$service_price = Package::PACKET_SERVICES_FEE;
        $service_price = 0;

        // 葡萄+代金券支付
        if ($account['cash'] >= $service_price) {
            $pay_coupon    = $service_price;
            $pay_price     = $service_price;
            $express_price = 0;
            $deposit_price = 0;

            // 租用处理
            list ($relateid, $rentid) = UserRenting::delayUserRentings($uid, $relateid, $orderid, $packageid, $packageInfo['sn'], $packageInfo['num'], $month, UserRenting::USER_RENTING_TYPE_RELET, UserRenting::USER_RENTING_EXPRESS_TYPE_NO, $rent_price, $deposit_price, $pay_price, $pay_coupon, $service_price, $express_price);

            // 订单、租金、押金处理
            $DAOOrders = new DAOOrders();
            try {
                $DAOOrders->startTrans();

                // 创建订单
                Order::addReletOrder($orderid, $packageid, $rent_price, $deposit_price, $service_price, $express_price, DAOOrders::ORDER_STATUS_CONFIRM, DAOOrders::ORDER_PAY_STATUS_PAYOFF, DAOOrders::ORDER_PAY_TYPE_COUPON, $pay_price, $pay_coupon, $contact);

                // 冻结押金；支出租金；
                Account::freeze($uid, $deposit_price, $orderid, "冻结押金");
                Account::deductGrape($uid, $rent_price, $orderid, "支出租金");

                // 支出冻结代金券
                Account::deductCash($uid, $pay_coupon, $orderid, "支出代金券");

                $DAOOrders->commit();
            } catch (Exception $e) {
                $DAOOrders->rollback();
                Logger::log("user_renting", "relet_order_coupon", array("uid"=>$uid, "packageid"=>$packageid, "code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
                throw new BizException($e->getMessage());
            }

            // 租赁状态
            list ($relateid, $rentid, $rentlogid) = UserRenting::updateUserRentingsReletFinish($relateid, $rentid, $orderid, $month, $rent_price, UserRenting::USER_RENTING_TYPE_RENTING, UserRenting::USER_RENTING_TYPE_RENTING_ST_INIT);

            // 添加package的orderid
            Package::updatePackageOrderid($packageid, $orderid);

            // 发消息
            $userRentingInfo = UserRenting::getUserRentingInfo($relateid);
            $Message = new Message($rentingInfo['uid']);
            $Message->sendMessage(DAOMessage::TYPE_ORDER_RELET, array($month,$userRentingInfo['month'], date('Y-m-d H:i:s', strtotime($userRentingInfo['endtime']) - 84600 * 15)));

            // 微信消息
            $option  = array(
                'orderid' => $orderid,
                'title'   => $packageInfo['description'],
                'payment' => intval($rent_price) . "葡萄",
            );
            $param = array("orderid" => $orderid,"relateid" => $relateid,"rentid"  => $rentid);
            $args  = array($rentingInfo['month'], $userRentingInfo['month'], date('Y-m-d H:i:s', strtotime($userRentingInfo['endtime']) - 84600 * 15));
            WxMessage::reletSuccess($rentingInfo['uid'], $option, $param, $args);

            // 执行任务
            try {
                Task::execute($uid, Task::TASK_ID_NINE, 1, json_encode(array('grape'=>$rent_price)));
            } catch (Exception $e) {}

            return array($orderid, $relateid, $rentid, false, array());
        }else {
            $pay_coupon    = $account['cash'];
            $pay_price     = $service_price - $account['cash'];
            $express_price = 0;
            $deposit_price = 0;

            // 续租处理
            list ($relateid, $rentid) = UserRenting::delayUserRentings($uid, $relateid, $orderid, $packageid, $packageInfo['sn'], $packageInfo['num'], $month, UserRenting::USER_RENTING_TYPE_RELET, UserRenting::USER_RENTING_EXPRESS_TYPE_NO, $rent_price, $deposit_price, $pay_price, $pay_coupon, $service_price, $express_price);

            // 订单、租金、押金处理
            $DAOOrders = new DAOOrders();
            try {
                $DAOOrders->startTrans();

                // 创建订单
                Order::addRentOrder($orderid, $packageid, $rent_price, $deposit_price, $service_price, $express_price, DAOOrders::ORDER_PAY_STATUS_INITIAL, DAOOrders::ORDER_PAY_STATUS_INITIAL, DAOOrders::ORDER_PAY_TYPE_MIXED, $pay_price, $pay_coupon, $contact);

                // 冻结押金；支出租金；
                Account::freeze($uid, $deposit_price, $orderid, "冻结押金");
                Account::deductGrape($uid, $rent_price, $orderid, "支出租金");

                $DAOOrders->commit();
            } catch (Exception $e) {
                $DAOOrders->rollback();
                Logger::log("user_renting", "relet_order_payment", array("uid"=>$uid, "packageid"=>$packageid, "code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
                throw new BizException($e->getMessage());
            }

            // 续租状态
            list ($relateid, $rentid, $rentlogid) = UserRenting::updateUserRentingsReletFinish($relateid, $rentid, $orderid, $month, $rent_price, UserRenting::USER_RENTING_TYPE_RENTING, UserRenting::USER_RENTING_TYPE_RENTING_ST_INIT);

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
        // renting验证
        Interceptor::ensureNotFalse(! empty($rentingInfo), ERROR_BIZ_RANTING_DATA_NOT_EXIST);
        Interceptor::ensureNotFalse(in_array($rentingInfo['status'], array(UserRenting::USER_RENTING_TYPE_RECEIVED_ST_PAY,UserRenting::USER_RENTING_TYPE_RECEIVED_ST_NO_SEND)), ERROR_BIZ_RANTING_NOT_ALLOW_CANCEL);

        // order验证
        $orderInfo = Order::getOrderInfo($rentingInfo['orderid']);
        Interceptor::ensureNotFalse(in_array($orderInfo["status"],[DAOOrders::ORDER_STATUS_INITIAL,DAOOrders::ORDER_STATUS_CONFIRM]), ERROR_BIZ_ORDER_STATUS_UNVALID, $rentingInfo['orderid']);

        // 状态处理
        list ($relateid, $rentid, $rentlogid) = UserRenting::updateUserRentingsStatus($rentingInfo['relateid'], $rentid, $rentingInfo['order'], UserRenting::USER_RENTING_TYPE_FINISH, UserRenting::USER_RENTING_TYPE_FINISH_ST_CANCEL, "取消订单");

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
            $penalty_amount = ceil($rentingInfo['rent_price'] * $percent);
            $refund_amount  = ceil($rentingInfo['rent_price'] - $penalty_amount);

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

    // 支付确认
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
            Logger::log("user_renting", "relet_confirm", array("uid"=>$rentingInfo['uid'], "orderid"=>$orderid, "code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
            throw new BizException($e->getMessage());
        }

        // 状态确认
        $DAORentingLog = new DAORentingLog();
        $DAORentingLog->updateRentingLogResult($rentlogid, Renting::STATUS_FINISH);

        // 执行任务
        try {
            Task::execute($rentingInfo['uid'], Task::TASK_ID_NINE, 1, json_encode(array('grape'=>$rentingInfo['rent_price'])));
        } catch (Exception $e) {}

        return true;
    }

    // 取消支付
    public static function cancelPay($rentingInfo, $relateid, $rentid, $userid)
    {
        $message = new Message($userid);
        $penalty = $rentingInfo['rent_price'] * 0.2;
        $message->sendMessage(DAOMessage::TYPE_ORDER_NO_PAY, array($penalty));
        return true;
    }
}
?>