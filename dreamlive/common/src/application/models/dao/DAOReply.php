<?php
class DAOReply extends DAOProxy
{

    public function __construct($pid)
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setShardId($pid);
        $this->setTableName("reply");
    }

    public function getReplies($pid, $offset, $num = 20)
    {
        $sql = "select {$this->_getFields()} from " . $this->getTableName();
        $sql .= " where pid=?";
        $sql .= " and floor > ? ";
        $sql .= " order by floor limit $num";
        
        return $this->getAll(
            $sql, array(
            $pid,
            $offset
            )
        );
    }

    public function getTotal($pid)
    {
        /* {{{ */
        $sql = "select count(0) from {$this->getTableName()} where pid=?";
        return (int) $this->getOne($sql, $pid);
    }
    /* }}} */
    public function getReplyInfo($rid)
    {
        /* {{{ */
        $sql = "select {$this->_getFields()}  from {$this->getTableName()} where rid=?";
        return $this->getRow($sql, $rid);
    }
    /* }}} */
    public function addReply($pid, $qid, $floor, $uid, $content)
    {
        /* {{{ */
        $reply_info = array(
            "pid" => $pid,
            "qid" => $qid,
            "floor" => $floor,
            "uid" => $uid,
            "content" => $content,
            "addtime" => date("Y-m-d H:i:s"),
            "modtime" => date("Y-m-d H:i:s")
        );
        
        return $this->insert($this->getTableName(), $reply_info);
    }
    /* }}} */
    public function delReply($rid)
    {
        /* {{{ */
        $sql = "delete from {$this->getTableName()} where rid=?";
        return $this->execute($sql, $rid);
    }
    /* }}} */
    private function _getFields()
    {
        return "rid, pid, qid, uid, content, floor, addtime";
    }
}
