<?php
class DAOWithdrawMobile extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("withdraw_mobile");
    }
    
    public function add($uid, $mobile)
    {
        $sql = "INSERT INTO {$this->getTableName()} (uid,mobile,addtime) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE  mobile=mobile, modtime=?";
        return $this->Execute($sql, array($uid, $mobile, date("Y-m-d H:i:s"), $mobile, date("Y-m-d H:i:s")), false);
    }
    
    public function edit($uid, $mobile)
    {
        $info = array(
        "mobile"    => $mobile,
        "modtime"     => date("Y-m-d H:i:s")
        );
    
        return $this->update($this->getTableName(), $info, "uid=?", $uid);
    }

    public function exists($uid)
    {
        $sql = "select mobile from {$this->getTableName()} where uid=?";
        return $this->getOne($sql, $uid);
    }

}

?>