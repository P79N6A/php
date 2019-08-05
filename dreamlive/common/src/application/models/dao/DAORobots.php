<?php
class DAORobots extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setTableName("robots");
    }
    
    public function getList()
    {
        $sql = "select * from " . $this->getTableName();
        return $this->getAll($sql);
    }
}

?>