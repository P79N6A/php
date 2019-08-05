<?php
class Express extends Table{

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName('express');
    }

    public function getExpressDetail($orderid)
    {
        $query = "select * from ".$this->getTableName()." where orderid = ? ";

        return $this->getRow($query, array($orderid));
    }

    public function getList($start, $limit, $orderid, $status)
    {
        $condition_ar[] = " 1=? ";
        $params[] = 1;

        if (!empty($orderid)) {
            $condition_ar[] = " orderid = ? ";
            $params[] = $orderid;
        }

        if (!empty($status)) {
            $condition_ar[] = " status = ? ";
            $params[] = $status;
        }
        
        $condition = implode(" and ", $condition_ar);

        $sql = "select * from ".$this->getTableName()." where {$condition} order by id desc ";

        $sql.= $limit > 0 ? " limit $start, $limit" : "";
        $data = $this->getAll($sql, $params);

        $sql_count = "select count(*) from ".$this->getTableName()." where {$condition} order by id desc ";
        $total = $this->getOne($sql_count, $params);

        return array($data, $total);
    }

}