<?php
class TravelLottery
{
	const TRAVEL_LOTTERY_FINISHED_KEY 		= "lottery_finished_";//活动队伍总数计数器
	const TRAVEL_GROUP_FINISHED_KEY 		= "lottery_group_finished_";//组队成员计数器
	const TRAVEL_LOTTERY_HMSET_KEY_PREFIX 	= "hmset_travel_lottery_info_";//每个活动redis键值前缀
	const TRAVEL_LOTTERY_ONLY_ONE_TIME		= "lottery_group_add_one_times_";
	//组队抽奖码生成
	static public function buildCode($travel_id)
	{
		$key   = "travel_lotter_code_" . $travel_id;
		$cache = Cache::getInstance("REDIS_CONF_CACHE");
		if ($cache->exists($key)) {
			return $cache->incrBy($key, 1);
		} else {
			$cache->set($key, 100001);
			return $cache->get($key);
		}
	}

	//创建组队
	static public function add($userid, $relateid, $travel_id, $from)
	{
		$cache 		= Cache::getInstance("REDIS_CONF_CACHE");

		$groupid = $cache->get(TRAVEL_LOTTERY_ONLY_ONE_TIME.$userid.$relateid.$travel_id.$from);
		if (!empty($groupid)) {
			return array("groupid" => $groupid);
		}
		//检查活动是否结束，活动人数是否已满
		$lottery_travel_info 	= self::detail($travel_id);
		$max_total 				= $lottery_travel_info['total'];
		Interceptor::ensureNotFalse(($lottery_travel_info['isfinish'] == 'N' && $cache->get(self::TRAVEL_LOTTERY_FINISHED_KEY . $travel_id) < $max_total), ERROR_BIZ_TRAVEL_LOTTERY_FINISHED);
		$DAOTravelGroup 	= new DAOTravelGroup();
		$DAOTravelMember 	= new DAOTravelMember();
		$userinfo = User::getUserInfo($userid);
		$items = [
			"uid" 		=> $userid,
			"nickname" 	=> $userinfo['nickname'],
			"avatar" 	=> $userinfo['avatar'],
		];
		$members = [];
		$members[] = $items;
		try {
			$DAOTravelGroup->startTrans();
			//插入group
			$groupid = $DAOTravelGroup->add($userid, $travel_id, $lottery_travel_info['num'], $members, 1);
			//插入members
			if (!empty($groupid)) {
				list($usec, $seconds) = explode(" ", microtime());

				$microtime = sprintf("%03d", round($usec*1000));
				Logger::log("travel_lottery_log", "time", array("usec" => $usec, "relateid" => $relateid, "from" => $from,"seconds" => $seconds, "micro" => $microtime));
				$DAOTravelMember->add($userid, $travel_id, $relateid, $groupid, DAOTravelMember::MEMBER_TYPE_INVITER , $microtime, $from);
			} else {
				Logger::log("travel_lottery_log", "add group error", array("groupid" => $groupid));
			}

			$DAOTravelGroup->commit();
		} catch (Exception $e) {
			$DAOTravelGroup->rollback();
			Logger::log("travel_lottery_log", "add_group_members", array("code" => $e->getCode(),"msg" => $e->getMessage()));
			throw new BizException(ERROR_BIZ_TRAVEL_GROUP_ADD_ERROR, $e->getMessage());
		}

		$cache->incr(self::TRAVEL_GROUP_FINISHED_KEY . $travel_id ."_". $groupid);

		TravelGroup::setInfoRedis($groupid);

		$cache->set(TRAVEL_LOTTERY_ONLY_ONE_TIME.$userid.$relateid.$travel_id.$from, $groupid);

		return array("groupid" => $groupid);

	}
	//参加组队
	static public function join($userid, $groupid, $relateid, $from, $send_message)
	{
		$cache 		= Cache::getInstance("REDIS_CONF_CACHE");
		//检查活动是否结束，活动人数是否已满
		$lottery_travel_group_info = TravelGroup::detail($groupid);
		$lottery_travel_info = self::detail($lottery_travel_group_info['travel_id']);
		$max_num 		= (int) $lottery_travel_info['num'];//每团几个人
		$max_total 		= (int) $lottery_travel_info['total'];//总共几个团
		$travel_id 		= $lottery_travel_group_info['travel_id'];//活动id
		//是否参加过该组队
		Interceptor::ensureFalse(TravelMember::exists($userid, $groupid), ERROR_BIZ_TRAVEL_GROUP_MEMBER_EXIST);
		//组队活动是否已结束
		Interceptor::ensureNotFalse(($lottery_travel_info['isfinish'] == 'N'), ERROR_BIZ_TRAVEL_LOTTERY_FINISHED);
		//组队是否结束
		Interceptor::ensureNotFalse(($lottery_travel_group_info['isfinish'] == 'N' && ($finish_num = $cache->incr(self::TRAVEL_GROUP_FINISHED_KEY . $travel_id ."_". $groupid)) <= $max_num), ERROR_BIZ_TRAVEL_GROUP_FINISHED);
		if ($finish_num == $max_num) {
			//是否达到活动组队总数
			Interceptor::ensureNotFalse((($finish_total = $cache->incr(self::TRAVEL_LOTTERY_FINISHED_KEY . $travel_id)) <= $max_total), ERROR_BIZ_TRAVEL_LOTTERY_FINISHED);
		}

		$members = json_decode($lottery_travel_group_info['members'], true);

		$userinfo = User::getUserInfo($userid);
		$inviter_userinfo = User::getUserInfo($lottery_travel_group_info['uid']);

		$items = [
				"uid" 		=> $userid,
				"nickname" 	=> $userinfo['nickname'],
				"avatar" 	=> $userinfo['avatar'],
		];
		$members[] = $items;
		$DAOTravelGroup = new DAOTravelGroup();
		$DAOTravelMember = new DAOTravelMember();
		$DAOTravelLottery = new DAOTravelLottery();

		try {
			$DAOTravelGroup->startTrans();
			//插入members
			list($usec, $seconds) = explode(" ", microtime());
			$microtime = sprintf("%03d", round($usec*1000));
			$DAOTravelMember->add($userid, $travel_id, $relateid, $groupid, DAOTravelMember::MEMBER_TYPE_INVITEE, $microtime, $from);
			//更新group,如果组队成功更新活动表加1
			$DAOTravelGroup->updateNumByGroupid($groupid, $finish_num, $members);
			if ($finish_num == $max_num) {
				$code = self::buildCode($travel_id);
				$finish_time = date("Y-m-d H:i:s", $seconds) . "." . $microtime;
				$DAOTravelGroup->finished($groupid, $code, $finish_time, $members);
				$DAOTravelLottery->updateNumByid($travel_id, $finish_total);

				if ($finish_total == $max_total) {
					self::modifyDetailCache($travel_id, 'isfinish', 'Y');
					$DAOTravelLottery->finished($travel_id);
				}

				if ($send_message == true) {
					//给参与组团用户发站内信消息(组团成功)
					$model_message = new Message($lottery_travel_group_info['uid']);
					$model_message->sendMessage(DAOMessage::TYPE_LOTTERY_GROUP_SUCCESSR, array($userinfo['nickname'], $lottery_travel_info['name'], $code));
					foreach ($members as $member) {
						if ($member['uid'] != $lottery_travel_group_info['uid']) {
							$model_message = new Message($member['uid']);
							$model_message->sendMessage(DAOMessage::TYPE_LOTTERY_GROUP_SUCCESSE, array($inviter_userinfo['nickname'], $lottery_travel_info['name'], $code));
						}
					}
				}
			} else {
				if ($send_message == true) {
					//给参与组团用户发站内信消息(再接再厉)
					$model_message = new Message($lottery_travel_group_info['uid']);
					$model_message->sendMessage(DAOMessage::TYPE_LOTTERY_TRAVEL_INVITER, array($userinfo['nickname'], $lottery_travel_info['name'], $max_num-$finish_num));

					$model_message = new Message($userid);
					$model_message->sendMessage(DAOMessage::TYPE_LOTTERY_TRAVEL_INVITEE, array($inviter_userinfo['nickname'], $lottery_travel_info['name'], $max_num-$finish_num));
				}
			}

			$DAOTravelGroup->commit();
		} catch (Exception $e) {
			$DAOTravelGroup->rollback();
			$cache->decr(self::TRAVEL_GROUP_FINISHED_KEY . $travel_id ."_". $groupid);
			if ($finish_num == $max_num) {
				$cache->decr(self::TRAVEL_LOTTERY_FINISHED_KEY . $travel_id);
			}
			Logger::log("travel_lottery_log", "join group", array("code" => $e->getCode(),"msg" => $e->getMessage(), "finish_num" => $finish_num, "finish_total" => $finish_total));
			throw new BizException(ERROR_BIZ_TRAVEL_GROUP_JOIN_ERROR, $e->getMessage());
		}

		if (!empty($finish_total)) {
			self::modifyDetailCache($travel_id, 'finish_total', $finish_total);
		}
		Logger::log("travel_lottery_log", "time", array("finish_total" => $finish_total, "code" => $code, "from" => $from,"groupid" => $groupid, "from" => $from));

		TravelGroup::modifyDetailCache($groupid, "members", json_encode($members));
		TravelGroup::modifyDetailCache($groupid, "finish_num", $finish_num);
		if (!empty($code)) {
			TravelGroup::modifyDetailCache($groupid, "code", $code);
			TravelGroup::modifyDetailCache($groupid, "isfinish", DAOTravelGroup::IS_FINISH_YES);
			TravelGroup::modifyDetailCache($groupid, "finish_time", $finish_time);
		}

		return true;
	}

	static public function modifyDetailCache($travel_id, $hsetkey, $new_value)
	{
		$cache 		= Cache::getInstance("REDIS_CONF_CACHE");

		return $cache->hSet(self::TRAVEL_LOTTERY_HMSET_KEY_PREFIX. $travel_id, $hsetkey, $new_value);
	}

	//详情
	static public function detail($travel_id)
	{
		$cache 		= Cache::getInstance("REDIS_CONF_CACHE");
		$lotteryInfo 	= $cache->hgetall(self::TRAVEL_LOTTERY_HMSET_KEY_PREFIX. $travel_id);
		if (empty($lotteryInfo)) {
			$dao_lottery = new DAOTravelLottery();
			$lotteryInfo = $dao_lottery->getLotteryInfo($travel_id);
			$cache->hmset(self::TRAVEL_LOTTERY_HMSET_KEY_PREFIX . $travel_id, $lotteryInfo);
			$cache->expire(self::TRAVEL_LOTTERY_HMSET_KEY_PREFIX . $travel_id, 86400);
		}

		return $lotteryInfo;
	}
	//================================以下是会员活动的方法============================start
	static public function getMyLotteryInfo($userid)
	{
		$DAOTravelLottery = new DAOTravelLottery();

		return $DAOTravelLottery->getLotteryByuid($userid);
	}
	
	static public function getMyLotteryInfoIncludeFinished($userid)
	{
		$DAOTravelLottery = new DAOTravelLottery();
		
		return $DAOTravelLottery->getLotteryByuidIncludeFinished($userid);
	}
	//创建一个邀请用户加入会员活动
	static public function addMemberActivity($userid, $orderid)
	{
		$DAOTravelLottery = new DAOTravelLottery();
		$DAOTravelGroup 	= new DAOTravelGroup();
		$num	= 3;

		try {
			$DAOTravelGroup->startTrans();
			$travel_id = $DAOTravelLottery->add($userid, $num, 1, $orderid);

			$groupid = $DAOTravelGroup->add($userid, $travel_id, $num, [], 0);

			$DAOTravelGroup->commit();
		} catch (Exception $e) {
			$DAOTravelGroup->rollback();
			Logger::log("travel_lottery_log", "add_group_members", array("code" => $e->getCode(),"msg" => $e->getMessage()));
			throw new BizException(ERROR_BIZ_TRAVEL_GROUP_ADD_ERROR, $e->getMessage());
		}

		return $groupid;
	}
	//================================以上是会员活动的方法============================end
}