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

$startime = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s')) - 18000);
$endtime  = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s')) - 800);;

echo "脚本执行开始:" . date("Y-m-d H:i:s") . "\n";

$DAOOrders  = new DAOOrders();
$DAORenting = new DAORenting();
$list = $DAOOrders->getOrderListByNonPayment($startime, $endtime);
foreach ($list as $item) {
    try {
        if ($item['type'] == DAOOrders::ORDER_TYPE_RENT) {
            $rentingInfo = $DAORenting->getRentingInfoByOrderid($item['orderid']);
            $userRentingInfo = UserRenting::getUserRentingInfo($rentingInfo['relateid']);
            if ($rentingInfo['type'] == UserRenting::USER_RENTING_TYPE_RECEIVED && $rentingInfo['status'] == UserRenting::USER_RENTING_TYPE_RECEIVED_ST_PAY) {
                $result = Rent::cancel($rentingInfo['rentid'], $userRentingInfo, $rentingInfo);
            }
        }
        if ($item['type'] == DAOOrders::ORDER_TYPE_RELET) {
            $rentingInfo     = $DAORenting->getRentingInfoByOrderid($item['orderid']);
            $userRentingInfo = UserRenting::getUserRentingInfo($rentingInfo['relateid']);
            $result = Relet::cancel($rentingInfo['rentid'], $userRentingInfo, $rentingInfo);
        }
        if ($item['type'] == DAOOrders::ORDER_TYPE_BUYING) {
            Buying::cancel($item['orderid']);
        }
        echo "success  orderid=" . $item['orderid'];echo "\n";
    } catch (Exception $e) {
        echo "fail    orderid=" . $item['orderid']. "  msg=". $e->getMessage();echo "\n";
    }
}

echo "脚本执行结束:" . date("Y-m-d H:i:s");
echo "\n\n\n\n\n\n\n\n\n\n\n";

