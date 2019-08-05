<?php

class LotteryTravelLog extends Table
{
	public function __construct()
	{
		$this->setDBConf("MYSQL_CONF_MALL");
		$this->setTableName("travel_lottery_log");
		$this->setPrimary("id");
	}


	public function getListByTravelid($id)
	{
		$sql = "select * from ".$this->getTableName()." where travel_id = ?  order by id desc ";
        $data = $this->getAll($sql, array($id));
        return $data;
	}
}