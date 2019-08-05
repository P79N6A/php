<?php
class DAOActivityModule extends DAOProxy
{
    const MODULE_TYPE_APPLY=1;
    const MODULE_TYPE_SUPPORT=2;
    const MODULE_TYPE_RANK=3;
    const MODULE_TYPE_PROMOTION=4;
    const MODULE_TYPE_LOTTO=5;

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_ACTIVITY");
        $this->setTableName("activity_module");
    }

    public function getModuleById($moduleid)
    {
        return $this->getRow("select * from ".$this->getTableName()." where moduleid=?", ['moduleid'=>$moduleid]);
    }

    public function getModuleByActivityId($activityid)
    {
        return $this->getAll("select * from ".$this->getTableName()." where activityid=?", ['activityid'=>$activityid]);
    }

    public function getModuleByRoundId($roundid)
    {
        return $this->getAll("select * from ".$this->getTableName()." where roundid=?", ['roundid'=>$roundid]);
    }

    public function add($activityid,$roundid,$name,$type,$scripts,array $extends=array())
    {
        $now=date("Y-m-d H:i:s");
        $d=[
        'activityid'=>$activityid,
        'roundid'=>$roundid,
        'name'=>$name,
        'type'=>$type,
        'scripts'=>$scripts,
        'extends'=>json_encode($extends),
        'addtime'=>$now,
        'modtime'=>$now,
        ];
        return $this->insert($this->getTableName(), $d);
    }

    public function mod($moduleid,$name,$scripts,array $extends=array())
    {
        $d=[];
        if ($name) {
            $d['name']=$name;
        }

        if ($scripts) {
            $d['scripts']=$scripts;
        }
        if (!empty($extends)) {
            $d['extends']=$extends;
        }
        return $this->update($this->getTableName(), $d, 'moduleid=?', ['moduleid'=>$moduleid]);
    }

    public function del($moduleid)
    {
        return $this->delete($this->getTableName(), 'moduleid=?', ['moduleid'=>$moduleid]);
    }
}
?>
