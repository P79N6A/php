<?php

class DAOUserTask extends DAOProxy
{

    public function __construct($userid)
    {
        /* {{{ */
        $this->setDBConf("MYSQL_CONF_TASK");
        $this->setShardId($userid);
        $this->setTableName("user_task");
    }
    /* }}} */
    public function addUserTask($user_task_info)
    {
        /* {{{ */
        $user_task_info["addtime"] = date("Y-m-d H:i:s");
        $user_task_info["modtime"] = date("Y-m-d H:i:s");
        return $this->insert($this->getTableName(), $user_task_info);
    }
    /* }}} */
    public function modUserTask($taskid, $user_task_info)
    {
        /* {{{ */
        $user_task_info["modtime"] = date("Y-m-d H:i:s");
        return $this->update($this->getTableName(), $user_task_info, "uid=? and taskid=?", array($this->getShardId(),$taskid));
    }
    
    /* }}} */
    public function getUserTask($taskid, $locked = false)
    {
        /* {{{ */
        $sql = "select uid, taskid,awardext,backupext, totaltimes, daytimes, modtime from " . $this->getTableName() . " where uid=? and taskid=?";
        
        if ($locked) {
            $sql .= " for update";
        }
        
        return $this->getRow($sql, array($this->getShardId(),$taskid));
    }
    /* }}} */
    public function getUserTaskCount($taskid, $locked = false)
    {
        /* {{{ */
        $sql = "select count(*) from " . $this->getTableName() . " where uid=? and taskid=?";
        
        
        return $this->getOne($sql, array($this->getShardId(),$taskid));
    }
    /**
     * 用户今日是否已签到
     */
    public function isSign()
    {
        $date = date("Y-m-d");
        $sql = "select daytimes from  " . $this->getTableName() . " where uid=? and type=? ";
        $result = $this->getRow($sql, array($this->getShardId(),Task::TASK_TYPE_SIGN));
        if (isset($result['daytimes']) && $result['daytimes'] > 0) {
            return true;
        }
        return false;
    }
}
