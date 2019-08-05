<?php
class DAOActivityLotto extends DAOProxy
{
    const LOTTO_STATUS_NOT_RECEIVE=0;
    const LOTTO_STATUS_RECEIVE=1;

    const LOTTO_LEVEL_ONE=1;
    const LOTTO_LEVEL_TWO=2;
    const LOTTO_LEVEL_THREE=3;
    const LOTTO_LEVEL_LUCKY=4;
    const LOTTO_LEVEL_NULL=5;

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_ACTIVITY");
        $this->setTableName("activity_lotto");
    }

    public function add($activityid,$roundid,$moduleid,$uid,$level,$status)
    {
        $d=[
        'activityid'=>$activityid,
        'roundid'=>$roundid,
        'moduleid'=>$moduleid,
        'uid'=>$uid,
        'level'=>$level,
        'status'=>$status,
        'addtime'=>date('Y-m-d H:i:s'),
        ];
        return $this->insert($this->getTableName(), $d);
    }

    public function getLottoById($lottoid)
    {
        return $this->getRow("select * from ".$this->getTableName()." where id=?", ['id'=>$lottoid]);
    }
}
?>
