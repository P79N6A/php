<?php
class DAOLivePushLagLog extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_DREAM_REPORT");
        $this->setTableName("live_push_lag_log");
    }

    public function add($uid,$nickname,$newest_liveid,$avg_fps,$avg_bps,$avg_plr,$avg_lfps,$result=array(),$extends=array())
    {
        $d=array(
        'uid'=>$uid,
        'nickname'=>$nickname,
        'newest_liveid'=>$newest_liveid,
        'avg_fps'=>$avg_fps,
        'avg_bps'=>$avg_bps,
        'avg_plr'=>$avg_plr,
        'avg_lfps'=>$avg_lfps,
        'result'=>empty($result)?json_encode([]):json_encode($result),
        'extends'=>empty($extends)?json_encode([]):json_encode($extends),
        'addtime'=>date("Y-m-d H:i:s"),
        );
        return $this->insert($this->getTableName(), $d);
    }
}
?>
