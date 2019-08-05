<?php
set_time_limit(0);
ini_set('memory_limit', '2G');
ini_set("display_errors", "On");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

$ROOT_PATH = dirname(dirname(dirname(dirname(__FILE__))));
$LOAD_PATH = array(
    $ROOT_PATH."/src/www",
    $ROOT_PATH."/config",
    $ROOT_PATH."/src/application/controllers",
    $ROOT_PATH."/src/application/models",
    $ROOT_PATH."/src/application/models/libs",
    $ROOT_PATH."/src/application/models/dao",
    $ROOT_PATH."/src/application/models/libs/payment_client"
);
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $LOAD_PATH));

function __autoload($classname)
{
    $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    include_once "$classname.php";
}
require $ROOT_PATH . "/config/server_conf.php";


$DAOTravelLottery 		= new DAOTravelLottery();
$DAOTravelGroup			= new DAOTravelGroup();
$DAOTravelLooteryLog	= new DAOTravelLotteryLog();
$DAOTravelMember		= new DAOTravelMember();

$all_list 				= $DAOTravelLottery->getFinishedList();

if (!empty($all_list)) {

	foreach ($all_list as $actvity) {
		$travel_id = $actvity['id'];
		if ($actvity['isshow'] == 0) {
			$orderid = $actvity['remark'];
			$DAOPay = new DAOPay();
			$payInfo = $DAOPay->getPayInfo($orderid);
			$amount = ($payInfo['amount']/2);
			Refund::call($orderid, 'wx', $amount);
			Logger::log("lottery_crontab_log", "refund", array("orderid" => $orderid,"addtime" => date("Y-m-d H:i:s"), "travel_id" => $travel_id));
			$DAOTravelLottery->updateRefund($travel_id);
		}
	}
} else {
	var_dump("没有数据");
	Logger::log("lottery_crontab_log", "nodata", array("datetime" => date("Y-m-d H:i:s")));
}