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

$package   = new Package();
$DAOOrders = new DAOOrders();
$DAOUserRenting = new DAOUserRenting();
$list = $DAOUserRenting->getUserRentingExpire($startime, $endtime);
foreach ($list as $item) {
    // 生成新的package
    $orderInfo = $DAOOrders->getRentOrderInfoByRelateid($item['packageid']);
    $contact = array(
        "contact_name"     => $orderInfo["contact_name"],
        "contact_zipcode"  => $orderInfo["contact_zipcode"],
        "contact_province" => $orderInfo["contact_province"],
        "contact_city"     => $orderInfo["contact_city"],
        "contact_county"   => $orderInfo["contact_county"],
        "contact_address"  => $orderInfo["contact_address"],
        "contact_national" => $orderInfo["contact_national"],
        "contact_phone"    => $orderInfo["contact_phone"]
    );
    try {
        $prvPackageInfo = Package::getPackageInfoByPackageid($item['packageid']);
        $prvLocation  = $prvPackageInfo['location'];
        $packageId    = $package->addByPackageId($item['packageid'], $orderInfo['uid'], $contact, $prvLocation, $item['endtime']);

        // 修改状态
        if($packageId){
            $result = userRentingExpire($item['id'], $item['rentid']);
        }
        echo "relateid=" . $item['id'] . "   rentid=" . $item['rentid'] . "  orderid=" . $item['orderid']."  newPackageId=".$packageId;echo "\n";
    } catch (Exception $e) {
        print_r($e);echo "\n";
    }
}

echo "脚本执行结束:" . date("Y-m-d H:i:s");
echo "\n\n\n\n\n\n\n\n\n\n\n";

function userRentingExpire($relateid, $rentid)
{
    $DAOUserRenting = new DAOUserRenting();
    try {
        $DAOUserRenting->startTrans();
        $DAOUserRenting->updateUserRentingStatus($relateid, UserRenting::USER_RENTING_TYPE_TRANSMIT, UserRenting::USER_RENTING_TYPE_TRANSMIT_ST_INIT);
        Renting::updateRentingStatus($rentid, UserRenting::USER_RENTING_TYPE_TRANSMIT, UserRenting::USER_RENTING_TYPE_TRANSMIT_ST_INIT, Renting::STATUS_FINISH);
        $DAOUserRenting->commit();
    } catch (Exception $e) {
        $DAOUserRenting->rollback();
        throw new BizException($e->getMessage());
    }
    return true;
}
