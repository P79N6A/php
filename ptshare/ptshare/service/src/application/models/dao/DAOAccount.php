<?php
class DAOAccount extends DAOProxy
{
	public function __construct($userid)
	{
		$this->setDBConf("MYSQL_CONF_PAYMENT");
		$this->setShardId($userid);
		$this->setTableName("account");
	}

	public function getBalance($currency, $locked=false)
	{
        $sql = "select balance from " . $this->getTableName() . " where uid=? and currency = ?";
        $sql .= $locked ? " for update" : "";
		return $this->getOne($sql, array($this->getShardId(), $currency));
	}

	public function getAccountList()
	{
		$sql = "select " . $this->_getFields() . " from " . $this->getTableName() . " where uid=?";
		$list = $this->getAll($sql, $this->getShardId());

		return $list;
	}

	public function insert($uid, $type, $balance)
	{
		$sql = "INSERT INTO {$this->getTableName()} (uid,currency,balance,addtime) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE  balance=(balance+?), modtime=?";
		return $this->Execute($sql, array($this->getShardId(), $type, $balance, date("Y-m-d H:i:s"), $balance, date("Y-m-d H:i:s")), false);
	}

	public function decrease($uid, $type, $balance)
	{
		$sql = "update {$this->getTableName()} set balance=(balance-?), modtime= ? where uid =? and currency =? and balance >= ? ";
		return $this->Execute($sql, array($balance, date("Y-m-d H:i:s"), $uid, $type, $balance))? true : false;
	}

	public function increase($uid, $type, $balance)
	{
		$sql = "update {$this->getTableName()} set balance=(balance+?), modtime=? where uid =? and currency =? ";
		return $this->Execute($sql, array($balance, date("Y-m-d H:i:s"), $uid, $type))? true : false;
	}

	private function _getFields()
	{
		return "currency, balance, addtime, modtime";
	}
}
?>
