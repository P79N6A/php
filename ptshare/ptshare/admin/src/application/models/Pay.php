<?php
class Pay extends Table
{
	public function __construct()
	{
		$this->setDBConf("MYSQL_CONF_PAYMENT");
		$this->setTableName("pay");
		$this->setPrimary("id");
	}

	public function getList($start, $limit, $uid, $orderid, $status, $type)
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

		if (!empty($type) && $type != '-1') {
			$condition_ar[] = " type = ? ";
			$params[] = $type;
		}

		if (!empty($status) && $status != '-1') {
			$condition_ar[] = " status = ? ";
			$params[] = $status;
		}

		$condition = implode(" and ", $condition_ar);

		$sql = "select * from ".$this->getTableName()." where {$condition} order by id desc ";

		$sql.= $limit > 0 ? " limit $start, $limit" : "";
		$data = $this->getAll($sql, $params);

		$sql_count = "select count(*) from ".$this->getTableName()." where {$condition} order by id desc ";
		$total = $this->getOne($sql_count, $params);

		return array($data, $total);
	}

	public static function getPayStatusData()
	{
		return ['P' => '支付准备','Y'=> '支付成功','N' => "支付失败", 'C' => '用户取消', 'R' => "已退款"];
	}

	public static function getPayTypeData()
	{
		return ['ORDER' => '订单','MEMBER'=> '会员','VIP' => 'VIP'];
	}

	public static function getPayMethodData()
	{
		return ['wx' => '微信'];
	}
}