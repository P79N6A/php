<?php
class SellDetail extends Table
{
	public function __construct($table = 'sell_detail', $primary = 'id')
	{
		$this->setDBConf("MYSQL_CONF_MALL");
		$this->setTableName($table);
		$this->setPrimary($primary);
	}

	const STATUS_SUCCESS = 200;
	
	
	public function getSellDetailBySellId($sellid)
	{
		$sql = "select * from ".$this->getTableName()." where sellid = ? and status = ?  order by id desc";
		
		return $this->getAll($sql, array($sellid, 200));
	}

	public function updateInfo($data, $condition, $param)
    {
        return $this->update($this->getTableName(), $data, $condition, $param);
    }

	
}