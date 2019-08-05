<?php

class DAODeposit extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("deposit");
    }

    public function prepare($uid, $orderid, $source, $currency, $amount, $tradeid, $extends = [])
    {
        $info = [
            "uid" => $uid,
            "orderid" => $orderid,
            "source" => $source,
            "tradeid" => $tradeid,
            "currency" => $currency,
            "amount" => $amount,
            "addtime" => date("Y-m-d H:i:s"),
            "modtime" => date("Y-m-d H:i:s"),
            "extends" => is_array($extends) ? json_encode($extends) : json_encode([]),
        ];

        return $this->insert($this->getTableName(), $info);
    }

    public function setDepositStatus($orderid, $status, $tradeid = 0)
    {
        $update = [];
        $update["status"] = $status;
        if ($tradeid) {
            $update["tradeid"] = $tradeid;
        }

        $update["modtime"] = date("Y-m-d H:i:s");

        return $this->update($this->getTableName(), $update, "orderid=?", $orderid) ? true : false;
    }

    public function getDepositList($uid, $offset, $num)
    {
        $sql = "select * from {$this->getTableName()} where uid=? and id<$offset order by id desc limit $num";
        $data = $this->getAll($sql, $uid);

        return $data;
    }

    public function getDepositNum($uid)
    {
        $sql = "select count(*) from {$this->getTableName()} where uid=?";

        return $this->getOne($sql, $uid);
    }

    public function getDepositNumByDate($uid, $date)
    {
        $sql = "select count(*) from {$this->getTableName()} where uid=? and status='Y'";

        if ($date) {
            $sql .= " and addtime like '$date%'";
        }

        return $this->getOne($sql, $uid);
    }

    public function getDepositInfo($orderid)
    {
        $sql = "select * from {$this->getTableName()} where orderid=?";

        return $this->getRow($sql, $orderid);
    }

    public function modDeposit($orderid, $info, $tradeid)
    {

        return $this->update($this->getTableName(), $info, "orderid=? and tradeid!=?", ['orderid' => $orderid, 'tradeid' => $tradeid]);
    }

    public function verify($transaction_id)
    {
        $sql = "select id, orderid, amount, currency, status from {$this->getTableName()} where tradeid=?";

        return $this->getRow($sql, $transaction_id);
    }

    public function getDepositInfoBySourceTradeid($source, $tradeid)
    {
        $sql = "SELECT * FROM {$this->getTableName()} WHERE source=? AND tradeid=?";

        return $this->getRow($sql, [$source, $tradeid]);
    }

    public function getDepositInfoBySourceStatus($source, $status)
    {
        $sql = "SELECT * FROM {$this->getTableName()} WHERE source=? AND status=? ";

        return $this->getAll($sql, [$source, $status]);
    }

    public function getAllDepositInfoBySourceTradeidStatus($source, $tradeid, $status)
    {
        $sql = "SELECT * FROM {$this->getTableName()} WHERE source=? AND tradeid=? AND status=? ";

        return $this->getRow($sql, [$source, $tradeid, $status]);
    }
}