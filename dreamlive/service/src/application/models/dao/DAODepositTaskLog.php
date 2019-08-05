<?php
class DAODepositTaskLog extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("deposit_tasklog");
    }

    public function addDepositTaskLog($orderid, $uid, $amount)
    {
        $info=array(
        "orderid"=>$orderid,
        "amount"=>$amount,
        "uid"=>$uid,
        "addtime"=>date("Y-m-d H:i:s"),
        ); //test
        return $this->insert($this->getTableName(), $info);
    }

    public function modifyDepositTaskLog($orderid,$status="Y")
    {
        $info['status']=$status;
        return $this->update($this->getTableName(), $info, " orderid=?", [$orderid]);
    }
}
?>
