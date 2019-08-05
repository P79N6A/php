<?php
class DAOOperations extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("operations");
    }
    //检测是否为运营号
    public function checkUid($uid)
    {
        $sql    = "select uid from {$this->getTableName()} where uid=?";

        return $this->getRow($sql, $uid);
    }
    //检测是否为运营号
    public function getDataByActiveid($activeid)
    {
        $sql    = "select * from {$this->getTableName()} where activeid=?";

        return $this->getAll($sql, $activeid);
    }

}
