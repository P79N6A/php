<?php
class DAOActivity extends DAOProxy
{
    const TYPE_COMMON=1;
    const TYPE_ROUND=2;
    const TYPE_LOTTO=3;

    const ACTIVITY_ONLINE="Y";
    const ACTIVITY_OFFLINE="N";


    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_ACTIVITY");
        $this->setTableName("activity");
    }

    public function getActivityById($activityid)
    {
        return $this->getRow("select * from ".$this->getTableName()." where activityid=?", ['activityid'=>$activityid]);
    }

    public function getCurrentActivityList()
    {
        return $this->getAll("select * from ".$this->getTableName()." where online='Y' and NOW() between startime and endtime order by addtime desc");
    }
    
    public function getHistoryActivityList()
    {
        return $this->getAll("select * from ".$this->getTableName()." where online='Y' and NOW() >= endtime order by addtime desc");
    }

    public function add($type,$name,$icon,$url,$vote,$online,$startime,$endtime,$remark,$extends)
    {
        $now=date("Y-m-d H:i:s");
        $d=[
        'type'=>$type,
        'name'=>$name,
        'icon'=>$icon,
        'url'=>$url,
        'vote'=>$vote,
        'online'=>$online,
        'startime'=>$startime,
        'endtime'=>$endtime,
        'remark'=>$remark,
        'extends'=>json_encode($extends),
        'modtime'=>$now,
        'addtime'=>$now,
        ];
        return $this->insert($this->getTableName(), $d);
    }

    public function mod($activityid,$name,$icon,$url,$vote,$online,$startime,$endtime,$remark,$extends)
    {
        $d=[];
        if ($name) {
            $d['name']=$name;
        }
        if ($icon) {
            $d['icon']=$icon;
        }
        if ($url) {
            $d['url']=$url;
        }
        if ($vote) {
            $d['vote']=$vote;
        }
        if ($online) {
            $d['online']=$online;
        }
        if ($startime) {
            $d['startime']=$startime;
        }
        if ($endtime) {
            $d['endtime']=$endtime;
        }
        if ($remark) {
            $d['remark']=$remark;
        }
        if ($extends) {
            $d['extends']=$extends;
        }

        return $this->update($this->getTableName(), $d, 'activityid=?', ['activityid'=>$activityid]);
    }

    public function del($activityid)
    {
        return $this->delete($this->getTableName(), 'activityid=?', ['activityid'=>$activityid]);
    }
}
?>
