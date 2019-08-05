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

    public function getReceiveGiftNumTopN($giftid,$start,$end)
    {
        $re= $this->getAll("select sum(num) as total,receiver as uid,giftid from ".$this->getTableName()." where giftid=? and addtime between '".$start."' and '".$end."' group by uid order by total desc limit 3", ['giftid'=>$giftid]);
        array_walk(
            $re, function (&$v,$k) {
                $v['rank']=$k+1;
            } 
        );
        return $re;
    }

    public function getSendGiftNumTopN($giftid,$start,$end)
    {
        $re= $this->getAll("select sum(num) as total,sender as uid ,giftid from ".$this->getTableName()." where giftid=? and addtime between '".$start."' and '".$end."' group by uid order by total desc limit 3", ['giftid'=>$giftid]);
        array_walk(
            $re, function (&$v,$k) {
                $v['rank']=$k+1;
            } 
        );
        return $re;
    }

    public function getGiftRankByUid($uid,$giftid,$start,$end)
    {
        if (!$uid||$uid<=0) { return null;
        }
        if (!$giftid) { return null;
        }
        if (!$start) { return null;
        }
        if (!$end) { return null;
        }
        $ps=$uid.",".$giftid.",'".$start."','".$end."'";
        return $this->getRow("CALL DreamGetGiftStarRankByUid(".$ps.")", array(), false);
    }
}