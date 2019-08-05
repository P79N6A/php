<?php
class DAOActChristmasRank extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_ACTIVITY_JAVA");
        $this->setTableName("activity_christmas_rank");
    }

    public function add($roundid,$uid,$utype,$amount,$rank,$actid=0)
    {
        $d=array(
        'roundid'=>$roundid,
        'uid'=>$uid,
        'utype'=>$utype,
        'amount'=>$amount,
        'rank'=>$rank,
        'addtime'=>date("Y-m-d H:i:s"),
        'actid'=>$actid,
        );
        return $this->insert($this->getTableName(), $d);
    }
}
?>
