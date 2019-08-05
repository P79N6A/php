<?php

class DAOUserVipLog extends DAOProxy
{

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setTableName("user_vip_log");
    }

    public function addLog($uid, $lastlevel, $newLevel)
    {
        $info['uid'] = $uid;
        $info['lastlevel'] = $lastlevel;
        $info['newlevel']  = $newLevel;
        $info['addtime'] = date("Y-m-d H:i:s");
        $info['modtime'] = date("Y-m-d H:i:s");

        if($lastlevel < $newLevel) {
            $info['type'] = 'Y';
        }else if($lastlevel == $newLevel) {
            $info['type'] = 'U';
        }else if($lastlevel > $newLevel) {
            $info['type'] = 'N';
        }

        return $this->insert($this->getTableName(), $info);
    }
    public function addAwardLog($id,$award)
    {
        $info["award"] = $award;
        
        return $this->update($this->getTableName(), $info, "id=?", $id);
    }

}
