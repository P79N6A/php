<?php
class Renting
{

    const STATUS_INIT   = 0;  // 初始化
    const STATUS_FINISH = 1;  // 订单生成完成

    // 添加renting
    public static function addRenting($uid, $relateid, $orderid, $packageid, $sn, $num, $month, $rent_type, $express_type, $rent_price, $deposit_price, $pay_price, $pay_coupon, $service_price, $express_price)
    {
        try {
            $DAORenting = new DAORenting();
            $rentid  = $DAORenting->addRenting($uid, $relateid, $orderid, $packageid, $sn, $num, $month, $rent_type, $express_type, $rent_price, $deposit_price, $pay_price, $pay_coupon, $service_price, $express_price);
            $rentingInfo = self::getRentingInfo($rentid);

            $DAORentingLog = new DAORentingLog();
            $DAORentingLog->addRentingLog($uid, $orderid, $relateid, $rentid, $packageid, $sn, json_encode($rentingInfo), "续租");

        } catch (Exception $e) {
            Logger::log("user_renting_order", "addRenting", array("code" => $e->getCode(),"msg" => $e->getMessage()));
            throw new BizException($e->getMessage());
        }
        return $rentid;
    }

    // 修改租用状态
    public static function updateRentingResult($rentid, $type, $status, $result)
    {
        try {
            $DAORenting = new DAORenting();
            $DAORenting->updateRentingResult($rentid, $type, $status, $result);
            $rentingInfo = self::getRentingInfo($rentid);

            $DAORentingLog = new DAORentingLog();
            $DAORentingLog->addRentingLog($rentingInfo['uid'], $rentingInfo['packageid'], $rentingInfo['sn'], json_encode($rentingInfo));

        } catch (Exception $e) {
            throw new BizException($e->getMessage());
        }
        return true;
    }

    // 修改状态
    public static function updateRentingStatus($rentid, $type, $status)
    {
        try {
            $DAORenting = new DAORenting();
            $DAORenting->updateRentingStatus($rentid, $type, $status);
            $rentingInfo = self::getRentingInfo($rentid);

            $rentingInfo   = Renting::getRentingInfo($rentid);
            $DAORentingLog = new DAORentingLog();
            $rentlogid = $DAORentingLog->addRentingLog($rentingInfo['uid'], $rentingInfo['orderid'], $rentingInfo['relateid'], $rentid, $rentingInfo['packageid'], $rentingInfo['sn'], json_encode($rentingInfo), Renting::STATUS_FINISH, "续租订单");

        } catch (Exception $e) {
            throw new BizException($e->getMessage());
        }
        return true;
    }

    // 确认支付订单
    public static function confirm($rentid, $pay_price, $pay_coupon)
    {
        try {
            $DAORenting = new DAORenting();

            $DAORenting->confirm($rentid, $pay_price, $pay_coupon);

            $rentingInfo   = Renting::getRentingInfo($rentid);
            $DAORentingLog = new DAORentingLog();
            $rentlogid = $DAORentingLog->addRentingLog($rentingInfo['uid'], $rentingInfo['orderid'], $rentingInfo['relateid'], $rentid, $rentingInfo['packageid'], $rentingInfo['sn'], json_encode($rentingInfo), Renting::STATUS_FINISH, "续租订单");


        } catch (Exception $e) {
            throw new BizException($e->getMessage());
        }
        return true;
    }

    // 是否重复
    public static function isExistRenting($uid, $packageid, $sn, $month)
    {
        $DAORenting = new DAORenting();
        return $DAORenting->isExistRenting($uid, $packageid, $sn, $month);
    }

    // renting详情
    public static function getRentingInfo($rentid)
    {
        $DAORenting = new DAORenting();
        return $DAORenting->getRentingInfo($rentid);
    }

    // 批量获取renting详情
    public static function getRentingInfos($rentids)
    {
        $arrTemp = array();
        if (empty($rentids)) {
            return $arrTemp;
        }
        $DAORenting = new DAORenting();
        $list = $DAORenting->getRentingInfos($rentids);
        foreach ($list as $item) {
            $arrTemp[$item['rentid']] = $item;
        }
        return $arrTemp;
    }
}