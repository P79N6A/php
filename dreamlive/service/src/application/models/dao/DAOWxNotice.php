<?php
class DAOWxNotice extends DAOProxy
{
    public function __construct()
    {
        /* {{{ */
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setTableName("user_wx_notice");

    }/* }}} */

    public function addNotice($uid,$openid,$formid)
    {
        /* {{{ */
        $arr_info["uid"] = $uid;
        $arr_info["openid"] = $openid;
        $arr_info["formid"] = $formid;
        $arr_info["addtime"] = date("Y-m-d H:i:s");
        return $this->insert($this->getTableName(), $arr_info);
    }/* }}} */

    public function exists($uid)
    {
        $sql = "select id from {$this->getTableName()} where uid=?";
        return $this->getOne($sql, $uid);
    }

    public function modNotice($uid,$openid, $formid)
    {
        /*{{{*/
        $arr_info = array(
            "openid"    => $openid,
            "formid"    => $formid,
            "modtime"     => date("Y-m-d H:i:s"),
        );
        return $this->update($this->getTableName(), $arr_info, "uid=?", $uid);
    }/*}}}*/

    public function getNoticeInfo($uid)
    {
        /*{{{*/
        $sql = "select openid, formid, addtime,modtime from {$this->getTableName()} where uid=?";
        return $this->getRow($sql, $uid);
    }/*}}}*/

    public function getNoticeUsers()
    {
        return $this->getAll("select * from ".$this->getTableName()." order by id desc limit 10000");
    }


}
