<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/5
 * Time: 14:11
 */
class DAOGiftStarLog extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("giftstarlog");
    }

    public function add($orderid,$sender,$receiver,$liveid,$giftid,$consume,$price,$ticket,$num)
    {
        $sql="insert into ".$this->getTableName()." (orderid,sender,receiver,liveid,giftid,consume,price,ticket,num,addtime)values(?,?,?,?,?,?,?,?,?,?)";
        return $this->Execute(
            $sql, [$orderid,$sender,$receiver,$liveid,$giftid,$consume,
            $price,$ticket,$num,date("Y-m-d H:i:s")]
        );
    }
    public function getInfo($orderid)
    {
        $sql = "select * from " . $this->getTableName() . " where orderid=?";

        return $this->getRow($sql, $orderid);
    }
}