<?php
class DAOUserLevelMap extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_TASK");
        $this->setTableName("user_level_map");
    }

    public function getTotalCount()
    {
        $sql = "select sum(headcount) as count from {$this->getTableName()}";

        return $this->getRow($sql);
    }

    public function getEqLevelCount($level)
    {
        $sql = "select sum(headcount) as count from {$this->getTableName()} where level = ?";

        return $this->getRow($sql, $level);
    }

    public function getLtLevelCount($level)
    {
        $sql = "select sum(headcount) as count from {$this->getTableName()} where level < ?";

        return $this->getRow($sql, $level);
    }
}
