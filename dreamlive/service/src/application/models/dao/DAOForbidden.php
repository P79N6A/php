<?php
class DAOForbidden extends DAOProxy
{
    public function __construct()
    {
        /* {{{ */
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setTableName("forbidden");
    }
    /* }}} */
    public function addForbidden($uid, $expire, $reason = "")
    {
        /* {{{ */
        $arr_info["uid"] = $uid;
        $arr_info["expire"] = $expire;
        $arr_info["reason"] = $reason;
        $arr_info["addtime"] = $arr_info['modtime'] = date("Y-m-d H:i:s");
        
        return $this->replace($this->getTableName(), $arr_info);
    }
    /* }}} */
    public function getForbiddenLists($uids)
    {
        /* {{{ */
        $sql = "select uid as relateid, expire, reason from {$this->getTableName()} where uid in (" . implode(",", $uids) . ")";
        return $this->getAll($sql, null);
    }
    /* }}} */
    public function getForbidden($uid)
    {
        /* {{{ */
        $sql = "select uid as relateid, expire, reason from {$this->getTableName()} where uid = ? ";
        return $this->getRow(
            $sql, array(
            $uid
            )
        );
    }
    /* }}} */
    public function unForbidden($uid)
    {
        /* {{{ */
        $sql = "delete from {$this->getTableName()} where uid = ? ";
        return $this->execute(
            $sql, array(
            $uid
            )
        );
    } /* }}} */
}

