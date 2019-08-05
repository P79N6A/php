<?php
class DAOActChristmasLog extends DAOProxy
{
    const TYPE_WISH=1;
    const TYPE_RED_PACKET=2;
    const TYPE_LOTTO_TICKET=3;
    const TYPE_RIDE=4;

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_ACTIVITY_JAVA");
        $this->setTableName("activity_christmas_log");
    }

    public function add($roundid,$type,$uid,$utype,$actid=0)
    {
        $d=array(
        'roundid'=>$roundid,
        'type'=>$type,
        'uid'=>$uid,
        'utype'=>$utype,
        'addtime'=>date("Y-m-d H:i:s"),
        'actid'=>$actid,
        );
        return $this->insert($this->getTableName(), $d);
    }
}
?>
