<?php
class TravelMember
{
	//我参与的组队
	static public function myList($userid, $num, $offset, $status)
	{
		$arrTemp = array();

		$DAOTravelMember = new DAOTravelMember();
		$list = $DAOTravelMember->getList($userid, $num, $offset, $status);
        foreach ($list as $key => $val) {
        	$loattery_info = TravelLottery::detail($val['travel_id']);

        	$group_info    = TravelGroup::detail($val['groupid']);
        	$members = json_decode($group_info['members'], true);
        	$name_array = [];
        	foreach ($members as $member) {
        		if ($userid != $member['uid']) {
        			$name_array[] = $member['nickname'];
        		}
        	}

            $temp = array(
                'name' 	  		=> $loattery_info['name'],
                'cover'   		=> $loattery_info['list_cover'],
            	'total'   		=> $loattery_info['total'],
            	"finish_total"	=> $loattery_info['finish_total'],
            	"num"			=> $loattery_info['num'],
            	"finish_num"	=> $group_info['finish_num'],
                "groupid"       => $val['groupid'],
                "from"          => $val['from'],
                "travel_id"     => $loattery_info['id'],
            	"code"			=> $group_info['code'],
            	"addtime"		=> $group_info['finish_time'],
            	"wincode"		=> $loattery_info['wincode'],
            	"title"         => ($group_info['isfinish'] == DAOTravelGroup::IS_FINISH_YES) ?  implode("和", $name_array): "去邀请好友来组队分享吧",
            	"status"		=> $val['status'],
            	"group_status"	=> $group_info['isfinish'],
            	"lottery_status"=> $val['iswin'],
            );
            $arrTemp[] = $temp;
            $offset = $val['id'];
        }
        $total = $DAOTravelMember->getMemberTotal($userid, $status);
        $more = (bool) $DAOTravelMember->getMoreMemeberList($userid, $offset, $status);
        return array($arrTemp, $total, $offset, $more);
	}

	static public function exists($uid, $groupid)
	{
		$DAOTravelMember = new DAOTravelMember();

		return $DAOTravelMember->exists($uid, $groupid);
	}
}