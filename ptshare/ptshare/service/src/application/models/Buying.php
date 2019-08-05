<?php
class Buying
{
    const RESULT_INIT   = 0;
    const RESULT_FINISH = 1;

    const BUYING_TYPE_RECEIVED = 100;  //待收货
    const BUYING_TYPE_FINISH   = 400;  //完成

    const BUYING_TYPE_RECEIVED_ST_PAY      = 101;  //待支付
    const BUYING_TYPE_RECEIVED_ST_NO_SEND  = 102;  //待发货
    const BUYING_TYPE_RECEIVED_ST_SEND     = 103;  //已发货
    const BUYING_TYPE_FINISH_ST_NO_COMMENT = 401;  //待评价
    const BUYING_TYPE_FINISH_ST_COMMENT    = 402;  //已评价
    const BUYING_TYPE_FINISH_ST_CANCEL     = 403;  //已取消
    const BUYING_TYPE_FINISH_ST_REVOKE     = 404;  //已撤销

    // 购买订单
    public static function order($uid, $packageid, $contact)
    {
        // package验证
        $packageInfo = Package::getPackageInfo($packageid);
        Interceptor::ensureNotEmpty($packageInfo, ERROR_BIZ_PACKET_NOT_EXIST);
        if (in_array($packageInfo['sell_user_id'], Package::$user_sellout)) {
            $DAOPackage = new DAOPackage();
            $DAOPackage->updatePackageOnline($packageInfo['packageid'], DAOPackage::STATUS_SELLOUT);
        }
        $packageInfo = Package::getPackageInfo($packageid);
        Interceptor::ensureNotFalse(DAOPackage::STATUS_ONLINE == $packageInfo['status'], ERROR_BIZ_PACKET_NOT_ONLINE);
        $packageid = $packageInfo['packageid'];

        // 价格处理
        $userInfo = User::getUserInfo($uid);
        if (1 == $packageInfo['vip']) {
            // vip专享包裹普通用户不允许购买
            Interceptor::ensureNotFalse($userInfo['vip'], ERROR_BIZ_PACKET_VIP_NOT_BUYR_OR_ENT);
            // vip专享包裹原价
            $grape = ceil($packageInfo['deposit_price']);
        }else{
            // 普通包裹vip用户8折，非vip原价
            if ($userInfo['vip']) {
                $grape = ceil($packageInfo['deposit_price'] * Package::PACKET_VIP_PERCENT);
            } else {
                $grape = ceil($packageInfo['deposit_price']);
            }
        }

        // 葡萄币是否够支付
        $account  = Account::getAccountList($uid);
        Interceptor::ensureNotFalse($account['grape'] >= $grape, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

        // 是否重复购买
        $DAOBuying = new DAOBuying();
        Interceptor::ensureFalse($DAOBuying->isExistBuying($uid, $packageid, $packageInfo['sn']), ERROR_BIZ_RANTING_REPEAT_ORDER);

        // package下架
        Interceptor::ensureNotFalse(Package::updatePackageOnline($packageid, DAOPackage::STATUS_SELLOUT), ERROR_SYS_UNKNOWN);

        $orderid = Order::getOrderId();
        $express_price = Package::PACKET_EXPRESS_FEE;
        $service_price = Package::PACKET_SERVICES_FEE;
        $price         = $express_price + $service_price;

        // 葡萄+代金券支付
        if ($account['cash'] >= $price) {
            $pay_price  = 0;
            $pay_coupon = $price;

            // 购买处理
            list ($buyid, $buylogid) =  self::addBuying($uid, $packageid, $packageInfo['sn'], $packageInfo['num'], $grape, $pay_price, $pay_coupon, $orderid);

            // 订单处理
            $DAOOrders = new DAOOrders();
            try {
                $DAOOrders->startTrans();

                // 创建订单
                Order::addBuyingOrder($orderid, $packageid, $grape, 0, $service_price, $express_price, DAOOrders::ORDER_STATUS_CONFIRM, DAOOrders::ORDER_PAY_STATUS_PAYOFF, DAOOrders::ORDER_PAY_TYPE_COUPON, $pay_price,$pay_coupon, $contact, (int)$userInfo['vip']);

                // 支出葡萄；
                Account::deductGrape($uid, $grape, $orderid, "支出葡萄");

                // 支出代金券
                Account::deductCash($uid, $pay_coupon, $orderid, "支出代金券");

                $DAOOrders->commit();
            } catch (Exception $e) {
                $DAOOrders->rollback();
                Package::updatePackageOnline($packageid, DAOPackage::STATUS_ONLINE);
                Logger::log("buying", "order_coupon", array("uid"=>$uid,"packageid"=>$packageid,"code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
                throw new BizException($e->getMessage());
            }

            // 购买状态
            list ($buyid, $buylogid) = self::updateBuyingFinish($buyid, self::BUYING_TYPE_RECEIVED, self::BUYING_TYPE_RECEIVED_ST_NO_SEND);

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
                'payment' => intval($grape) . "葡萄",
                'address' => $contact["contact_province"] . $contact["contact_city"] . $contact["contact_address"]
            );
            $param = array("buyid" => $buyid);
            $args  = array($num,$packageInfo["location"]);
            WxMessage::paymentSuccess($uid, $option, $param, $args, 1);

            // 执行任务
            try {
                Task::execute($uid, Task::TASK_ID_NINE, 1, json_encode(array('grape'=>$grape)));
            } catch (Exception $e) {
            	Logger::log("buying", "task", array("code" => $e->getCode(),"msg" => $e->getMessage()));
            }

            return array($orderid, $buyid, false, array());

        }else{

            // user_renting处理
            $pay_price = $price - $account['cash'];
            $pay_coupon = $account['cash'];

            // 购买处理
            list ($buyid, $buylogid) =  self::addBuying($uid, $packageid, $packageInfo['sn'], $packageInfo['num'], $grape, $pay_price, $pay_coupon, $orderid);

            // 订单处理
            $DAOOrders = new DAOOrders();
            try {
                $DAOOrders->startTrans();

                // 创建订单
                Order::addBuyingOrder($orderid, $packageid, $grape, 0, $service_price, $express_price, DAOOrders::ORDER_STATUS_INITIAL, DAOOrders::ORDER_PAY_STATUS_INITIAL, DAOOrders::ORDER_PAY_TYPE_MIXED, $pay_price,$pay_coupon, $contact, (int)$userInfo['vip']);

                // 支出葡萄;
                Account::deductGrape($uid, $grape, $orderid, "支出租金");

                $DAOOrders->commit();
            } catch (Exception $e) {
                $DAOOrders->rollback();
                Package::updatePackageOnline($packageid, DAOPackage::STATUS_ONLINE);
                Logger::log("buying", "order_payment", array("uid"=>$uid,"packageid"=>$packageid, "code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
                throw new BizException($e->getMessage());
            }

            // 添加package的orderid
            Package::updatePackageOrderid($packageid, $orderid);

            // 购买状态确认
            list ($buyid, $buylogid) = self::updateBuyingFinish($buyid, self::BUYING_TYPE_RECEIVED, self::BUYING_TYPE_RECEIVED_ST_PAY);

            // 支付prepare
            $result = Pay::prepare($uid, Pay::PAY_WECHAT_XIAOCHENGXU, 'CNY', $pay_price, $orderid);

            return array($orderid, $buyid, true, $result);
        }
    }

   // 支付确认
    public static function confirm($orderid, $pay_price, $pay_coupon, $pay_no){
        // buying验证
        $DAOBuying = new DAOBuying();
        $buyingInfo = $DAOBuying->getBuyingInfoByOrderid($orderid);
        Interceptor::ensureNotEmpty($buyingInfo, ERROR_BIZ_ORDER_NOT_EXIST,$orderid);

        // 状态处理
        list ($buyid, $buylogid) = self::updateBuyingStatus($buyingInfo['buyid'], self::BUYING_TYPE_RECEIVED, self::BUYING_TYPE_RECEIVED_ST_NO_SEND, "支付确认");

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
            Logger::log("buying", "confirm_order", array("uid"=>$buyingInfo['uid'], "orderid"=>$orderid, "code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
            throw new BizException($e->getMessage());
        }

        // 状态确认
        $DAOBuyingLog = new DAOBuyingLog();
        $DAOBuyingLog->updateBuyingLogResult($buylogid, self::RESULT_FINISH);

        // 快递处理
        $packageInfo = Package::getPackageInfoByPackageid($buyingInfo['packageid']);
        $orderInfo   = Order::getOrderInfo($orderid);
        $result      = self::express($buyingInfo['uid'], $orderid, $buyingInfo['num'], $orderInfo, $packageInfo['contact'], $packageInfo['description']);

        // 执行任务
        try {
            Task::execute($orderInfo['uid'], Task::TASK_ID_NINE, 1, json_encode(array('grape'=>$orderInfo['grape'])));
        } catch (Exception $e) {
        	Logger::log("buying", "confirm task", array("code" => $e->getCode(),"msg" => $e->getMessage()));
        }

        return true;

    }

    // 取消订单
    public static function cancel($orderid){
        // buying验证
        $DAOBuying = new DAOBuying();
        $buyingInfo = $DAOBuying->getBuyingInfoByOrderid($orderid);
        Interceptor::ensureNotEmpty($buyingInfo, ERROR_BIZ_ORDER_NOT_EXIST,$orderid);
        Interceptor::ensureNotFalse(in_array($buyingInfo['status'], array(self::BUYING_TYPE_RECEIVED_ST_PAY,self::BUYING_TYPE_RECEIVED_ST_NO_SEND)), ERROR_BIZ_RANTING_NOT_ALLOW_CANCEL);

        // 购买处理
        list ($buyid, $buylogid) = self::updateBuyingStatus($buyingInfo['buyid'], self::BUYING_TYPE_FINISH, self::BUYING_TYPE_FINISH_ST_CANCEL, "取消订单");

        // 订单、资金处理
        $orderInfo = Order::getOrderInfo($orderid);
        $DAOOrders = new DAOOrders();
        try {
            // 快递未发货且支付有支付现金，退支付金额
            if ($orderInfo['express_status'] == DAOOrders::ORDER_EXPRESS_STATUS_INITIAL && $orderInfo['pay_price'] > 0) {
                $DAOPay  = new DAOPay();
                $payInfo = $DAOPay->getPayInfo($orderid);
                if ($payInfo['status'] == Pay::PAY_STATUS_SUCCESS) {
                    Refund::call($orderid, Pay::PAY_WECHAT_XIAOCHENGXU, $payInfo['amount']);
                }
            }

            $DAOOrders->startTrans();

            // 交罚金
            $percent = Penalty::PENALTY_PERCENT; // 罚金比例
            $penalty_amount = ceil($orderInfo['grape'] * $percent);
            $refund_amount  = ceil($orderInfo['grape'] - $penalty_amount);

            // 取消订单退回葡萄，交罚金
            Account::refundGrape($orderInfo['uid'], $orderInfo['grape'], $orderid, "取消订单退回");
            Account::deductGrape($orderInfo['uid'], $penalty_amount, $orderid, "取消订单扣除");

            // 订单取消退代金券
            if ($orderInfo['pay_status'] == DAOOrders::ORDER_PAY_STATUS_PAYOFF && $orderInfo['pay_status'] == DAOOrders::ORDER_PAY_TYPE_COUPON) {
                Account::refundCash($orderInfo['uid'], $orderInfo['pay_coupon'], $orderInfo['orderid'], "订单取消退代金券");
            }

            //设置订单状态
            Order::cancel($orderInfo['orderid']);

            $DAOOrders->commit();
        } catch (Exception $e) {
            $DAOOrders->rollback();
            Logger::log("buying", "cancel", array("uid"=>$orderInfo['uid'], "code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
            throw new BizException($e->getMessage());
        }

        $DAOPenalty = new DAOPenalty();
        try {
            $DAOPenalty->startTrans();

            // 宝贝上架
            Interceptor::ensureNotFalse(Package::updatePackageOnline($buyingInfo['packageid'], 'ONLINE'), ERROR_SYS_UNKNOWN);

            // 处罚记录
            $DAOPenalty->add($buyingInfo['uid'], Penalty::PENALTY_TYPE_CANCEL, $buyingInfo['buyid'], "订单被取消扣罚金", $penalty_amount);

            // 状态确认
            $DAOBuyingLog = new DAOBuyingLog();
            $DAOBuyingLog->updateBuyingLogResult($buylogid, self::RESULT_FINISH);

            $DAOPenalty->commit();
        } catch (Exception $e) {
            $DAOPenalty->rollback();
            Logger::log("buying", "cancel_status", array("uid"=>$buyingInfo['uid'], "buyid"=>$buyingInfo['buyid'], "code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
            throw new BizException($e->getMessage());
        }

        return true;
    }

    // 已发货(快递回调)
    public static function expressDeliver($orderid)
    {
        // order验证
        $orderInfo = Order::getOrderInfo($orderid);
        Interceptor::ensureNotEmpty($orderInfo, ERROR_BIZ_ORDER_STATUS_UNVALID, $orderid);

        // buying验证
        $DAOBuying = new DAOBuying();
        $buyingInfo = $DAOBuying->getBuyingInfoByOrderid($orderid);
        Interceptor::ensureNotEmpty($buyingInfo, ERROR_BIZ_ORDER_NOT_EXIST,$orderid);

        // 状态处理
        list ($buyid, $buylogid) = self::updateBuyingStatus($buyingInfo['buyid'], self::BUYING_TYPE_RECEIVED, self::BUYING_TYPE_RECEIVED_ST_SEND, "已发货");

        // 已发货
        try {
            Order::deliver($orderid);
        } catch (Exception $e) {
            Logger::log("buying", "expressDeliver_order", array("orderid"=>$orderid,"code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
            throw new BizException($e->getMessage());
        }

        // 发消息
        try {
            $packageInfo = Package::getPackageInfoByOrderid($orderid);
            if ($packageInfo['source'] == DAOPackage::SOURCE_SELL) {
                $Message = new Message($buyingInfo['uid']);
                $Message->sendMessage(DAOMessage::TYPE_ORDER_SEND);
            }
            if ($packageInfo['source'] == DAOPackage::SOURCE_PACKAGE) {
                $DAOUserRenting = new DAOUserRenting();
                $prevUserRentingInfo = $DAOUserRenting->getPrevUserRentingInfoBySn($buyingInfo['uid'], $buyingInfo['sn']);
                if (! empty($prevUserRentingInfo)) {
                    $DAORenting = new DAORenting();
                    $prevRentingInfo = $DAORenting->getRentingInfo($prevUserRentingInfo['rentid']);
                    $Message = new Message($prevRentingInfo['uid']);
                    $Message->sendMessage(DAOMessage::TYPE_ORDER_RENT_FINISH, array($prevRentingInfo['deposit_price']));
                }
            }
        } catch (Exception $e) {
            Logger::log("buying", "expressDeliver_massage", array("orderid"=>$orderid,"code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
            throw new BizException($e->getMessage());
        }

        // 状态确认
        $DAOBuyingLog = new DAOBuyingLog();
        $DAOBuyingLog->updateBuyingLogResult($buylogid, self::RESULT_FINISH);

        return true;
    }

    // 已收货(快递回调)
    public static function expressReceive($orderid)
    {
        return true;
    }

    // 确认收货
    public static function receiveConfirm($orderid)
    {
        // buying验证
        $DAOBuying = new DAOBuying();
        $buyingInfo = $DAOBuying->getBuyingInfoByOrderid($orderid);
        Interceptor::ensureNotEmpty($buyingInfo, ERROR_BIZ_ORDER_NOT_EXIST,$orderid);
        $packageInfo = Package::getPackageInfoByOrderid($orderid);

        list ($buyid, $buylogid) = self::updateBuyingStatus($buyingInfo['buyid'], self::BUYING_TYPE_FINISH, self::BUYING_TYPE_FINISH_ST_NO_COMMENT, "确认收货");

        $orderInfo = Order::getOrderInfo($orderid);
        // vip用户购买，并且购买的非vip专享包裹，补折扣
        if ($orderInfo['vip'] > 0 && $packageInfo['vip'] == 0) {
            $price = $packageInfo['deposit_price'] - $orderInfo['grape'];
            if ($price > 0) {
                try {
                    Account::discountToSystem($orderid, $price, "补还用户折扣葡萄");
                } catch (MySQLException $e) {
                    // throw new BizException($e->getMessage());
                }
            }
        }

        try {
            Order::receive($orderid);
        } catch (Exception $e) {
            Logger::log("buying", "order_confirmReceive", array("orderid"=>$orderid,"code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
            throw new BizException($e->getMessage());
        }

        if($packageInfo['source'] == DAOPackage::SOURCE_SELL){
            // 退分享者发放奖励
            $packageModel = new Package();
            $packageModel->doExpressReceiveByPackageId($buyingInfo['packageid']);

            // 发消息
        }
        if($packageInfo['source'] == DAOPackage::SOURCE_PACKAGE){
            $DAOUserRenting = new DAOUserRenting();
            $prevUserRentingInfo = $DAOUserRenting->getPrevUserRentingInfoBySn($buyingInfo['uid'], $buyingInfo['sn']);
            if (! empty($prevUserRentingInfo)) {

                // 修改上一个用户租借的状态
                $DAORenting =  new DAORenting();
                $prevRentingInfo = $DAORenting->getRentingInfo($prevUserRentingInfo['rentid']);
                list($preRelateid, $preRentid, $preRentlogid) = UserRenting::updateUserRentingsStatus($prevRentingInfo['relateid'], $prevRentingInfo['rentid'], $prevRentingInfo['orderid'], self::BUYING_TYPE_FINISH, self::BUYING_TYPE_FINISH_ST_NO_COMMENT, "租赁流转");

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
                $DAORentingLog = new DAORentingLog();
                $DAORentingLog->updateRentingLogResult($preRentlogid, Renting::STATUS_FINISH);
            }
        }

        // 状态确认
        $DAOBuyingLog = new DAOBuyingLog();
        $DAOBuyingLog->updateBuyingLogResult($buylogid, self::RESULT_FINISH);

        return true;
    }

    // 撤销订单
    public static function revoker($orderid)
    {
        // order验证
        $orderInfo = Order::getOrderInfo($orderid);
        Interceptor::ensureNotEmpty($orderInfo, ERROR_BIZ_ORDER_STATUS_UNVALID, $orderid);

        // buying验证
        $DAOBuying = new DAOBuying();
        $buyingInfo = $DAOBuying->getBuyingInfoByOrderid($orderid);
        Interceptor::ensureNotEmpty($buyingInfo, ERROR_BIZ_ORDER_NOT_EXIST,$orderid);

        // 状态处理
        list ($buyid, $buylogid) = self::updateBuyingStatus($buyingInfo['buyid'], self::BUYING_TYPE_FINISH, self::BUYING_TYPE_FINISH_ST_REVOKE, "撤销订单");

        // 资金处理
        $DAOOrders = new DAOOrders();
        try {
            // 退现金
            if ($orderInfo['pay_price'] > 0 && $orderInfo['status'] == DAOOrders::ORDER_STATUS_CONFIRM && $orderInfo['ORDER_PAY_STATUS_PAYOFF'] == DAOOrders::ORDER_PAY_STATUS_PAYOFF) {
                Refund::call($orderInfo['orderid'], Pay::PAY_WECHAT_XIAOCHENGXU, $orderInfo['pay_price']);
            }

            $DAOOrders->startTrans();

            // 退葡萄
            if ($orderInfo['grape'] > 0) {
                Account::refundGrape($orderInfo['uid'], $orderInfo['grape'], $orderid, "订单撤销退葡萄");
            }

            // 退代金券
            if ($orderInfo['pay_coupon'] > 0) {
                Account::refundCash($orderInfo['uid'], $orderInfo['pay_coupon'], $orderInfo['orderid'], "订单撤销退代金券");
            }

            $DAOOrders->commit();
        } catch (Exception $e) {
            $DAOOrders->rollback();
            Logger::log("buying", "revoker_order", array("rentid"=>$rentid,"code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
            throw new BizException($e->getMessage());
        }

        // 状态确认
        $DAOBuyingLog = new DAOBuyingLog();
        $DAOBuyingLog->updateBuyingLogResult($buylogid, self::RESULT_FINISH);

        return true;
    }

    // 快递处理
    public static function express($uid, $orderid, $num, $receiveContact, $sendContact, $desc)
    {
        if(empty($receiveContact) || empty($sendContact)){
            Logger::log("buying", "express", array("uid" => $uid,"orderid" => $orderid));
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
        Logger::log("buying", "express", array("uid" => $uid,"orderid" => $orderid,"recName"=>$recName,"recPrintAddr"=>$recPrintAddr,"recMobile"=>$recMobile,"recTel"=>$recTel,"sendNamek"=>$sendNamek,"sendPrintAddr"=>$sendPrintAddr,"sendMobile"=>$sendMobile,"sendTel"=>$sendTel));
        return Express::eorder(Express::JD, "jd", $orderid, $recName, $recPrintAddr, $recMobile, $recTel, $sendNamek, $sendPrintAddr, $sendMobile, $sendTel, 5, $num, $desc);
    }

    // 购买列表
    public static function getList($uid, $type, $num, $offset)
    {
        $DAOBuying = new DAOBuying();
        $list = $DAOBuying->getBuyingList($uid, $type, $num, $offset);
        if (empty($list)) {
            return array(array(), 0, 0, false);
        }

        $arrTemp = $packageids = $orderids = array();
        foreach($list as $item){
            if($item['type'] == Buying::BUYING_TYPE_RECEIVED){
                array_push($orderids, $item['orderid']);
            }
            array_push($packageids, $item['packageid']);
        }
        $packageInfos = Package::getPackageInfosByPackageids($packageids);
        $expressInfos = Express::getLastArea($orderids);

        foreach ($list as $key => $val) {
            $tempPackageInfo = $packageInfos[$val['packageid']];
            if ($tempPackageInfo['sales_type'] == DAOPackage::SELL_TYPE_NORMAL) {
                $lasttime = (strtotime($val['addtime']) + 900 - time()) <= 0 ? 0 : (strtotime($val['addtime']) + 900 - time());
            }
            if ($tempPackageInfo['sales_type'] == DAOPackage::SELL_TYPE_ZEIZING) {
                $lasttime = (strtotime($val['addtime']) + 86400 - time()) <= 0 ? 0 : (strtotime($val['addtime']) + 86400 - time());
            }

            $buyingInfo = array(
                "buyid"       => $val['buyid'],
                "uid"         => $val['uid'],
                "packageid"   => (string) $val['packageid'],
                "orderid"     => (string) $val['orderid'],
                "type"        => $val['type'],
                "status"      => $val['status'],
                "sn"          => $val['sn'],
                "num"         => $val['num'],
                "grape"       => $val['grape'],
                "pay_price"   => $val['pay_price'],
                "pay_coupon"    => $val['pay_coupon'],
                "addtime"       => $val['addtime'],
                "lasttime"      => $lasttime,
            );

            $packageInfo = array(
                "id"         => $tempPackageInfo['id'],
                "packageid"  => (string) $val['packageid'],
                "categoryid" => $tempPackageInfo['categoryid'],
                "sn"         => $tempPackageInfo['sn'],
                "cover"      => Util::joinStaticDomain($tempPackageInfo['cover']),
                "cover_type" => $tempPackageInfo['cover_type'],
                "location"   => $tempPackageInfo['location'],
                "description"=> $tempPackageInfo['description'],
                "sales_type" => $tempPackageInfo['sales_type'],
                "services_fee" => $tempPackageInfo['services_fee'],
                "express_fee"  => $tempPackageInfo['express_fee'],
                "vip"          => (bool)$tempPackageInfo['vip']
            );

            $express = isset($expressInfos[$val['orderid']]) ? $expressInfos[$val['orderid']] : '';
            $arrTemp[] = array('buyingInfo' => $buyingInfo, 'packageInfo' => $packageInfo, 'express'=>$express);

            $offset = $val['buyid'];
        }
        $total = $DAOBuying->getBuyingTotal($uid, $type);
        $more = (bool) $DAOBuying->getBuyingMore($uid, $type, $offset);
        return array($arrTemp, $total, $offset, $more);
    }

    // 购买详情
    public static function getDetails($buyid)
    {
        $tempBuyingInfo = self::getBuyingInfo($buyid);
        $buyingInfo = array(
            "buyid"     => $tempBuyingInfo['buyid'],
            "packageid" => (string)$tempBuyingInfo['packageid'],
            "orderid"   => (string)$tempBuyingInfo['orderid'],
            "type"      => $tempBuyingInfo['type'],
            "status"    => $tempBuyingInfo['status'],
            "sn"        => $tempBuyingInfo['sn'],
            "num"       => $tempBuyingInfo['num'],
            "grape"     => $tempBuyingInfo['grape'],
            "pay_price"   => $tempBuyingInfo['pay_price'],
            "pay_coupon"  => $tempBuyingInfo['pay_coupon'],
            "addtime"     => $tempBuyingInfo['addtime'],
            "lasttime"    => (strtotime($tempBuyingInfo['addtime']) + 900 - time()) <= 0 ? 0 :(strtotime($tempBuyingInfo['addtime']) + 900 - time()),
        );

        $tempPackageInfo = Package::getPackageInfoByPackageid($buyingInfo['packageid']);
        if($tempPackageInfo['orderid']){
            Pay::rollback($tempPackageInfo['orderid']);
        }
        $tempOrderInfo = Order::getOrderInfo($tempBuyingInfo['orderid']);
        $orderInfo = array(
            "orderid" => (string)$tempOrderInfo['orderid'],
            "uid"     => $tempOrderInfo['uid'],
            "type"    => $tempOrderInfo['type'],
            "status"  => $tempOrderInfo['status'],
            "grape"   => $tempOrderInfo['grape'],
            "deposit_price" => $tempOrderInfo['deposit_price'],
            "service_price" => $tempOrderInfo['service_price'],
            "packageid"     => $tempOrderInfo['relateid'],
            "pay_no"        => $tempOrderInfo['pay_no'],
            "pay_status"    => $tempOrderInfo['pay_status'],
            "pay_type"      => $tempOrderInfo['pay_type'],
            "pay_coupon"    => $tempOrderInfo['pay_coupon'],
            "pay_price"     => $tempOrderInfo['pay_price'],
            "express_status"   => $tempOrderInfo['express_status'],
            "express_price"    => $tempOrderInfo['express_price'],
            "express_time"     => $tempOrderInfo['express_time'],
            "receive_time"     => $tempOrderInfo['receive_time'],
            "contact_name"     => $tempOrderInfo['contact_name'],
            "contact_zipcode"  => $tempOrderInfo['contact_zipcode'],
            "contact_province" => $tempOrderInfo['contact_province'],
            "contact_city"     => $tempOrderInfo['contact_city'],
            "contact_county"   => $tempOrderInfo['contact_county'],
            "contact_address"  => $tempOrderInfo['contact_address'],
            "contact_national" => $tempOrderInfo['contact_national'],
            "contact_phone"    => $tempOrderInfo['contact_phone'],
            "addtime"          => $tempOrderInfo['addtime'],
        );
        $tempPackageInfo = Package::getPackageInfoByPackageid($buyingInfo['packageid']);
        $sourceUserInfo  = User::getUserInfo($tempPackageInfo['source_uid']);
        $tempPackageInfo['sender'] =  $sourceUserInfo['nickname'];
        $tempPackageInfo['vip']    =  (bool)$tempPackageInfo['vip'];
        if ($tempPackageInfo['vip']) {
            $tempPackageInfo['vip_price'] = ceil($tempPackageInfo['deposit_price']);
        } else {
            $tempPackageInfo['vip_price'] = ceil($tempPackageInfo['deposit_price'] * Package::PACKET_VIP_PERCENT);
        }

        $uid = Context::get("userid");
        $account    = Account::getAccountList($uid);
        $express_price = Package::PACKET_EXPRESS_FEE;
        $service_price = Package::PACKET_SERVICES_FEE;
        $price    = $express_price + $service_price;
        if ($account['cash'] >= $price) {
            $coupon   = $price;
            $need_pay = 0;
        }else{
            $coupon   = $account['cash'];
            $need_pay = $price - $account['cash'];
        }
        $pay = array('coupon'=>$coupon, 'need_pay'=>$need_pay);

        return array($buyingInfo, $orderInfo, $tempPackageInfo, $pay);
    }

    // 购买处理
    public static function addBuying($uid, $packageid, $sn, $num, $grape, $pay_price, $pay_coupon, $orderid){
        $DAOBuying = new DAOBuying();
        try {
            $DAOBuying->startTrans();

            // buying
            $buyid = $DAOBuying->addBuying($uid, $packageid, $sn, $num, $grape, $pay_price, $pay_coupon, $orderid);

            // buying_log
            $buyingInfo = self::getBuyingInfo($buyid);
            $DAOBuyingLog = new DAOBuyingLog();
            $buylogid = $DAOBuyingLog->addBuyingLog($uid, $orderid, $buyid, $packageid, $sn, json_encode($buyingInfo), self::RESULT_FINISH, '购买初始化');

            $DAOBuying->commit();
        } catch (Exception $e) {
            $DAOBuying->rollback();
            Package::updatePackageOnline($packageid, DAOPackage::STATUS_ONLINE);
            Logger::log("buying", "addBuying", array("uid" => $uid,"packageid" => $packageid,"orderid" => $orderid,"code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
            throw new BizException($e->getMessage());
        }
        return array($buyid, $buylogid);
    }

    // 购买完成
    public static function updateBuyingFinish($buyid, $type, $status){
        $DAOBuying = new DAOBuying();
        try {
            $DAOBuying->startTrans();

            // buying
            $result = $DAOBuying->updateBuyingRelust($buyid, $type, $status, self::RESULT_FINISH);

            // buying_log
            $buyingInfo = self::getBuyingInfo($buyid);
            $DAOBuyingLog = new DAOBuyingLog();
            $buylogid = $DAOBuyingLog->addBuyingLog($buyingInfo['uid'], $buyingInfo['orderid'], $buyid, $buyingInfo['packageid'], $buyingInfo['sn'], json_encode($buyingInfo), self::RESULT_FINISH, '购买完成');

            $DAOBuying->commit();
        } catch (Exception $e) {
            $DAOBuying->rollback();
            Logger::log("buying", "updateBuyingFinish", array("uid" => $uid,"buyid" => $buyid,"orderid" => $buyingInfo['orderid'],"code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
            throw new BizException($e->getMessage());
        }
        return array($buyid, $buylogid);
    }

    // 修改状态
    public static function updateBuyingStatus($buyid, $type, $status, $remark){
        $DAOBuying = new DAOBuying();
        try {
            $DAOBuying->startTrans();

            // buying
            $result = $DAOBuying->updateBuyingStatus($buyid, $type, $status);

            // buying_log
            $buyingInfo = self::getBuyingInfo($buyid);
            $DAOBuyingLog = new DAOBuyingLog();
            $buylogid = $DAOBuyingLog->addBuyingLog($buyingInfo['uid'], $buyingInfo['orderid'], $buyid, $buyingInfo['packageid'], $buyingInfo['sn'], json_encode($buyingInfo), self::RESULT_FINISH, $remark);

            $DAOBuying->commit();
        } catch (Exception $e) {
            $DAOBuying->rollback();
            Logger::log("buying", "updateBuyingStatus", array("uid" => $uid,"buyid" => $buyid,"orderid" => $buyingInfo['orderid'],"code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
            throw new BizException($e->getMessage());
        }
        return array($buyid, $buylogid);
    }

    /**
     * 购买详情
     * @param int $buyid
     * @return array
     */
    public static function getBuyingInfo($buyid){
        $DAOBuying = new DAOBuying();
        return $DAOBuying->getBuyingInfo($buyid);
    }

    /**
     * 根据订单orderid获取购买详情
     * @param string $orderid
     * @return array
     */
    public static function getBuyingInfoByOrderid($orderid){
        $DAOBuying = new DAOBuying();
        return $DAOBuying->getBuyingInfoByOrderid($orderid);
    }

    /**
     * 批量获取购买详情
     * @param array $buyids
     * @return array
     */
    public static function getBuyingInfos($buyids)
    {
        if (! $buyids) {
            return array();
        }
        if (! is_array($buyids)) {
            $buyids = array($buyids);
        }
        $DAOBuying = new DAOBuying();
        $buyingInfos = array();
        $list = $DAOBuying->getBuyingInfos($buyids);
        foreach ($list as $item) {
            $buyingInfos[$item['buyid']] = $item;
        }
        return $buyingInfos;
    }
}
