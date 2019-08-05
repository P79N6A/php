<?php
class DAOTravelMember extends DAOProxy
{
	const MEMBER_TYPE_INVITER = 'INVITER';//队员类型邀请者
	const MEMBER_TYPE_INVITEE = 'INVITEE';//队员类型受邀者
	
	const MEMBER_FROM_SHARE 	= "share";
	const MEMBER_FROM_BUYING	= "buy";
	const MEMBER_FROM_RENT		= "rent";
	
	public function __construct()
	{
		$this->setDBConf("MYSQL_CONF_MALL");
		$this->setTableName("travel_member");
	}

	public function add($uid, $travel_id, $relateid, $groupid, $type, $microtime, $from)
    {
    	$show = 'Y';
    	if (in_array($from, [Pay::PAY_TYPE_VIP])) {
			$show = 'N';
		}
    	$info = array(
    			"uid"		=> $uid,
    			"relateid"	=> $relateid,
    			"travel_id"	=> $travel_id,
    			"groupid"	=> $groupid,
    			"type"		=> $type,
    			"addtime"	=> date("Y-m-d H:i:s"),
    			"micro_sec" => $microtime,
    			"from"		=> $from,
    			"isshow"	=> $show
    	);
    	return $this->insert($this->getTableName(), $info);
    }

    //修改活动结束
    public function finished($travel_id)
    {
    	$info = array(
    			"status"=> "OFF",
    			"modtime"	=> date("Y-m-d H:i:s")
    	);
    	return $this->update($this->getTableName(), $info, " travel_id=? ", array($travel_id));
    }

	public function getList($uid, $num, $offset, $status)
	{
		$where = " and status=?  and `isshow`=? ";
		if ($offset > 0) {
			$where .= " and id<" . $offset . " ";
		}
		$sql = " SELECT * FROM " . $this->getTableName() . " WHERE uid=? ";
		$sql .= $where;
		if ($status == 'ON') {
			$sql .= " ORDER BY id DESC LIMIT " . $num;
		} else {
			$sql .= " ORDER BY modtime DESC LIMIT " . $num;
		}
		
		return $this->getAll($sql, array('uid' => $uid, 'status'=>$status, "isshow" => "Y"));
	}

	public function getMemberTotal($uid, $status)
	{
		$where = " and status=?  and `isshow`=? ";
		$sql   = " SELECT count(*) as cnt FROM " . $this->getTableName() . " WHERE uid=?  ";
		$sql  .= $where;
		return $this->getOne($sql, array('uid' => $uid, 'status'=>$status, "isshow" => "Y"));
	}

	public function getMoreMemberList($uid, $offset, $status)
	{
		$where = " and status=?  ";
		if ($offset > 0) {
			$where .= " and id<" . $offset . " ";
		}
		$sql  = " SELECT * FROM " . $this->getTableName() . " WHERE uid=? ";
		$sql .= $where;
		return $this->getOne($sql, array('uid' => $uid, 'status'=>$status));
	}
	
	public function updateWinner($groupid)
	{
		$info = array(
				"iswin"=> "Y",
				"modtime"	=> date("Y-m-d H:i:s")
		);
		return $this->update($this->getTableName(), $info, " groupid=? ", array($groupid));
	}
	
	public function exists($uid, $groupid)
	{
		$sql   = " SELECT count(*) as cnt FROM " . $this->getTableName() . " WHERE groupid = ? and uid=?  ";
		return $this->getOne($sql, array($groupid, $uid)) > 0;
	}
}