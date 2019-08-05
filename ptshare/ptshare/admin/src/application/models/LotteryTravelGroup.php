<?php

class LotteryTravelGroup extends Table
{
	public function __construct()
	{
		$this->setDBConf("MYSQL_CONF_MALL");
		$this->setTableName("travel_group");
		$this->setPrimary("id");
	}

	public function getList($start, $limit, $code, $uid, $id, $isfinish)
	{
		$condition_ar[] = " travel_id = ? ";
		$params[] = $id;

		if (!empty($code)) {
			$condition_ar[] = " code like '%{$code}%' ";
		}

		if (!empty($uid)) {
			$condition_ar[] = " uid = ? ";
			$params[] = $uid;
		}
		
		if (!empty($isfinish)) {
			$condition_ar[] = " isfinish = ? ";
			$params[] = $isfinish;
		}

		$condition = implode(" and ", $condition_ar);

		$sql = "select * from ".$this->getTableName()." where {$condition} order by id desc ";

		$sql.= $limit > 0 ? " limit $start, $limit" : "";
		$data = $this->getAll($sql, $params);

		$sql_count = "select count(*) from ".$this->getTableName()." where {$condition} order by id desc ";
		$total = $this->getOne($sql_count, $params);

		return array($data, $total);
	}
}