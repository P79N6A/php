<?php
class VipController extends BaseController
{
	public function createGroupAction()
	{
		$orderid    = $this->getParam("orderid")    ? trim($this->getParam("orderid")) : 0;
		$userid = Context::get("userid");

		Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");
		Interceptor::ensureNotEmpty($orderid, ERROR_PARAM_IS_EMPTY, 'orderid');
		$lotteryInfo = TravelLottery::getMyLotteryInfo($userid);
		if (empty($lotteryInfo)) {
			$groupid  	= TravelLottery::addMemberActivity($userid, $orderid);
		} else {
			$travel_id 	= $lotteryInfo['id'];
			$groupInfo 	= TravelGroup::getGroupInfoByTravelId($travel_id);
			$groupid 	=  $groupInfo['id'];
		}

		$this->render(array('groupid' => $groupid));
	}

	public function getGroupIdAction()
	{
		$userid = Context::get("userid");

		Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");
		$lotteryInfo = TravelLottery::getMyLotteryInfo($userid);

		Interceptor::ensureNotEmpty($lotteryInfo, ERROR_BIZ_TRAVEL_GROUP_NOT_EXIST);
		$travel_id 	= $lotteryInfo['id'];
		$groupInfo 	= TravelGroup::getGroupInfoByTravelId($travel_id);
		Interceptor::ensureNotEmpty($groupInfo, ERROR_BIZ_TRAVEL_GROUP_NOT_EXIST);
		$groupid 	=  $groupInfo['id'];


		$this->render(array('groupid' => $groupid));
	}

	public function groupDetailAction()
	{/*{{{邀请进度详情*/
		$userid = Context::get("userid");
		Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");

		$lotteryInfo = TravelLottery::getMyLotteryInfoIncludeFinished($userid);
		Interceptor::ensureNotEmpty($lotteryInfo, ERROR_BIZ_TRAVEL_GROUP_NOT_EXIST);
		$travel_id 	= $lotteryInfo['id'];
		$groupInfo 	= TravelGroup::getGroupInfoByTravelId($travel_id);
		$groupid 	=  $groupInfo['id'];

		$groupInfo = TravelGroup::detail($groupid);
		Interceptor::ensureNotEmpty($groupInfo, ERROR_BIZ_TRAVEL_GROUP_NOT_EXIST);

		$lotteryInfo 	= TravelLottery::detail($groupInfo['travel_id']);
		$userinfo 		= User::getUserInfo($lotteryInfo['uid']);
		$data = [
			"uid"			=> $userinfo['uid'],
			"nickname"		=> $userinfo['nickname'],
			"num"			=> $groupInfo['num'],
			"finish_num"	=> $groupInfo['finish_num'],
			"members"		=> json_decode($groupInfo['members'], true)
		];

		$this->render($data);
	}/*}}}*/
}