<?php
class UserRenting
{
    const USER_RENTING_TYPE_RENT        = 'RENT';  //租用
    const USER_RENTING_TYPE_RELET       = 'RELET'; //续租

    const USER_RENTING_TYPE_RECEIVED = 100;  //待收货
    const USER_RENTING_TYPE_RENTING  = 200;  //租用中  （租期开始~租期到期15天时间段）
    const USER_RENTING_TYPE_TRANSMIT = 300;  //待传递  （租期到期15天~租期到期时间段）
    const USER_RENTING_TYPE_FINISH   = 400;  //完成

    const USER_RENTING_TYPE_RECEIVED_ST_PAY      = 101;  //待支付
    const USER_RENTING_TYPE_RECEIVED_ST_NO_SEND  = 102;  //待发货
    const USER_RENTING_TYPE_RECEIVED_ST_SEND     = 103;  //已发货
    const USER_RENTING_TYPE_RENTING_ST_INIT      = 200;  //租用中 （租期开始~租期到期15天时间段）
    const USER_RENTING_TYPE_TRANSMIT_ST_INIT     = 300;  //待传递 （租期到期15天~租期到期时间段）
    const USER_RENTING_TYPE_FINISH_ST_NO_COMMENT = 401;  //待评价
    const USER_RENTING_TYPE_FINISH_ST_COMMENT    = 402;  //已评价
    const USER_RENTING_TYPE_FINISH_ST_CANCEL     = 403;  //已取消
    const USER_RENTING_TYPE_FINISH_ST_REVOKE     = 404;  //已撤销

    const USER_RENTING_EXPRESS_TYPE_NO          = 0;  // 无需快递
    const USER_RENTING_EXPRESS_TYPE_FIRST       = 1;  // 首次快递
    const USER_RENTING_EXPRESS_TYPE_TRANSMIT    = 2;  // 传递快递

    // 续租
    public static function delayUserRentings($uid, $relateid, $orderid, $packageid, $sn, $num, $month, $rent_type, $express_type, $rent_price, $deposit_price, $pay_price, $pay_coupon, $service_price, $express_price)
    {
        $DAORenting = new DAORenting();
        // 租用处理
        try {
            $DAORenting->startTrans();

            // renting
            $rentid  = $DAORenting->addRenting($uid, $relateid, $orderid, $packageid, $sn, $num, $month, $rent_type, $express_type, $rent_price, $deposit_price, $pay_price, $pay_coupon, $service_price, $express_price);

            // renting_log
            $rentingInfo   = Renting::getRentingInfo($rentid);
            $DAORentingLog = new DAORentingLog();
            $rentlogid = $DAORentingLog->addRentingLog($uid, $orderid, $relateid, $rentid, $packageid, $sn, json_encode($rentingInfo), Renting::STATUS_FINISH, "续租订单");

            $DAORenting->commit();
        } catch (Exception $e) {
            $DAORenting->rollback();
            Logger::log("user_renting", "userRenting_delayUserRentings", array("uid"=>$uid,"packageid"=>$packageid,"orderid"=>$orderid,"relateid"=>$relateid,"code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
            throw new BizException($e->getMessage());
        }
        return array($relateid, $rentid, $rentlogid);
    }

    // 租借
    public static function addUserRentings($uid, $orderid, $packageid, $sn, $num, $month, $rent_type, $express_type, $rent_price, $deposit_price, $pay_price, $pay_coupon, $service_price, $express_price)
    {
        $DAORenting = new DAORenting();
        // 租用处理
        try {
            $DAORenting->startTrans();

            // user_renting
            $DAOUserRenting = new DAOUserRenting();
            $relateid = $DAOUserRenting->addUserRenting($uid, $packageid, $sn, $num, $month, $rent_price);

            // renting
            $DAORenting = new DAORenting();
            $rentid = $DAORenting->addRenting($uid, $relateid, $orderid, $packageid, $sn, $num, $month, $rent_type, $express_type, $rent_price, $deposit_price, $pay_price, $pay_coupon, $service_price, $express_price);

            // renting_log
            $rentingInfo   = Renting::getRentingInfo($rentid);
            $DAORentingLog = new DAORentingLog();
            $rentlogid = $DAORentingLog->addRentingLog($uid, $orderid, $relateid, $rentid, $packageid, $sn, json_encode($rentingInfo), Renting::STATUS_FINISH, "租赁订单");

            // 更新$rentid
            $DAOUserRenting = new DAOUserRenting();
            $DAOUserRenting->updateUserRentingRentidOrderid($relateid, $rentid, $orderid);

            $DAORenting->commit();
        } catch (Exception $e) {
            $DAORenting->rollback();
            Package::updatePackageOnline($packageid, DAOPackage::STATUS_ONLINE);
            Logger::log("user_renting", "userRenting_delayUserRentings", array("uid" => $uid,"packageid" => $packageid,"orderid" => $orderid,"code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
            throw new BizException($e->getMessage());
        }
        return array($relateid, $rentid, $rentlogid);
    }

    // 收货(快递回调、用户触发)
    public static function receiveUserRentings($relateid, $rentid, $orderid, $month, $startime){
        $DAORenting = new DAORenting();
        try {
            $DAORenting->startTrans();

            // user_renting
            $DAOUserRenting = new DAOUserRenting();
            if(empty($startime) || $startime == '0000-00-00 00:00:00'){
                $startime = date('Y-m-d H:i:s');
                $endtime  = date('Y-m-d H:i:s', strtotime($startime . " +" . $month * 30 . " day"));
                $DAOUserRenting->updateUserRentingReceive($relateid, UserRenting::USER_RENTING_TYPE_RENTING, UserRenting::USER_RENTING_TYPE_RENTING_ST_INIT, $startime, $endtime);
            }else{
                $DAOUserRenting->updateUserRentingStatus($relateid, UserRenting::USER_RENTING_TYPE_RENTING, UserRenting::USER_RENTING_TYPE_RENTING_ST_INIT);
            }

            // renting
            $DAORenting->updateRentingStatus($rentid, UserRenting::USER_RENTING_TYPE_RENTING, UserRenting::USER_RENTING_TYPE_RENTING_ST_INIT);

            // renting_log
            $rentingInfo = Renting::getRentingInfo($rentid);
            $DAORentingLog = new DAORentingLog();
            $rentlogid = $DAORentingLog->addRentingLog($rentingInfo['uid'], $orderid, $rentingInfo['relateid'], $rentingInfo['rentid'], $rentingInfo['packageid'], $rentingInfo['sn'], json_encode($rentingInfo), Renting::STATUS_INIT, "确认收货");

            $DAORenting->commit();
        } catch (Exception $e) {
            $DAORenting->rollback();
            Logger::log("user_renting", "receiveUserRentings", array("relateid" => $relateid,"rentid" => $rentid,"type" => $type,"status" => $status,"code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
            throw new BizException($e->getMessage());
        }
        return array($relateid, $rentid, $rentlogid);
    }

    // 租赁完成
    public static function updateUserRentingsRentFinish($relateid, $rentid, $orderid, $type, $status)
    {
        $DAOUserRenting = new DAOUserRenting();
        try {
            $DAOUserRenting->startTrans();

            // user_renting
            $DAOUserRenting->updateUserRentingStatus($relateid, $type, $status);

            // renting
            $DAORenting = new DAORenting();
            $DAORenting->updateRentingResult($rentid, $type, $status, Renting::STATUS_FINISH);

            // renting_log
            $rentingInfo = Renting::getRentingInfo($rentid);
            $DAORentingLog = new DAORentingLog();
            $rentlogid = $DAORentingLog->addRentingLog($rentingInfo['uid'], $orderid, $relateid, $rentid, $rentingInfo['packageid'], $rentingInfo['sn'], json_encode($rentingInfo), Renting::STATUS_FINISH, "租赁完成");

            $DAOUserRenting->commit();
        } catch (Exception $e) {
            $DAOUserRenting->rollback();
            Logger::log("user_renting", "updateUserRentingsFinish", array("relateid" => $relateid,"rentid" => $rentid,"type" => $type,"status" => $status,"code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
            throw new BizException($e->getMessage());
        }
        return array($relateid, $rentid, $rentlogid);
    }

    // 续租完成
    public static function updateUserRentingsReletFinish($relateid, $rentid, $orderid, $month, $rent_price, $type, $status)
    {
        $DAOUserRenting = new DAOUserRenting();
        try {
            $DAOUserRenting->startTrans();

            // user_renting
            $userRentingInfo = $DAOUserRenting->getUserRentingInfo($relateid);
            $endtime = date('Y-m-d H:i:s', strtotime($userRentingInfo['endtime'] . " +" . $month * 30 . " day"));
            $grape = $userRentingInfo['grape'] + $rent_price * $month;
            $month = $userRentingInfo['month'] + $month;
            $DAOUserRenting->delayUserRenting($relateid, $month, $grape, $endtime, $type, $status);

            // renting
            $DAORenting = new DAORenting();
            $DAORenting->updateRentingResult($rentid, $type, $status, Renting::STATUS_FINISH);

            // renting_log
            $rentingInfo   = Renting::getRentingInfo($rentid);
            $DAORentingLog = new DAORentingLog();
            $rentlogid = $DAORentingLog->addRentingLog($rentingInfo['uid'], $orderid, $relateid, $rentid, $rentingInfo['packageid'], $rentingInfo['sn'], json_encode($rentingInfo), Renting::STATUS_FINISH, "续租完成");

            // 更新$rentid
            $DAOUserRenting = new DAOUserRenting();
            $DAOUserRenting->updateUserRentingRentidOrderid($relateid, $rentid, $orderid);

            $DAOUserRenting->commit();
        } catch (Exception $e) {
            $DAOUserRenting->rollback();
            Logger::log("user_renting", "updateUserRentingsReletFinish", array("relateid" => $relateid,"rentid" => $rentid,"type" => $type,"status" => $status,"code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
            throw new BizException($e->getMessage());
        }
        return array($relateid, $rentid, $rentlogid);
    }

    // 修改状态
    public static function updateUserRentingsStatus($relateid, $rentid, $orderid, $type, $status, $remark)
    {
        $DAOUserRenting = new DAOUserRenting();
        try {
            $DAOUserRenting->startTrans();

            // user_renting
            $DAOUserRenting->updateUserRentingStatus($relateid, $type, $status);

            // renting
            $DAORenting = new DAORenting();
            $DAORenting->updateRentingStatus($rentid, $type, $status);

            // renting_log
            $rentingInfo = Renting::getRentingInfo($rentid);
            $DAORentingLog = new DAORentingLog();
            $rentlogid = $DAORentingLog->addRentingLog($rentingInfo['uid'], $orderid, $relateid, $rentid, $rentingInfo['packageid'], $rentingInfo['sn'], json_encode($rentingInfo), Renting::STATUS_INIT, $remark);

            $DAOUserRenting->commit();
        } catch (Exception $e) {
            $DAOUserRenting->rollback();
            Logger::log("user_renting", "updateUserRentingsStatus", array("relateid" => $relateid,"rentid" => $rentid,"type" => $type,"status" => $status,"code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
            throw new BizException($e->getMessage());
        }
        return array($relateid, $rentid, $rentlogid);
    }

    // 租用列表
    public static function getRentingList($uid, $type, $num, $offset)
    {
        $DAOUserRenting = new DAOUserRenting();
        $list = $DAOUserRenting->getUserRentingList($uid, $type, $num, $offset);
        if (empty($list)) {
            return array(array(),0,0,false);
        }

        $arrTemp = $packageids = $orderids = $rentids = array();
        foreach($list as $item){
            if($item['type'] == UserRenting::USER_RENTING_TYPE_RECEIVED){
                array_push($orderids, $item['orderid']);
            }
            array_push($rentids, $item['rentid']);
            array_push($packageids, $item['packageid']);
        }
        $packageInfos = Package::getPackageInfosByPackageids($packageids);
        $expressInfos = Express::getLastArea($orderids);
        $rentingInfos = Renting::getRentingInfos($rentids);

        foreach ($list as $key => $val) {
            $userRentingInfo = array(
                "relateid"  => $val['id'],
                "rentid"    => $val['rentid'],
                "packageid" => (string) $val['packageid'],
                "orderid"   => (string) $val['orderid'],
                "type"      => $val['type'],
                "status"    => $val['status'],
                "sn"        => $val['sn'],
                "num"       => $val['num'],
                "month"     => $val['month'],
                "grape"     => $val['grape'],
                "addtime"   => $val['addtime'],
                "startime"  => $val['startime'],
                "endtime"   => $val['endtime'],
                "paytime"   => date('H:i',strtotime($val['addtime'])+900),
            	"lasttime"  => (strtotime($val['addtime']) + 900 - time()) <= 0 ? 0 :(strtotime($val['addtime']) + 900 - time()),
                "expire"    => 0,
            );
            if($userRentingInfo['type']==UserRenting::USER_RENTING_TYPE_RENTING){
                $userRentingInfo['expire'] = round((strtotime($val['endtime'])-time())/3600/24);
            }

            $tempPackageInfo = $packageInfos[$val['packageid']];
            $packageInfo = array(
                "id"         => $tempPackageInfo['id'],
                "packageid"  => (string) $val['packageid'],
                "categoryid" => $tempPackageInfo['categoryid'],
                "sn"         => $tempPackageInfo['sn'],
                "cover"      => Util::joinStaticDomain($tempPackageInfo['cover']),
                "cover_type" => $tempPackageInfo['cover_type'],
                "location"   => $tempPackageInfo['location'],
                "description"=> $tempPackageInfo['description'],
                "vip"        => (bool)$tempPackageInfo['vip'],
            );

            $tempRentingInfo = $rentingInfos[$val['rentid']];
            $rentingInfo = array(
                "express_type"  => $tempRentingInfo['express_type'],
                "deposit_price" => $tempRentingInfo['deposit_price'],
                "rent_price"    => $tempRentingInfo['rent_price'],
                "pay_price"     => $tempRentingInfo['pay_price'],
                "pay_coupon"    => $tempRentingInfo['pay_coupon'],
                "service_price" => $tempRentingInfo['service_price'],
                "express_price" => $tempRentingInfo['express_price'],
            );

            $express = isset($expressInfos[$val['orderid']]) ? $expressInfos[$val['orderid']] : '';

            $arrTemp[] = array('userRentingInfo' => $userRentingInfo, 'packageInfo' => $packageInfo, 'rentingInfo'=>$rentingInfo, 'express'=>$express);
            $offset = $val['id'];
        }

        $total = $DAOUserRenting->getUserRentingTotal($uid, $type);
        $more = (bool) $DAOUserRenting->getUserRentingMore($uid, $type, $offset);
        return array($arrTemp,$total,$offset,$more);
    }

    // 租用详情
    public static function getRentingDetails($relateid){
        $tempUserRentingInfo = self::getUserRentingInfo($relateid);
        $userRentingInfo = array(
            "relateid"  => $tempUserRentingInfo['id'],
            "rentid"    => $tempUserRentingInfo['rentid'],
            "packageid" => (string)$tempUserRentingInfo['packageid'],
            "orderid"   => (string)$tempUserRentingInfo['orderid'],
            "type"      => $tempUserRentingInfo['type'],
            "status"    => $tempUserRentingInfo['status'],
            "sn"        => $tempUserRentingInfo['sn'],
            "num"       => $tempUserRentingInfo['num'],
            "month"     => $tempUserRentingInfo['month'],
            "grape"     => $tempUserRentingInfo['grape'],
            "startime"  => $tempUserRentingInfo['startime'],
            "endtime"   => $tempUserRentingInfo['endtime'],
        );

        $tempPackageInfo = Package::getPackageInfoByPackageid($userRentingInfo['packageid']);
        if($tempPackageInfo['orderid']){
            Pay::rollback($tempPackageInfo['orderid']);
        }
        $tempRentingInfo = Renting::getRentingInfo($userRentingInfo['rentid']);
        $rentingInfo = array(
            "express_type"  => $tempRentingInfo['express_type'],
            "deposit_price" => $tempRentingInfo['deposit_price'],
            "rent_price"    => $tempRentingInfo['rent_price'],
            "pay_price"     => $tempRentingInfo['pay_price'],
            "pay_coupon"    => $tempRentingInfo['pay_coupon'],
            "service_price" => $tempRentingInfo['service_price'],
            "express_price" => $tempRentingInfo['express_price'],
        );

        $tempOrderInfo = Order::getOrderInfo($userRentingInfo['orderid']);
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
        
        return array($userRentingInfo, $rentingInfo, $orderInfo, $tempPackageInfo, $pay);
    }

    // userRenting详情
    public static function getUserRentingInfo($relateid)
    {
        $DAOUserRenting = new DAOUserRenting();
        return $DAOUserRenting->getUserRentingInfo($relateid);
    }

    //  添加数据
    public static function addUserRentingData($uid, $packageid, $sn, $num, $month, $grape)
    {
        $DAOUserRenting = new DAOUserRenting();
        return $DAOUserRenting->addUserRenting($uid, $packageid, $sn, $num, $month, $grape);
    }
}