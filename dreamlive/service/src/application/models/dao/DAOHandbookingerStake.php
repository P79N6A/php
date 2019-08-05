<?php
class DAOHandbookingerStake extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_GAME");
        $this->setTableName("handbookinger_stake");
    }
    public function insertStake($roundid, $userid, $trackno, $amount, $orderid)
    {
        $info = array(
        'roundid'        => $roundid,
        'uid'            => $userid,
        'trackno'        => $trackno,
        'amount'        => $amount,
        'orderid'        => $orderid,
        'addtime'        => date("Y-m-d H:i:s")
        );
        return $this -> insert($this->getTableName(), $info);
    }

    // 得到本场比赛的押注情况
    public function getStakeLeastHorse($roundid)
    {
        return $this->getAll("select roundid,trackno,sum(amount) as num, orderid from ".$this->getTableName()." where roundid=? group by trackno order by num ASC", $roundid);
    }
    
    public function getAllStake($roundid)
    {
        return $this->getAll("select * from ".$this->getTableName()." where roundid=?", $roundid);
    }
}
?>
