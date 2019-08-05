<?php

class DAOEmployeRelease extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_FAMILY");
        $this->setTableName("employe_release");
    }

    public function add($info, $release_reason, $release_time)
    {
        $info['release_reason'] = $release_reason;
        $info['release_time'] = $release_time;

        return $this->insert($this->getTableName(), $info);
    }

    public function getRecent($authorid, $release_time)
    {
        $sql = "SELECT * from {$this->getTableName()} WHERE authorid=? AND release_time>=?";

        return $this->getRow($sql, [$authorid, $release_time]);
    }
}