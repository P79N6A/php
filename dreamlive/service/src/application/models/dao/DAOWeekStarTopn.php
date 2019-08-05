<?php
class DAOWeekStarTopn extends DAOProxy
{
    const DIRECT_RECEIVE=1;
    const DIRECT_SEND=2;

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("weekstartopn");
    }

    public function add($week,$week_start,$week_end,$uid,$giftid,$num,$addtime,$direct=1)
    {
        $d=[
        'week'=>$week,
        'week_start'=>$week_start,
        'week_end'=>$week_end,
        'uid'=>$uid,
        'giftid'=>$giftid,
        'num'=>$num,
        'direct'=>$direct,
        'addtime'=>$addtime,
        ];

        return $this->insert($this->getTableName(), $d);
    }


}
?>
