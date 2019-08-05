<?php
class GoodsLabel extends  Table {
	public function __construct($table = 'goods_label', $primary = 'id')
	{
		$this->setDBConf("MYSQL_CONF_ADMIN");
		$this->setTableName($table);
		$this->setPrimary($primary);
	}


	public function add($info)
	{

		return $this->addRecord($info);
	}
}