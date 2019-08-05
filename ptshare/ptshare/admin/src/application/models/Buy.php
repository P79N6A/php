<?php

class Buy extends Table
{
	public function __construct()
	{
		$this->setDBConf("MYSQL_CONF_MALL");
		$this->setTableName("buying");
		$this->setPrimary("buyid");
	}

	public function getList($start, $limit, $uid, $orderid, $status)
	{
		$condition_ar[] = " 1=? ";
		$params[] = 1;

		if (!empty($uid)) {
			$condition_ar[] = " uid = ? ";
			$params[] = $uid;
		}

		if (!empty($orderid)) {
			$condition_ar[] = " orderid = ? ";
			$params[] = $orderid;
		}

		if (!empty($status) && $status != '-1') {
			$condition_ar[] = " status = ? ";
			$params[] = $status;
		}

		$condition = implode(" and ", $condition_ar);

		$sql = "select * from ".$this->getTableName()." where {$condition} order by buyid desc ";

		$sql.= $limit > 0 ? " limit $start, $limit" : "";
		$data = $this->getAll($sql, $params);

		$sql_count = "select count(*) from ".$this->getTableName()." where {$condition} order by buyid desc ";
		$total = $this->getOne($sql_count, $params);

		return array($data, $total);
	}

	public static function getPayStatusData()
	{
		//'101待支付，102代发货，103已发货，401待评价，402已评价，403已取消，404已撤销'
		return ['101' => '待支付','102'=> '待发货','103' => "已发货", '401' => '待评价', '402' => "已评价", '403' => "已取消", '404' => "已撤销"];
	}

	public function getInfo($buyid)
    {
        $sql = "select * from ".$this->getTableName()." where buyid=?";

        return $this->getRow($sql, [$buyid]);
    }
}