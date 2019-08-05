<?php

class DAOUserExp extends DAOProxy
{

    public function __construct($userid)
    {
        /* {{{ */
        $this->setDBConf("MYSQL_CONF_TASK");
        $this->setShardId($userid);
        $this->setTableName("user_exp");
    }
    /* }}} */
    public function addUserExp($user_exp_info)
    {
        /* {{{ */
        $user_exp_info['addtime'] = date("Y-m-d H:i:s");
        return $this->insert($this->getTableName(), $user_exp_info);
    }
    /* }}} */
    public function getUserExp()
    {
        /* {{{ */
        $sql = "select exp from {$this->getTableName()} where uid = ?";
        
        return $this->getRow($sql, $this->getShardId());
    }
    /* }}} */
    public function incUserExp($exp)
    {
        /* {{{ */
        $sql = "update {$this->getTableName()} set exp = exp + ? where uid =?";
        return $this->execute(
            $sql, array(
            $exp,
            $this->getShardId()
            )
        );
    } /* }}} */
    public function getCountUserBetweenExp($startexp,$endexp)
    {
        /* {{{ */
        $sql = "select count(*) as count from {$this->getTableName()} where exp BETWEEN ? and ?";

        return $this->getRow($sql, array($startexp,$endexp));
    } /* }}} */
}
