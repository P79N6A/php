<?php
class DAOActChristmas extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_ACTIVITY_JAVA");
        $this->setTableName("activity_christmas");
    }

    public function add($stime,$etime,$actid=0)
    {
        $d=array(
        'actid'=>$actid,
        'stime'=>$stime,
        'etime'=>$etime,
        'addtime'=>date("Y-m-d H:i:s"),
        );
        return $this->insert($this->getTableName(), $d);
    }
}
?>
