<?php
class DAOActivitySupport extends DAOProxy
{
    const SUPPORT_TYPE_GIFT=1;
    const SUPPORT_TYPE_VOTE=2;
    const SUPPORT_TYPE_FREE_VOTE=3;
    const SUPPORT_TYPE_SHARE=4;
    const SUPPORT_TYPE_UPLOAD=5;
    const SUPPORT_TYPE_JURY_VOTE=6;//评委投票

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_ACTIVITY");
        $this->setTableName("activity_support");
    }

    public function add($activityid,$roundid,$moduleid,$type,$uid,$num,$receiveid=0,$relateid=0,$extends=array())
    {
        $d=[
        'activityid'=>$activityid,
        'roundid'=>$roundid,
        'moduleid'=>$moduleid,
        'type'=>$type,
        'relateid'=>$relateid,
        'supporter'=>$uid,
        'candidate'=>$receiveid,
        'num'=>$num,
        'extends'=>json_encode($extends),
        'addtime'=>date("Y-m-d H:i:s"),
        ];
        return $this->insert($this->getTableName(), $d);
    }
}
?>
