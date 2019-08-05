<?php
class DAOVerified extends DAOProxy
{
    public function __construct($userid = 0)
    {
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setTableName("verified");
    }

    public function addVerified($uid, $type, $realname, $mobile, $idcard, $credentials, $imgs)
    {
        /*{{{*/
        $arr_info = array(
            "uid"         => $uid,
            "type"      => $type,
            "realname"    => $realname,
            "mobile"      => $mobile,
            "idcard"      => $idcard,
            "credentials" => $credentials,
            "imgs"        => $imgs,
            "addtime"     => date("Y-m-d H:i:s"),
            "modtime"     => date("Y-m-d H:i:s"),
        );
        return $this->insert($this->getTableName(), $arr_info);
    }/*}}}*/

    public function modVerified($uid, $type, $realname, $mobile, $idcard, $credentials, $imgs='')
    {
        /*{{{*/
        $arr_info = array(
            "uid"         => $uid,
            "type"      => $type,
            "realname"    => $realname,
            "mobile"      => $mobile,
            "idcard"      => $idcard,
            "credentials" => $credentials,
            "imgs"        => $imgs,
            "modtime"     => date("Y-m-d H:i:s"),
        );
        if($imgs) {
            $arr_info['imgs'] = $imgs;
        }
        return $this->update($this->getTableName(), $arr_info, "uid=?", $uid);
    }/*}}}*/

    public function getVerifiedInfo($uid, $forceMaster = false)
    {
        /* {{{ */
        $sql = "select " . $this->_getFields() . " from " . $this->getTableName() . " where uid=?";

        return $this->getRow($sql, $uid);
    }
    /* }}} */
    public function getVerifiedsInfo($arr_uid_list, $forceMaster = false)
    {
        /* {{{ */

        $sql = "select " . $this->_getFields() . " from " . $this->getTableName() . " where uid in(" . implode(",", $arr_uid_list) . ")";
        $data = $this->getAll($sql, null, false);

        $list = array();
        foreach ($data as $v) {
            $list[$v["uid"]] = $v;
        }

        return $list;
    }
    /* }}} */

    public function delete($uid)
    {
        /*{{{*/
        $sql = "delete from {$this->getTableName()} where uid=?";
        return $this->execute($sql, $uid);
    }/*}}}*/

    private function _getFields()
    {
        /* {{{ */
        return "uid, type, realname, mobile, idcard, imgs, credentials, modtime";
    } /* }}} */
    
    /**
     * 是否存在
     *
     * @param int    $uid
     * @param string $where
     */
    public function isExistByFields($uid,$where)
    {
        $sql    = " select count(0) as cnt from ".$this->getTableName()." where uid=? and ".$where;
        $result = $this->getRow($sql, $uid);
        if(isset($result['cnt']) && $result['cnt']>0) {
            return true;
        }
        return false;
    }
}
