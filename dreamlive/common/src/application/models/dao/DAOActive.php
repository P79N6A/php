<?php
class DAOActive extends DAOProxy
{
    public function __construct($userid='')
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("operations");
    }

    public function getList($uid)
    {
        $sql = "select * from {$this->getTableName()} where uid=? and type=2";
        $data = $this->getAll($sql, $uid);
        return $data;
    }

    public function get($activeid_str, $receiver)
    {
        $sql = "select * from {$this->getTableName()} where activeid in($activeid_str) and type=3 and uid=?";
        $data = $this->getRow($sql, $receiver);
        return $data;
    }

    public function getInfo($activeid)
    {
        $sql = "select * from actives where activeid =?";
        $data = $this->getRow($sql, $activeid);
        return $data;
    }

}

?>