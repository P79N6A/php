<?php
class Seizing
{
    const ZEIZING_PRICE       = 1;
    const ZEIZING_NUMBER_INIT = 200000;


    // 夺宝下单
    public static function order($uid, $packageId){
        $packageInfo = Package::getPackageInfoByPackageid($packageId);
        Interceptor::ensureNotEmpty($packageInfo, ERROR_BIZ_PACKET_NOT_EXIST);
        Interceptor::ensureNotFalse(DAOPackage::STATUS_ONLINE == $packageInfo['status'], ERROR_BIZ_PACKET_NOT_ONLINE);
        Interceptor::ensureNotFalse(DAOPackage::SELL_TYPE_ZEIZING == $packageInfo['sales_type'], ERROR_BIZ_PACKET_SELL_TYPE);

        // 葡萄币是否够支付
        $account = Account::getAccountList($uid);
        Interceptor::ensureNotFalse($account['grape'] >= self::ZEIZING_PRICE, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

        // 是否重复购买
        $DAOSeizing = new DAOSeizing();
        Interceptor::ensureFalse($DAOSeizing->isExistSeizing($uid, $packageId), ERROR_BIZ_ZEIZING_REPEAT_ORDER);

        // 是否抢完
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key = "seizing_" . $packageId;
        Interceptor::ensureFalse(($sort = $cache->INCR($key)) > $packageInfo['deposit_price'], ERROR_BIZ_ZEIZING_SELL_OUT);
        $number = self::ZEIZING_NUMBER_INIT + $sort;

        $orderid = Order::getOrderId();
        $grape = self::ZEIZING_PRICE;

        $DAOAccount = new DAOAccount($uid);
        try {
            // 夺宝处理
            $addtime = $DAOSeizing->addSeizing($uid, $packageId, $number, $grape, $orderid);

            $DAOAccount->startTrans();

            // 支出葡萄
            Account::deductGrape($uid, $grape, $orderid, "支出葡萄");

            $DAOAccount->commit();
        } catch (Exception $e) {
            $DAOAccount->rollback();
            $cache->DECR($key);
            Logger::log("seizing", "account", array("uid"=>$uid,"packageid"=>$packageid,"code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
            throw new BizException($e->getMessage());
        }

        // 最后夺宝用户触发中奖、下单逻辑
        if($sort == $packageInfo['deposit_price']){
            // package下线
            $DAOPackage = new DAOPackage();
            $DAOPackage->updatePackageOnline($packageId, DAOPackage::STATUS_SELLOUT);

            //  删除redis计数器
            $cache->delete($key);
        }

        return array($orderid, $number,$addtime);
    }

    // 夺宝列表
    public static function getList($packageid, $num, $offset){
        $DAOSeizing = new DAOSeizing();
        $list = $DAOSeizing->getSeizingList($packageid, $num, $offset);
        if (empty($list)) {
            return array(array(), 0, 0, false);
        }

        $arrTemp = $packageids = $uids = array();
        foreach($list as $item){
            array_push($packageids, $item['packageid']);
            array_push($uids, $item['uid']);
        }
        $packageInfos = Package::getPackageInfosByPackageids($packageids);
        $userInfos    = User::getUserInfos($uids);

        foreach ($list as $key => $val) {
            $seizingInfo = array(
                "uid"        => $val['uid'],
                "packageid"  => (string) $val['packageid'],
                "number"     => $val['number'],
                "orderid"    => $val['orderid'],
                "type"       => $val['type'],
                "win_number" => $val['win_number'],
                "addtime"    => $val['addtime'].".".$val['msec'],
            );
            $tempPackageInfo  = $packageInfos[$val['packageid']];
            $packageInfo = array(
                "id"         => $tempPackageInfo['id'],
                "packageid"  => $tempPackageInfo['packageid'],
                "cover"      => Util::joinStaticDomain($tempPackageInfo['cover']),
                "cover_type" => $tempPackageInfo['cover_type'],
                "num"        => $tempPackageInfo['num'],
                "location"      => $tempPackageInfo['location'],
                "deposit_price" => $tempPackageInfo['deposit_price'],
                "description"   => $tempPackageInfo['description'],
                "status"        => $tempPackageInfo['status'],
                "sales_type"    => $tempPackageInfo['sales_type'],

            );
            $userInfo = $userInfos[$val['uid']];
            $arrTemp[] = array('seizingInfo' => $seizingInfo, 'packageInfo' => $packageInfo, 'userInfo' => $userInfo);

            $offset = $val['id'];
        }
        $total = $DAOSeizing->getSeizingTotal($packageid);
        $more = (bool) $DAOSeizing->getSeizingMore($packageid, $offset);
        return array($arrTemp, $total, $offset, $more);
    }

    /**
     * 用户夺宝列表
     * @param int $uid
     * @param boolean $type
     * @param int $num
     * @param int $offset
     */
    public static function getUserList($uid, $type, $num, $offset)
    {
        $DAOSeizing = new DAOSeizing();
        $list = $DAOSeizing->getUserSeizingList($uid, $type, $num, $offset);
        if (empty($list)) {
            return array(array(), 0, 0, false);
        }

        $arrTemp = $packageids = $uids = array();
        foreach($list as $item){
            array_push($packageids, $item['packageid']);
        }
        $packageInfos = Package::getPackageInfosByPackageids($packageids);
        $seizingCompleteAmount = self::getSeizingCompleteAmount($packageids);

        foreach ($list as $key => $val) {
            $seizingInfo = array(
                "uid"        => $val['uid'],
                "packageid"  => (string) $val['packageid'],
                "number"     => $val['number'],
                "orderid"    => $val['orderid'],
                "type"       => $val['type'],
                "win_number" => $val['win_number'],
                "addtime"    => $val['addtime'].".".$val['msec'],
                "cnt"        => $seizingCompleteAmount[$val['packageid']],
            );
            $tempPackageInfo  = $packageInfos[$val['packageid']];
            $packageInfo = array(
                "id"         => $tempPackageInfo['id'],
                "packageid"  => $tempPackageInfo['packageid'],
                "cover"      => Util::joinStaticDomain($tempPackageInfo['cover']),
                "cover_type" => $tempPackageInfo['cover_type'],
                "num"        => $tempPackageInfo['num'],
                "location"      => $tempPackageInfo['location'],
                "deposit_price" => $tempPackageInfo['deposit_price'],
                "description"   => $tempPackageInfo['description'],
                "status"        => $tempPackageInfo['status'],
                "sales_type"    => $tempPackageInfo['sales_type'],
            );

            $arrTemp[] = array('seizingInfo' => $seizingInfo, 'packageInfo' => $packageInfo);

            $offset = $val['id'];
        }
        $total = $DAOSeizing->getUserSeizingTotal($uid, $type);
        $more = (bool) $DAOSeizing->getUserSeizingMore($uid, $type, $offset);
        return array($arrTemp, $total, $offset, $more);
    }

    // 获取中奖号
    public static function getWinningNumber($packageId, $total, $ticket){
        $DAOSeizing = new DAOSeizing();
        $list = $DAOSeizing->getLastTenSeizing($packageId);

        $num = $ticket;
        foreach ($list as $item) {
            $date = intval(date('His', strtotime($item['addtime'])) . $item['msec']);
            $num += $date;
        }
        return self::ZEIZING_NUMBER_INIT + ($num % $total) + 1;
    }

    // 获取完成数
    public static function getSeizingCompleteAmount($packageids){
        $DAOSeizing = new DAOSeizing();
        $list = $DAOSeizing->getSeizingCompleteAmount($packageids);

        $arrTemp = array();
        foreach($list as $item){
            $arrTemp[$item['packageid']] = $item['cnt'];
        }
        return $arrTemp;
    }

}