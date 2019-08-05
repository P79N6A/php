<?php
class DAOGiftLog extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("giftlog");
    }

    public function add($orderid, $sender, $receiver, $giftid, $liveid, $consume, $price, $ticket, $num)
    {
        $log_info = array(
            "orderid"            => $orderid,
            "sender"            => $sender,
            "receiver"          => $receiver,
            "giftid"            => $giftid,
            "liveid"            => $liveid,
            "consume"           => $consume,
            "price"       => $price,
            "ticket"    => $ticket,
            "num"      => $num,
            "addtime"      => date("Y-m-d H:i:s")
        );

        return $this->insert($this->getTableName(), $log_info);
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
        return $this->getRow("CALL DreamGetGiftRankByUid(".$ps.")", array(), false);
    }

    public function getGiftAmountTopN($giftids,$start,$end,$direct='S',$lines=10,$filter=array())
    {
        $ufield=$direct=="S"?"sender":"receiver";
        $gifts=implode(",", $giftids);
        $where="";
        if (!empty($filter)) {
            $blst=implode(",", $filter);
            $where=" and uid not in (".$blst.") ";
        }


        $re= $this->getAll("select sum(num*price) as total,".$ufield." as uid from ".$this->getTableName()." where giftid in (".$gifts.") ".$where." and addtime between '".$start."' and '".$end."' group by uid order by total desc limit ".$lines, []);
        array_walk(
            $re, function (&$v,$k) {
                $v['rank']=$k+1;
            } 
        );
        return $re;
    }
}
