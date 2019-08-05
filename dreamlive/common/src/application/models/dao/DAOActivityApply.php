<?php
class DAOActivityApply extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_ACTIVITY");
        $this->setTableName("activity_apply");
    }

    public function add($activityid,$roundid,$moduleid,$uid,$name,$age,$sex,$phone,$email,$address,$zone,$extends=array())
    {
        $now=date("Y-m-d H:i:s");
        $d=[
        'activityid'=>$activityid,
        'roundid'=>$roundid,
        'moduleid'=>$moduleid,
        'uid'=>$uid,
        'name'=>$name,
        'age'=>$age,
        'sex'=>$sex,
        'phone'=>$phone,
        'email'=>$email,
        'address'=>$address,
        'zone'=>$zone,
        'extends'=>json_encode($extends),
        'modtime'=>$now,
        'addtime'=>$now,
        ];
        return $this->insert($this->getTableName(), $d);
    }
    
    public function getApplyByUid($activityid,$moduleid,$uid)
    {
        return $this->getRow(
            "select * from ".$this->getTableName()." where activityid=? and moduleid=? and uid=?",
            ['activityid'=>$activityid,'moduleid'=>$moduleid,'uid'=>$uid]
        );
    }

    public function getApplyInfoByUid($moduleid,$uid)
    {
        return $this->getRow(
            "select * from ".$this->getTableName()." where  moduleid=? and uid=?",
            ['moduleid'=>$moduleid,'uid'=>$uid]
        );
    }
}
?>
