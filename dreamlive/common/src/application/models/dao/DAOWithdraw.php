<?php
class DAOWithdraw extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("withdraw");
    }

    public function add($uid, $orderid, $source, $amount, $extends, $status)
    {
        $info = array(
            "uid"    => $uid,
        "orderid"     => $orderid,
            "source" => $source,
            "amount"  => $amount,
            "extends" => $extends,
            "status" => $status,
            "addtime" => date("Y-m-d H:i:s"),
        "modtime" => date("Y-m-d H:i:s")
        );
        
        return $this->insert($this->getTableName(), $info);
    }

    public function getWithdrawList($uid, $offset, $num)
    {
        $sql = "select id, orderid, uid, `status`, reason, addtime, modtime, sum(amount) as amount from {$this->getTableName()} where uid=? and id<? group by orderid order by id desc limit  ?";
        
        $data = $this->getAll($sql, array('uid'=>$uid, 'offset'=>$offset, 'num'=>$num));
        
        return $data;
    }

    public function getWithdrawNum($uid)
    {
        $sql = "select count(*) from {$this->getTableName()} where uid=?";

        return $this->getOne($sql, $uid);
    }

    public function getWithdrawInfo($orderid)
    {
        $sql = "select * from {$this->getTableName()} where orderid=?";

        return $this->getRow($sql, $orderid);
    }

    public function updateStatus($orderid, $status, $reason)
    {
        $sql = "update {$this->getTableName()} set status=?, reason=?, modtime='" . date("Y-m-d H:i:s")."' where orderid=?";

        return $this->getRow($sql, array('status'=>$status, 'reason'=>$reason, 'orderid'=>$orderid));
    }

    public function getWithdrawTodayTotal($uid)
    {
        $sql = "select sum(amount) as amount from {$this->getTableName()} where status<>? and uid=? and addtime>?";

        return $this->getRow($sql, array('status' => 5, 'uid' => $uid, 'addtime' => date("Y-m-d", time())));
    }
}
?>