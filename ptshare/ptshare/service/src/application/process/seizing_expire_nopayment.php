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

$startime = date('Y-m-d H:i:s');
$endtime  = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . " +15 day"));

echo "脚本执行开始:" . date("Y-m-d H:i:s") . "\n";

$startime = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s')) - 86400);
$endtime  = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s')) - 86100);;

$package   = new Package();
$DAOOrders = new DAOOrders();
$list = $DAOOrders->getSeizingOrderListByNonPayment($startime, $endtime);
foreach($list as $item){
    $packageInfo = $package->getPackageInfoByPackageid($item['relateid']);
    $contact = $packageInfo['contact'];
    $contact = array(
        "contact_name"     => $contact["contact_name"],
        "contact_zipcode"  => $contact["contact_zipcode"],
        "contact_province" => $contact["contact_province"],
        "contact_city"     => $contact["contact_city"],
        "contact_county"   => $contact["contact_county"],
        "contact_address"  => $contact["contact_address"],
        "contact_national" => $contact["contact_national"],
        "contact_phone"    => $contact["contact_phone"]
    );
    
    
    $packageId  = $package->addByPackageId($item['relateid'], $item['uid'], $contact, $packageInfo['location']);
    $buyingInfo = Buying::getBuyingInfoByOrderid($item['orderid']);
    list ($buyid, $buylogid) = Buying::updateBuyingStatus($buyingInfo['buyid'], Buying::BUYING_TYPE_FINISH, Buying::BUYING_TYPE_FINISH_ST_CANCEL, "过期未支付取消订单");
    //设置订单状态
    Order::cancel($item['orderid']);
    
    echo "  orderid=" . $item['orderid']."  newPackageId=".$packageId;echo "\n";
}

echo "脚本执行结束:" . date("Y-m-d H:i:s");
echo "\n\n\n\n\n\n\n\n\n\n\n";





