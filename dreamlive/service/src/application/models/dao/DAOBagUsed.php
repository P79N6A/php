<?php
class DAOBagUsed extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("bag_used");
    }

    public function insert($uid, $productid, $bagid, $expiretime)
    {
        $sql = "INSERT INTO {$this->getTableName()} (uid,productid,bagid,expiretime, addtime) VALUES (?,?,?,?,?)";
        return $this->Execute($sql, array($uid, $productid, $bagid, $expiretime, date("Y-m-d H:i:s")), false);
    }

    public function add($uid, $relateid, $bagid, $num,array $ext=[])
    {
        $ext=empty($ext)?[]:$ext;
        $sql = "INSERT INTO {$this->getTableName()} (uid,relateid,bagid,num, addtime,extends) VALUES (?,?,?,?,?,?)";
        return $this->Execute($sql, array($uid, $relateid, $bagid, $num, date("Y-m-d H:i:s"),json_encode($ext)), false);
    }
}
?>
