<?php

class Card extends Table
{
	public function __construct()
	{
		$this->setDBConf("MYSQL_CONF_MALL");
		$this->setTableName("mini_card");
		$this->setPrimary("id");
	}
	
	public function getInfo($id)
	{
		$sql = "select * from ".$this->getTableName()." where id=?";
		
		return $this->getRow($sql, [$id]);
	}
}