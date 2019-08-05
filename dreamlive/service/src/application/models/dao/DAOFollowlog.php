<?php
class DAOFollowlog extends DAOProxy
{
    const ACTION_ADD = "ADD";

    const ACTION_CANCEL = "CANCEL";

    public function __construct()
    {
        /* {{{ */
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setTableName("followlog");
    }
    /* }}} */
    public function addFollowlog($uid, $fid, $action, $reason = "")
    {
        /* {{{ */
        $arr_info["uid"] = $uid;
        $arr_info["fid"] = $fid;
        $arr_info["action"] = $action;
        $arr_info["reason"] = trim($reason);
        $arr_info["addtime"] = date("Y-m-d H:i:s");

        return $this->insert($this->getTableName(), $arr_info);
    } /* }}} */
}
