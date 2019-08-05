<?php
class DAODefriend extends DAOProxy
{
    public function __construct()
    {
        /* {{{ */
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setTableName("defriend");
    }
    /* }}} */
    public function addDefriend($uid, $expire)
    {
        /* {{{ */
        $arr_info["uid"] = $uid;
        $arr_info["expire"] = $expire;
        $arr_info["addtime"] = $arr_info['modtime'] = date("Y-m-d H:i:s");

        return $this->replace($this->getTableName(), $arr_info);
    }
    /* }}} */
    public function getDefriendByUid($uid)
    {
        /* {{{ */
        $sql = "select uid, expire from {$this->getTableName()} where uid = ? ";
        return $this->getRow(
            $sql, array(
            $uid
            )
        );
    }
    /* }}} */
    public function getDefriendByUids($uids)
    {
        /* {{{ */
        $sql = sprintf("select * from {$this->getTableName()} where uid IN (%s) and exipire>=?", implode(",", array_map("intval", $uids)));
        return $this->getRow($sql, array(time()));
    }
    /* }}} */

    public function unDefriend($uid)
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
