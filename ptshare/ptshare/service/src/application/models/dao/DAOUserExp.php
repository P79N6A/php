<?php
class DAOUserExp extends DAOProxy{
    /**
     * 构造方法
     * @param int $userid
     */
    public function __construct($userid)
    { 
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setShardId($userid);
        $this->setTableName("user_exp");
    }

    /**
     * 增加用户经验
     * @param int $user_exp_info
     * @return unknown
     */
    public function addUserExp($uid, $exp)
    {
        $option = array(
            'uid'     => $uid,
            'exp'     => $exp,
            'addtime' => date("Y-m-d H:i:s")
        );
        return $this->insert($this->getTableName(), $option);
    }

    /**
     * 获取用户经验
     * @return int
     */
    public function getUserExp()
    { 
        $sql = "select exp from {$this->getTableName()} where uid = ?";
        return $this->getRow($sql, $this->getShardId());
    }

    /**
     * 增加用户经验
     * @param int $exp
     * @return unknown
     */
    public function incUserExp($exp)
    { 
        $sql = "update {$this->getTableName()} set exp = exp + ? where uid =?";
        return $this->execute($sql, array( $exp,$this->getShardId()));
    }
}
