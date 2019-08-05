<?php
class DAOBlocked extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setTableName("blocked");
    }

    public function addBlocked($uid, $bid)
    {
        /* {{{ */
        $blocked_info = array(
            "uid" => $uid,
            "bid" => $bid,
            "addtime" => date("Y-m-d H:i:s")
        );
        
        return $this->insert($this->getTableName(), $blocked_info);
    }
    /* }}} */
    public function getBlockeds($uid, $start = null, $num = null, $master = false)
    {
        /* {{{ */
        $limit = (is_numeric($start) && is_numeric($num)) ? " order by id desc limit $start , $num " : "order by id desc";
        $sql = "select " . $this->_getFields() . " from " . $this->getTableName() . " where uid=? $limit";
        
        return $this->getAll($sql, $uid);
    }
    /* }}} */
    public function getBlockers($bid, $start = null, $num = null)
    {
        /* {{{ */
        $limit = (is_numeric($start) && is_numeric($num)) ? " order by id desc limit $start , $num " : "";
        $sql = "select " . $this->_getFields() . " from " . $this->getTableName() . " where bid=? $limit";
        
        return $this->getAll($sql, $bid);
    }
    /* }}} */
    public function exists($uid, $bid)
    {
        /* {{{ */
        $sql = "select count(*) from {$this->getTableName()} where uid=? and bid=?";
        return (int) $this->getOne(
            $sql, array(
            $uid,
            $bid
            )
        ) > 0;
    }
    /* }}} */
    public function getTotal($uid)
    {
        /* {{{ */
        $sql = "select count(*) from {$this->getTableName()} where uid=?";
        return (int) $this->getOne($sql, $uid);
    }
    /* }}} */
    public function delBlocked($uid, $bid)
    {
        /* {{{ */
        $sql = "delete from {$this->getTableName()} where uid=? and bid=?";
        return $this->execute(
            $sql, array(
            $uid,
            $bid
            )
        );
    }
    /* }}} */
    private function _getFields()
    {
        /* {{{ */
        return "uid, bid, addtime";
    } /* }}} */
}
