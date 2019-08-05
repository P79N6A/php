<?php
class DAOTravelLottery extends DAOProxy
{
	const IS_FINISH_YES = 'Y';
	const IS_FINISH_NO  = 'N';

	public function __construct()
	{
		$this->setDBConf("MYSQL_CONF_MALL");
		$this->setTableName("travel_lottery");
	}
	
	public function add($uid, $num, $total, $remark)
	{
		$now = time();
		$end_time = $now + 3600*24*365;
		$info = array(
				"uid"			=> $uid,
				"num"			=> $num,
				"name"			=> "邀请用户加入会员",
				"award"			=> "邀请",
				"finish_total"	=> 0,
				"remark"		=> $remark,
				"endtime"		=> date("Y-m-d H:i:s", $end_time),
				"total"			=> $total,
				"addtime"		=> date("Y-m-d H:i:s", $now)
		);
		return $this->insert($this->getTableName(), $info);
	}
	
	public function getLotteryInfo($id)
	{
		$sql = "select * from {$this->getTableName()} where id=? limit 1";
		return $this->getRow($sql, $id);
	}
	
	public function getLotteryByuid($uid)
	{
		$sql = "select * from {$this->getTableName()} where uid=? and status = ? and isfinish = ? and endtime > ? order by id desc  limit 1";
		return $this->getRow($sql, array($uid, 'N', 'N', date("Y-m-d H:i:s")));
	}
	
	public function getLotteryByuidIncludeFinished($uid)
	{
		$sql = "select * from {$this->getTableName()} where uid=? order by id desc  limit 1";
		return $this->getRow($sql, array($uid));
	}
	
	public function getListByIds($ids)
	{

		$sql = " SELECT * FROM " . $this->getTableName() . " WHERE id in (?) ";

		return $this->getAll($sql, array(implode(',', $ids)));
	}

	public function updateNumByid($id, $finish_total)
    {
    	$info = array(
    			"finish_total"	=> $finish_total,
    			"modtime"		=> date("Y-m-d H:i:s")
    	);

    	return $this->update($this->getTableName(), $info, " id=? ", array($id));
    }

    public function finished($travel_id)
    {
    	$info = array(
    			"isfinish"	=> self::IS_FINISH_YES,
    			"modtime"	=> date("Y-m-d H:i:s")
    	);
    	return $this->update($this->getTableName(), $info, " id=? ", array($travel_id));
    }
    
    public function updateRefund($travel_id)
    {
    	$info = array(
    			"status"	=> 'Y',
    			"refund_time"	=> date("Y-m-d H:i:s")
    	);
    	return $this->update($this->getTableName(), $info, " id=? ", array($travel_id));
    }
    public function updateCode($travel_id, $wincode, $remark)
    {
    	$info = array(
    			"wincode"	=> $wincode,
    			"remark"	=> $remark,
    			"status"	=> "Y",//设置已开奖
    			"modtime"	=> date("Y-m-d H:i:s")
    	);
    	return $this->update($this->getTableName(), $info, " id=? ", array($travel_id));
    }
    
    public function getFinishedList()
    {
    	
    	$sql = " SELECT * FROM " . $this->getTableName() . " WHERE isfinish = ? and wincode = ? and status = ?";
    	
    	return $this->getAll($sql, array('Y', 0, 'N'));
    }
    
    
    public function getActiveList()
    {
    	$sql = " SELECT * FROM " . $this->getTableName() . " WHERE isfinish = ? and wincode = ? and status = ?";
    	
    	return $this->getAll($sql, array('N', 0, 'N'));
    }
}