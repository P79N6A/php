<?php

class DAOUserVip extends DAOProxy
{

    public function __construct($userid)
    {
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setShardId($userid);
        $this->setTableName("user_vip");
    }

    public function getUserInfo()
    {
        $sql = "select consume_current,consume_keep,uptime from {$this->getTableName()} where uid = ?";
        
        return $this->getRow($sql, $this->getShardId());
    }

    public function addUserConsume($amont)
    {
        $info['uid']             = $this->getShardId();
        $info['consume_current'] = $amont;
        $info['addtime']         = date("Y-m-d H:i:s");
        $info['modtime']         = date("Y-m-d H:i:s");

        return $this->insert($this->getTableName(), $info);
    }

    public function incrUserConsume($amont)
    {
        $modtime = date("Y-m-d H:i:s");
        $sql = "update {$this->getTableName()} set modtime='{$modtime}', consume_current = consume_current + ? where uid =?";
        return $this->execute(
            $sql, array(
            $amont,
            $this->getShardId()
            )
        );
    }

    public function modUserVip($uid, $info)
    {
        // $info["recounttime"] = time();
        
        return $this->update($this->getTableName(), $info, "uid=?", $uid);
    }

    public function modUserVipRecounttime($uid)
    {
        $info["recounttime"] = time();
        
        return $this->update($this->getTableName(), $info, "uid=?", $uid);
    }

    public function addUptime($uid)
    {
        $info["uptime"] = date("Y-m-d H:i:s");
        
        return $this->update($this->getTableName(), $info, "uid=?", $uid);
    }

    public function delUserVip($uid)
    {
        $sql = "delete from {$this->getTableName()} where uid=?";

        return $this->Execute($sql, array($uid)) ? true : false;
    }

}
