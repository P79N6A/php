<?php
class DAOUserMedal extends DAOProxy
{

    public function __construct($userid)
    { /* {{{ */
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setShardId($userid);
        $this->setTableName("user_medal");
    }
 /* }}} */
    public function addUserMedal($kind, $medal)
    { /* {{{ */
        $user_medal_info = array(
            'uid' => $this->getShardId(),
            'kind' => $kind,
            'medal' => $medal,
            'addtime' => date("Y-m-d H:i:s")
        );
        
        return $this->replace($this->getTableName(), $user_medal_info);
    }
 /* }}} */
    public function getUserMedal($kind)
    { /* {{{ */
        $sql = "select medal from {$this->getTableName()} where uid = ? and kind = ?";
        return $this->getOne($sql, array(
            $this->getShardId(),
            $kind
        ));
    }
 /* }}} */
    public function getUserMedals()
    { /* {{{ */
        $sql = "select kind, medal from {$this->getTableName()} where uid = ?";
        return $this->getAll($sql, $this->getShardId());
    } /* }}} */
    public function delete($uid, $kind)
    {/*{{{*/
        $sql = "delete from {$this->getTableName()} where uid=? and kind=?";
        return $this->execute($sql, array($uid, $kind));
    }/*}}}*/
}
