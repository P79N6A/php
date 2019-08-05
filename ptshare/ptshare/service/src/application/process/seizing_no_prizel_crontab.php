<?php
// nohup php /home/yangqing/work/ptshare/service/src/application/process/user_renting_expire_crontab.php > user_renting_expire_crontab.log 2>&1 &
set_time_limit(0);
ini_set('memory_limit', '1G');
ini_set("display_errors", "On");
error_reporting(E_ALL & ~ E_NOTICE & ~ E_WARNING);
$ROOT_PATH = dirname(dirname(dirname(dirname(__FILE__))));
$LOAD_PATH = array(
    $ROOT_PATH . "/src/www",
    $ROOT_PATH . "/config",
    $ROOT_PATH . "/src/application/controllers",
    $ROOT_PATH . "/src/application/models",
    $ROOT_PATH . "/src/application/models/libs",
    $ROOT_PATH . "/src/application/models/dao"
);
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $LOAD_PATH));

function __autoload($classname)
{
    $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    include_once "$classname.php";
}

require $ROOT_PATH . "/config/server_conf.php";

echo "脚本执行开始:" . date("Y-m-d H:i:s") . "\n";

$DAOSeizing = new DAOSeizing();
$list = $DAOSeizing->getNoPrizeList();
foreach ($list as $item) {
    $packageInfo = Package::getPackageInfoByPackageid($item['packageid']);
    if ($packageInfo['deposit_price'] == $item['cnt']) {
        try {
            $win_number = seizingWin($item['packageid'], $packageInfo);
            echo "success  packageid=" . $item['packageid'] . " cnt=" . $item['cnt'] ."  win_number=".$win_number;echo "\n";
        } catch (Exception $e) {
            echo "error  packageid=" . $item['packageid'] . " cnt=" . $item['cnt'] . " code=" . $e->getCode() . "  msg=" . $e->getMessage();echo "\n";
        }
    }
}

echo "脚本执行结束:" . date("Y-m-d H:i:s");
echo "\n\n\n\n\n\n\n\n\n\n\n";

function seizingWin($packageId,$packageInfo){
    //  删除redis计数器
    $cache = Cache::getInstance("REDIS_CONF_CACHE");
    $key   = "seizing_" . $packageId;
    $cache->delete($key);
    $DAOSeizing = new DAOSeizing();
    
    // 获取中奖号码
    $china_tiyu_lottery_plw_key = "china_tiyu_lottery_plw_key";
    $ticket = $cache->HGET($china_tiyu_lottery_plw_key,'number');//近期彩票中奖号码
    $win_number = Seizing::getWinningNumber($packageId, $packageInfo['deposit_price'], $ticket);
    
    // 修改seizing
    $DAOSeizing->updateSeizingWinNumberByPackageid($packageId, $win_number);
    
    // 获取中奖信息
    $seizingWinInfo = $DAOSeizing->getSeizingWinInfo($packageId, $win_number);
    $uid = $seizingWinInfo['uid'];
    
    // 下单处理
    $orderid = Order::getOrderId();
    $express_price = Package::PACKET_EXPRESS_FEE;
    $service_price = Package::PACKET_SERVICES_FEE;
    $price         = $express_price + $service_price;
    $account       = Account::getAccountList($uid);
    
    $grape      = 0;
    $pay_price  = $price;
    $pay_coupon = 0;
    
    
    $contact = array(
        "contact_name"     => '',
        "contact_zipcode"  => '',
        "contact_province" => '',
        "contact_city"     => '',
        "contact_county"   => '',
        "contact_address"  => '',
        "contact_national" => '',
        "contact_phone"    => ''
    );
    
    // 购买处理
    list ($buyid, $buylogid) = Buying::addBuying($uid, $packageId, $packageInfo['sn'], $packageInfo['num'], $grape, $pay_price, $pay_coupon, $orderid);
    
    // 订单处理
    $DAOOrders = new DAOOrders();
    try {
        $DAOOrders->startTrans();
        
        // 创建订单
        Order::addSeizingOrder($orderid, $uid, $packageId, $service_price, $express_price, DAOOrders::ORDER_STATUS_INITIAL, DAOOrders::ORDER_PAY_STATUS_DEFRAY, DAOOrders::ORDER_PAY_TYPE_MIXED, $pay_price, $pay_coupon, $contact);
        
        $DAOOrders->commit();
    } catch (Exception $e) {
        $DAOOrders->rollback();
        Logger::log("seizing", "order_payment", array("uid" => $uid,"packageid" => $packageId,"code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
        throw new BizException($e->getMessage());
    }
    
    // 添加package的orderid
    Package::updatePackageOrderid($packageId, $orderid);
    
    // 购买状态确认
    list ($buyid, $buylogid) = Buying::updateBuyingFinish($buyid, Buying::BUYING_TYPE_RECEIVED, Buying::BUYING_TYPE_RECEIVED_ST_PAY);
    
    $Message = new Message($uid);
    $Message->sendMessage(DAOMessage::TYPE_SEIZING_SUCCESS,array($packageInfo['description']));
    
    return $win_number;
}