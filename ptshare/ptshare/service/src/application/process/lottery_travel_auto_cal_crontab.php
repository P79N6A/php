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

$cache = Cache::getInstance("REDIS_CONF_CACHE");
$china_tiyu_lottery_plw_key = "china_tiyu_lottery_plw_key";
$last_plw = $cache->hgetall("china_tiyu_lottery_plw_key");

if (empty($last_plw)) {
	Logger::log("lottery_crontab_log", "no_plw_value", array());
}
$plw_number = $last_plw['number'];

$DAOTravelLottery 		= new DAOTravelLottery();
$DAOTravelGroup			= new DAOTravelGroup();
$DAOTravelLooteryLog	= new DAOTravelLotteryLog();
$DAOTravelMember		= new DAOTravelMember();

$all_list 				= $DAOTravelLottery->getFinishedList();

if (!empty($all_list)) {

	foreach ($all_list as $actvity) {

		if ($actvity['isshow'] == 0) {
			continue;
		}

		$travel_id 		 = $actvity['id'];
		$total			 = $actvity['total'];
		$last_ten_record = $DAOTravelGroup->getFinishedGroupList($travel_id);
		$array_sum = 0;

		$array_time = [];
		$array_origin_times = [];
		if (!empty($last_ten_record)) {
			$DAOTravelLooteryLog->add($travel_id, $plw_number, 'T', '--');
			foreach ($last_ten_record as $item) {
				$time_array = explode(' ', $item['finish_time']);
				$array_origin_times[] = $item['finish_time'];
				$hisx = str_replace(':', '', $time_array[1]);

				$hisx = str_replace('.', '', $hisx);

				$array_time[] = $hisx;

				$array_sum += $hisx;

				$DAOTravelLooteryLog->add($travel_id, $hisx, 'M', $item['finish_time']);
			}

			$array_time[] = $plw_number;
		}

		$array_sum += $plw_number;

		$array_sum_g = array_sum($array_time);

		//---------------------------计算结果中奖码--------------------
		if ($array_sum == $array_sum_g) {
			$yushu = $array_sum % $total;
			$code = $yushu + 100001;
		}
		//---------------------------计算结果中奖码--------------------

		Logger::log("lottery_crontab_log", "write", array("wincode" => $code,"sum1" => $array_sum, "sum2" => $array_sum_g));

		try {
			$DAOTravelGroup->startTrans();
			$remark = ['origin' => $array_origin_times, "times" => $array_time, "code" => $code, "sum" => $array_sum, "orderid" => $orderid];
			$DAOTravelLottery->updateCode($travel_id, $code, json_encode($remark));

			$groupinfo = $DAOTravelGroup->getGroupInfoBycode($code, $travel_id);

			$members = json_decode($groupinfo['members'], true);

			//给获奖用户发站内信消息
			foreach ($members as $member) {
				$model_message = new Message($member['uid']);
				$model_message->sendMessage(DAOMessage::TYPE_LOTTERY_TRAVEL_WINNER, array($actvity['name'], $actvity['award']));
			}


			$DAOTravelMember->updateWinner($groupinfo['id']);

			$DAOTravelMember->finished($travel_id);
			$DAOTravelGroup->commit();

		} catch (Exception $e) {
			$DAOTravelGroup->rollback();
			Logger::log("lottery_crontab_log", "error", array("code" => $e->getCode(),"msg" => $e->getMessage()));
		}

		TravelLottery::modifyDetailCache($travel_id, 'wincode', $code);

		Logger::log("lottery_crontab_log", "okdone", array("wincode" => $code,"sum1" => $$array_sum, "sum2" => $array_sum_g));
	}
} else {
	var_dump("没有数据");
	Logger::log("lottery_crontab_log", "nodata", array("datetime" => date("Y-m-d H:i:s")));
}