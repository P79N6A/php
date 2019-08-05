<?php
class DAOUserBind extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setTableName("userbind");
    }

    public function addUserBind($uid, $rid, $source, $nickname, $avatar, $token)
    {
        $bindinfo = array(
            "uid" => $uid,
            "rid" => $rid,
            "source" => $source,
            "access_token" => $token,
            "nickname" => $nickname,
            "avatar" => $avatar,
            "addtime"=>date("Y-m-d H:i:s"),
            "modtime"=>date("Y-m-d H:i:s")
        );
        return $this->insert($this->getTableName(), $bindinfo);
    }

    public function setUserBind($uid, $source, $rid)
    {
        $bindinfo = array(
            "rid"=>$rid,
            "modtime"=>date("Y-m-d H:i:s")
        );

        return $this->update($this->getTableName(), $bindinfo, "uid=? and source=?", array($uid, $source));
    }

    public function getUserBind($uid, $source)
    {
        $sql = "select " . $this->_getFields() . " from " . $this->getTableName() . " where uid=? and source=?";
        return $this->getRow(
            $sql, array(
            $uid,
            $source
            )
        );
    }

    public function getUserBindBySource($rid, $source)
    {
        $sql = "select " . $this->_getFields() . " from " . $this->getTableName() . " where rid=? and source=?";
        return $this->getRow(
            $sql, array(
            $rid,
            $source
            )
        );
    }

    public function getUserBinds($uid)
    {
        $sql = "select " . $this->_getFields() . " from " . $this->getTableName() . " where uid=?";
        return $this->getAll($sql, $uid);
    }

    public function delete($uid, $source)
    {
        $sql = "delete from {$this->getTableName()} where uid=? and source=?";
        return $this->execute($sql, array($uid, $source));
    }

    private function _getFields()
    {
        return "id, uid, rid, nickname, avatar, source, addtime, modtime";
    }
    
    /**
     * 是否存在
     *
     * @param int    $uid
     * @param string $where
     */
    public function isExistByFields($uid)
    {
        $sql    = " select rid from ".$this->getTableName()." where uid=? and  rid !='' and source='mobile' ";
        $result = $this->getRow($sql, $uid);
        if(isset($result['rid'])) {
            return $result['rid'];
        }
        return false;
    }
}
?>
