<?php
class Vip
{
	//充值成功
	static public function complete($payinfo)
	{
		$userid = $payinfo['uid'];
		$user_medal = new UserMedal();

		$user_medal->addVip($userid);

		User::reload($userid);
		$lotteryInfo = TravelLottery::getMyLotteryInfo($userid);
		if (empty($lotteryInfo)) {
			TravelLottery::addMemberActivity($userid, $payinfo['orderid']);
		}
		$DAOInvite = new DAOInvite();
		$info = $DAOInvite->getUserInviter($userid);
		$groupid = $info['extend'];
		if (!empty($info) && !empty($groupid)) {
			Logger::log("travel_lottery_log", "get", array("groupid" => $groupid));

			$groupinfo = TravelGroup::detail($groupid);
			if (!empty($groupinfo['travel_id'])) {
				$travel_info = TravelLottery::detail($groupinfo['travel_id']);
				if ($travel_info['uid'] != $payinfo['uid']) {
					$endtime = strtotime($travel_info['endtime']);
					$now = time();
					if (!empty($groupid) && $now < $endtime) {
						Logger::log("travel_lottery_log", "get", array("id" => $payinfo['id']));

						TravelLottery::join($userid, $groupid, $payinfo['id'], Pay::PAY_TYPE_VIP, false);
					}
				}
			}
		}

		return true;
	}
}