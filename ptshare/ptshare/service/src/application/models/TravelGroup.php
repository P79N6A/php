<?php
class TravelGroup
{
	const TRAVEL_GROUP_HMSET_KEY_PREFIX = "hmset_travel_group_info_";//每个组队redis键值前缀

	static public function setInfoRedis($groupid)
	{
		$cache 			= Cache::getInstance("REDIS_CONF_CACHE");
		$DAOTravelGroup = new DAOTravelGroup();
		$groupInfo 		= $DAOTravelGroup->getGroupInfo($groupid);
		try {
			$cache->hmset(self::TRAVEL_GROUP_HMSET_KEY_PREFIX . $groupid, $groupInfo);
			$cache->expire(self::TRAVEL_GROUP_HMSET_KEY_PREFIX . $groupid, 86400);
		} catch (Exception $e) {

		}

		return $groupInfo;
	}

	static public function modifyDetailCache($groupid, $hsetkey, $new_value)
	{
		$cache 		= Cache::getInstance("REDIS_CONF_CACHE");

		return $cache->hSet(self::TRAVEL_GROUP_HMSET_KEY_PREFIX . $groupid, $hsetkey, $new_value);
	}

	static public function detail($groupid)
	{
		$cache 		= Cache::getInstance("REDIS_CONF_CACHE");
		$groupInfo 	= $cache->hgetall(self::TRAVEL_GROUP_HMSET_KEY_PREFIX. $groupid);

		if (empty($groupInfo)) {
			return self::setInfoRedis($groupid);
		}

		return $groupInfo;
	}

	//组队成功的列表
	static public function getGroupList($travel_id, $num, $offset)
	{
		$arrTemp = array();

		$DAOTravelGroup = new DAOTravelGroup();
		$list = $DAOTravelGroup->getList($travel_id, $num, $offset);
        foreach ($list as $key => $val) {
        	$members = json_decode($val['members'], true);

        	$names = [];
        	foreach ($members as $member) {
        		$names[] = $member['nickname'];
        	}

            $temp = array(
                "code" 	  	=> $val['code'],
            	"members"   => $members,
            	"groupid"	=> $val['id'],
            	"addtime"	=> $val['finish_time'],
            	"remark"	=> implode(" + ", $names)
            );
            $arrTemp[] = $temp;
            $offset = $val['id'];
        }
        $total = $DAOTravelGroup->getGroupTotal($travel_id);
        $more = (bool) $DAOTravelGroup->getMoreGroupList($travel_id, $offset);
        return array($arrTemp, $total, $offset, $more);
	}

	static public function getGroupInfoByTravelId($travel_id)
	{
		$DAOTravelGroup = new DAOTravelGroup();

		return $DAOTravelGroup->getGroupInfoByTravelId($travel_id);
	}
}