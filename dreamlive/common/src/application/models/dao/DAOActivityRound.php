<?php
class DAOActivityRound extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_ACTIVITY");
        $this->setTableName("activity_round");
    }

    public function getRoundById($roundid)
    {
        return $this->getRow("select * from ".$this->getTableName()." where roundid=?", ['roundid'=>$roundid]);
    }

    public function getRoundByActivityId($activityid)
    {
        return $this->getAll("select * from ".$this->getTableName()." where activityid=?", ['activityid'=>$activityid]);
    }

    public function add($activityid,$name,$round,$startime,$endtime,array $extends)
    {
        $d=[
        'activityid'=>$activityid,
        'name'=>$name,
        'round'=>$round,
        'startime'=>$startime,
        'endtime'=>$endtime,
        'extends'=>json_encode($extends),
        'addtime'=>date('Y-m-d H:i:s'),
        ];
        return $this->insert($this->getTableName(), $d);
    }

    public function mod($roundid,$name,$round,$startime,$endtime,array $extends)
    {
        $d=[];
        if ($name) {
            $d['name']=$name;
        }
        if ($round) {
            $d['round']=$round;
        }
        if ($startime) {
            $d['startime']=$startime;
        }
        if ($endtime) {
            $d['endtime']=$endtime;
        }
        if (!empty($extends)) {
            $d['extends']=$extends;
        }
        return $this->update($this->getTableName(), $d, "roundid=?", ['roundid'=>$roundid]);
    }

    public function del($roundid)
    {
        return $this->delete($this->getTableName(), 'roundid=?', ['roundid'=>$roundid]);
    }
}
?>
