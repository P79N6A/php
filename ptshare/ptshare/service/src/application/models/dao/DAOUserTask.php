<?php
class DAOUserTask extends DAOProxy{

    /**
     * 构造方法
     * @param int $userid            
     */
    public function __construct($userid){
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setShardId($userid);
        $this->setTableName("user_task");
    }

    /**
     * 添加用户任务
     * @param array $user_task_info            
     * @return unknown
     */
    public function addUserTask($uid, $taskid, $num)
    {
        $option = array(
            'uid'        => $uid,
            'taskid'     => $taskid,
            'totaltimes' => $num,
            'daytimes'   => $num,
            'addtime'    => date("Y-m-d H:i:s"),
            'modtime'    => date("Y-m-d H:i:s")
        );
        return $this->insert($this->getTableName(), $option);
    }
    
    public function modUserTask($taskid, $user_task_info)
    { /* {{{ */
        $user_task_info["modtime"] = date("Y-m-d H:i:s");
        return $this->update($this->getTableName(), $user_task_info, "uid=? and taskid=?", array($this->getShardId(),$taskid));
    }

    /**
     * 获取用户任务
     * @param int $taskid
     * @param boolean $locked
     * @return unknown
     */
    public function getUserTask($taskid, $locked = false){ 
        $sql = "select uid, taskid, totaltimes, daytimes, modtime from " . $this->getTableName() . " where uid=? and taskid=?";
        if ($locked) {
            $sql .= " for update";
        }
        return $this->getRow($sql, array($this->getShardId(),$taskid));
    }

    /**
     * 获取用户任务次数
     * @param int $taskid
     * @param boolean $locked
     * @return unknown
     */
    public function getUserTaskCount($taskid, $locked = false){ 
        $sql = "select count(*) from " . $this->getTableName() . " where uid=? and taskid=?";
        return $this->getOne($sql, array($this->getShardId(),$taskid));
    }
}
