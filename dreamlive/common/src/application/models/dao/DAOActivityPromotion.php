<?php
class DAOActivityPromotion extends DAOProxy
{
    const PROMOTION_TYPE_AUTO=1;
    const RPOMOTION_TYPE_JURY=2;
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_ACTIVITY");
        $this->setTableName("activity_promotion");
    }

    public function add($activityid,$roundid,$moduleid,$uid,$type,$position,$score,$jury='',$juryid=0)
    {
        $d=[
        'activityid'=>$activityid,
        'roundid'=>$roundid,
        'moduleid'=>$moduleid,
        'uid'=>$uid,
        'type'=>$type,
        'position'=>$position,
        'score'=>$score,
        'jury'=>$jury,
        'juryid'=>$juryid,
        'addtime'=>date("Y-m-d H:i:s"),
        ];
        return $this->insert($this->getTableName(), $d);
    }
}
?>
