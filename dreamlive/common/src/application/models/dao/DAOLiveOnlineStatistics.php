<?php
class DAOLiveOnlineStatistics extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_DREAM_REPORT");
        $this->setTableName("live_online_statistics");
    }

    public function add($uid,$liveid,$sn,$partner,$num)
    {
        $d=array(
            'uid'=>$uid,
            'liveid'=>$liveid,
            'sn'=>$sn,
            'partner'=>$partner,
            'num'=>$num,
            'addtime'=>date("Y-m-d H:i").":00",
        );
        return $this->insert($this->getTableName(), $d);
    }
}
?>
