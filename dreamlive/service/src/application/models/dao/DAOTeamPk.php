<?php
class DAOTeamPk extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_ACTIVITY_JAVA");
        $this->setTableName("activity_team_pk");
    }

    public function add($uid,$group)
    {
        $t=$this->getAnchorState($uid);
        if ($t) { return $t['id'];
        }
        $d=array(
        'uid'=>$uid,
        'group'=>$group,
        'addtime'=>date("Y-m-d H:i:s"),
        );
        return $this->insert($this->getTableName(), $d);
    }

    public function getList()
    {
        return $this->getAll("select * from ".$this->getTableName(), "");
    }

    public function getAnchorState($uid)
    {
        return $this->getRow("select * from ".$this->getTableName()." where uid=?", array('uid'=>$uid));
    }
}
?>
