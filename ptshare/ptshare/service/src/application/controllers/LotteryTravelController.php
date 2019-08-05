<?php
class LotteryTravelController extends BaseController
{
	public function detailAction()
	{/*{{{组队抽奖活动详情*/
		$groupid = $this->getParam("groupid") ? intval($this->getParam("groupid")) : 0;
		$userid  = Context::get("userid");

		//Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, "uid($userid)");
		Interceptor::ensureNotFalse($groupid > 0, ERROR_PARAM_INVALID_FORMAT, "groupid");

		$groupInfo = TravelGroup::detail($groupid);
		Interceptor::ensureNotEmpty($groupInfo, ERROR_BIZ_TRAVEL_GROUP_NOT_EXIST);

		$members = json_decode($groupInfo['members'], true);//组队成员

		$isjoined = 'N';
		foreach ($members as $member) {
			if (!empty($userid) && $userid == $member['uid']) {
				$isjoined = 'Y';
				break;
			}
		}

		$lotteryInfo = TravelLottery::detail($groupInfo['travel_id']);

		$data = [
			"name" 			=> $lotteryInfo['name'],
			"list_cover"	=> $lotteryInfo['list_cover'],
			"share_cover"	=> $lotteryInfo['share_cover'],
			"detail_cover"	=> $lotteryInfo['detail_cover'],
			"gate_cover"	=> $lotteryInfo['gate_cover'],
			"isfinish"		=> $groupInfo['isfinish'],
			"total"			=> $lotteryInfo['total'],
			"finish_total" 	=> $lotteryInfo['finish_total'],
			"num"			=> $groupInfo['num'],
			"finish_num"	=> $groupInfo['finish_num'],
			"isjoined"		=> $isjoined,
			"startime"      => $lotteryInfo['startime'],
			"endtime"		=> $lotteryInfo['endtime'],
			"addtime"		=> $groupInfo['addtime'],
			"award"			=> $lotteryInfo['award'],
			"members"		=> json_decode($groupInfo['members'], true)
		];

		$this->render($data);
	}/*}}}*/

	public function groupAction()
	{/*{{{抽奖组队列表*/
		$travel_id 	= $this->getParam("travel_id") 	? intval($this->getParam("travel_id")) 	: 0;
		$offset 	= $this->getParam("offset") 	? (int) ($this->getParam("offset")) 	: 0;
		$num    	= $this->getParam("num")    	? intval($this->getParam("num")) 		: 5;
		$userid 	= $this->getParam("uid") 		? intval($this->getParam("uid")) 		: 0;

		//Interceptor::ensureNotFalse($userid> 0, ERROR_PARAM_INVALID_FORMAT, "uid($userid)");
		Interceptor::ensureNotFalse($travel_id > 0, ERROR_PARAM_INVALID_FORMAT, "travel_id");
		Interceptor::ensureNotEmpty(TravelLottery::detail($travel_id), ERROR_BIZ_TRAVEL_LOTTERY_NOT_EXIST);

		list($list,$total,$offset,$more) = TravelGroup::getGroupList($travel_id, $num, $offset);

		$this->render(array('list' => $list, 'total' => $total, 'offset' => $offset, 'more' => $more));
	}/*}}}*/

	public function mylistAction()
	{/*{{{我的组队抽奖记录*/
		$status = $this->getParam("status") ? trim($this->getParam("status")) : 'ON';
		$offset = $this->getParam("offset") ? (int) ($this->getParam("offset")) : 0;
		$num    = $this->getParam("num")    ? intval($this->getParam("num")) 	: 5;
		$userid = Context::get("userid");
		Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, "uid($userid)");
		Interceptor::ensureNotEmpty($status, ERROR_PARAM_INVALID_FORMAT, "status");

		list($list,$total,$offset,$more) = TravelMember::myList($userid, $num, $offset, $status);

		$this->render(array('list' => $list, 'total' => $total, 'offset' => $offset, 'more' => $more));
	}/*}}}*/

	public function addAction()
	{/*{{{创建组队*/
		$relateid	= $this->getParam("relateid") 	? intval($this->getParam("relateid")) 	: 0;
		$from		= $this->getParam("from") 		? trim($this->getParam("from")) 		: '';
		$travel_id 	= $this->getParam("travel_id") 	? intval($this->getParam("travel_id")) 	: 0;
		$userid  	= Context::get("userid");

		Interceptor::ensureNotFalse($travel_id > 0, ERROR_PARAM_INVALID_FORMAT, "travel_id");
		Interceptor::ensureNotEmpty(TravelLottery::detail($travel_id), ERROR_BIZ_TRAVEL_LOTTERY_NOT_EXIST);
		Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, "uid($userid)");
		Interceptor::ensureNotFalse($relateid > 0, ERROR_PARAM_INVALID_FORMAT, "sellid");
		Interceptor::ensureNotEmpty($from, ERROR_PARAM_INVALID_FORMAT, "from");

		$this->render(TravelLottery::add($userid, $relateid, $travel_id, $from));
	}/*}}}*/

	public function joinAction()
	{/*{{{参加组队*/
		$relateid  	= $this->getParam("relateid") 	? intval($this->getParam("relateid")) 	: 0;
		$groupid 	= $this->getParam("groupid") 	? intval($this->getParam("groupid")) 	: 0;
		$from		= $this->getParam("from") 		? trim($this->getParam("from")) 		: '';
		$userid  	= Context::get("userid");
		//$userid 	= 21000203;
		$userid 	= $this->getParam("uid") 		? intval($this->getParam("uid")) 		: 0;
		Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, "uid($userid)");
		Interceptor::ensureNotFalse($relateid > 0, ERROR_PARAM_INVALID_FORMAT, "relateid");
		Interceptor::ensureNotFalse($groupid > 0, ERROR_PARAM_INVALID_FORMAT, "groupid");
		$groupInfo = TravelGroup::detail($groupid);
		Interceptor::ensureNotEmpty($groupInfo, ERROR_BIZ_TRAVEL_GROUP_NOT_EXIST);
		Interceptor::ensureNotEmpty($from, ERROR_PARAM_INVALID_FORMAT, "from");

		$this->render(TravelLottery::join($userid, $groupid, $relateid, $from, true));
	}/*}}}*/

	//后台修改
    public function adminUpdateAction()
    {
    	$id  = $this->getParam("id") ? intval($this->getParam("id")) : 0;

    	Interceptor::ensureNotFalse(is_numeric($id) && $id > 0, ERROR_PARAM_IS_EMPTY, "id");

    	try {
    		$cache 		= Cache::getInstance("REDIS_CONF_CACHE");
    		$dao_lottery = new DAOTravelLottery();
			$lotteryInfo = $dao_lottery->getLotteryInfo($id);
			$cache->hmset(TravelLottery::TRAVEL_LOTTERY_HMSET_KEY_PREFIX . $id, $lotteryInfo);
			$cache->expire(TravelLottery::TRAVEL_LOTTERY_HMSET_KEY_PREFIX . $id, 86400);
    	} catch (Exception $e) {
    		Logger::log("travel_lottery_log", "adminupdate", array("code" => $e->getCode(),"msg" => $e->getMessage()));
    		throw new BizException($e->getMessage());
    	}

    	$this->render();
    }

	public function getConfigAction()
	{
		$config = new Config();
		$config_list = $config->getConfigs("china", "rand_lottery_config", "xcx", '1');
		$value_list = $config_list['rand_lottery_config']['value'];
		$config_array = [];
		$value_list = json_decode($value_list, true);
		foreach ($value_list as $con) {
			if ($con['type'] == 'qxc') {
				$config_array[] = $con;
			}
		}

		$DAOtravelLottery = new DAOTravelLottery();
		$all_list = $DAOtravelLottery->getActiveList();
		$cache 		= Cache::getInstance("REDIS_CONF_CACHE");
		foreach ($all_list as $item) {
			$travel_lottery = TravelLottery::detail($item['id']);
			$starttime 	= strtotime($item['startime']);
			$endtme		= strtotime($item['endtime']);
			$now 		= time();
			if ($travel_lottery['isfinish'] == 'N' && $now >= $starttime && $now <= $endtme) {
				$ite = [];
				$ite['cover'] = $item['gate_cover'];
				$ite['type']  = 'teamlottery';
				$ite['id']	  = $item['id'];
				$ite['endtime'] = $item['endtime'];
				$config_array[] = $ite;
			}
		}

		$count = count($config_array);

		$rand = rand(0, $count-1);

		$data =  array('rand_lottery_config' => array("value" => $config_array[$rand], "expire"=>0, "rand" => $rand));

		$this->render($data);
	}
}