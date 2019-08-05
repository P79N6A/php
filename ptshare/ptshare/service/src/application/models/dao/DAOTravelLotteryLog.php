<?php
class DAOTravelLotteryLog extends DAOProxy
{
	
	public function __construct()
	{
		$this->setDBConf("MYSQL_CONF_MALL");
		$this->setTableName("travel_lottery_log");
	}
	
	public function add($travel_id, $hisx, $type, $finish_time = '')
	{
		$info = array(
				"travel_id"		=> $travel_id,
				"type"			=> $type,
				"hisx"			=> $hisx,
				"finish_time"	=> $finish_time,
				"addtime"		=> date("Y-m-d H:i:s")
		);
		return $this->insert($this->getTableName(), $info);
	}
}