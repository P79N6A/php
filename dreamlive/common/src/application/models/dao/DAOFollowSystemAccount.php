<?php

class DAOFollowSystemAccount extends DAOProxy
{

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setTableName("follow_system_account");
    }

    public function getList($offset, $num)
    {
        $sql = "select fid from ".$this->getTableName()." order by id asc  limit $offset,$num";
        return $this->getAll($sql);
    }

    public function getCount()
    {
        $sql = "select count(1) as cnt from {$this->getTableName()} ";
        $result = $this->getRow($sql);
        return isset($result['cnt']) ? $result['cnt'] : 0;
    }
}
