<?php

class DAOWebcategory extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_ACTIVITY");
        $this->setTableName("web_category");
    }

    public function getCategory($fid=0)
    {
        $sql = "select * from {$this->getTableName()} where status='Y' and fid=? order by score desc";
        return $this->getAll($sql, array($fid));
    }

    public function getInfo($id)
    {
        $sql = "select * from {$this->getTableName()} where status='Y' and id=? order by score desc";
        return $this->getAll($sql, array($id));
    }
}
