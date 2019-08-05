<?php
class DAOTravelGroup extends DAOProxy
{
	const IS_FINISH_YES = 'Y';
	const IS_FINISH_NO  = 'N';
	public function __construct()
	{
		$this->setDBConf("MYSQL_CONF_MALL");
		$this->setTableName("travel_group");
	}

	public function add($uid, $travel_id, $num, $members, $finish_num = 1)
    {
    	$info = array(
    			"uid"		=> $uid,
    			"travel_id"	=> $travel_id,
    			"members"	=> json_encode($members),
    			"num"		=> $num,
    			"finish_num"=> $finish_num,
    			"addtime"	=> date("Y-m-d H:i:s")
    	);
    	return $this->insert($this->getTableName(), $info);
    }

    public function getGroupInfo($groupid)
    {
    	$sql = "select * from {$this->getTableName()} where id=? limit 1";
    	return $this->getRow($sql, $groupid);
    }

    public function getGroupInfoByTravelId($travel_id)
    {
    	$sql = "select * from {$this->getTableName()} where travel_id=? limit 1";
    	return $this->getRow($sql, $travel_id);
    }


    public function getGroupInfoBycode($code,$travel_id)
    {
    	$sql = "select * from {$this->getTableName()} where code=? and travel_id = ? limit 1";
    	return $this->getRow($sql, array($code, $travel_id));
    }

    public function updateNumByGroupid($groupid, $finish_num, $members)
    {
    	$info = array(
    			"finish_num"	=> $finish_num,
    			"members"		=> json_encode($members),
    			"modtime"		=> date("Y-m-d H:i:s")
    	);
    	return $this->update($this->getTableName(), $info, " id=? ", array($groupid));
    }

    public function finished($groupid, $code, $finish_time, $members)
    {
    	$info = array(
    			"members"		=> json_encode($members),
    			"finish_time"	=> $finish_time,
    			"code"			=> $code,
    			"isfinish"		=> self::IS_FINISH_YES,
    			"modtime"		=> date("Y-m-d H:i:s")
    	);
    	return $this->update($this->getTableName(), $info, " id=? ", array($groupid));
    }

	public function getListByGroupIds($groupids)
	{

		$sql = " SELECT * FROM " . $this->getTableName() . " WHERE id in (?) ";

		return $this->getAll($sql, array(implode(',', $groupids)));
	}


	public function getList($travel_id, $num, $offset, $status)
	{
		$where = " WHERE 1=1 ";
		if ($offset > 0) {
			$where .= " and id < " . $offset . " ";
		}
		$where .= " and travel_id=? and isfinish=? ";

		$sql = " SELECT * FROM " . $this->getTableName();
		$sql .= $where;
		$sql .= " ORDER BY code ASC LIMIT " . $num;
		return $this->getAll($sql, array($travel_id, self::IS_FINISH_YES));
	}

	public function getGroupTotal($travel_id)
	{
		$where = " and isfinish=?  ";
		$sql   = " SELECT count(*) as cnt FROM " . $this->getTableName() . " WHERE travel_id=?  ";
		$sql  .= $where;
		return $this->getOne($sql, array('travel_id' => $travel_id, 'isfinish'=>self::IS_FINISH_YES));
	}

	public function getMoreGroupList($travel_id, $offset)
	{
		$where = " and isfinish=?  ";
		if ($offset > 0) {
			$where .= " and id<" . $offset . " ";
		}
		$sql  = " SELECT * FROM " . $this->getTableName() . " WHERE travel_id=? ";
		$sql .= $where;
		return $this->getOne($sql, array('travel_id' => $travel_id, 'isfinish'=>self::IS_FINISH_YES));
	}

	public function getFinishedGroupList($travel_id)
	{

		$sql = " SELECT * FROM " . $this->getTableName() . " WHERE travel_id = ? and isfinish = ? order by code desc limit 10";

		return $this->getAll($sql, array($travel_id, 'Y'));
	}
}