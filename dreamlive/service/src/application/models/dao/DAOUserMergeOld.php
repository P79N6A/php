<?php
class DAOUserMergeOld extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setTableName("user_merge_old");
    }
    public function addUserMerge($uid)
    {
        $info["uid"] = $uid;
        $info["addtime"] = date("Y-m-d H:i:s");
        $info["modtime"] = date("Y-m-d H:i:s");
        $info["status"] = 'PREPARE';

        return $res = $this->insert($this->getTableName(), $info);
    }
    public function setUserMergeAuzd($uid, $olduid, $rid, $oldrid)
    {
        $info["olduid"] = $olduid;
        $info["rid"] = $rid;
        $info["oldrid"] = $oldrid;
        $info["modtime"] = date("Y-m-d H:i:s");
        $info["status"] = 'AUTHORIZED';
        $info["errno"] = 0;

        return $this->update($this->getTableName(), $info, "uid=?", $uid);
    }
    public function getOldUser($uid, $mergeid)
    {
        $sql = "select id,uid,olduid,rid,oldrid,status,errno from " . $this->getTableName() . " where uid=? and id=?";

        return $this->getRow($sql, array($uid,$mergeid));
    }
    public function getOldUserByOlduid($uid)
    {
        $sql = "select id,uid,olduid,status,errno,modtime from " . $this->getTableName() . " where olduid=?";

        return $this->getRow($sql, $uid);
    }
    public function getOldUserByUid($uid)
    {
        $sql = "select id,uid,olduid,rid,oldrid,status,errno from " . $this->getTableName() . " where uid=?";

        return $this->getRow($sql, array($uid));
    }
    public function setUserMergeSucess($uid, $diamond, $ticket)
    {
        $info["status"] = 'SUCCESS';
        $info["diamond"] = $diamond;
        $info["ticket"] = $ticket;
        $info["modtime"] = date("Y-m-d H:i:s");

        return $this->update($this->getTableName(), $info, "uid=?", $uid);
    }
    public function setUserMergeFail($uid)
    {
        $info["status"] = 'FAIL';
        $info["modtime"] = date("Y-m-d H:i:s");

        return $this->update($this->getTableName(), $info, "uid=?", $uid);
    }

    public function setUserMergeError($uid, $errno = 0, $errmsg = '')
    {
        $sql = "update {$this->getTableName()} set status=? ,errno=?, errmsg = concat(errmsg,?,'|'),modtime=? where uid=?";

        return $this->query($sql, array('FAIL',$errno,$errmsg,date("Y-m-d H:i:s"),$uid));
    }

    public function delUserMergeOldById($mergeid)
    {
        $sql = "delete from {$this->getTableName()} where id=? and status='FAIL' limit 1";
        
        return $this->query($sql, array($mergeid));
    }


}
?>
