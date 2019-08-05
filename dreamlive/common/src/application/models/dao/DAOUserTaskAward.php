<?php

class DAOUserTaskAward extends DAOProxy
{

    public function __construct($userid)
    {
        $this->setDBConf("MYSQL_CONF_TASK");
        $this->setShardId($userid);
        $this->setTableName("user_task_award");
    }

    /**
     * 添加数据
     * 
     * @param arrray $userTaskDetails            
     */
    public function addData($data)
    {
        return $this->insert($this->getTableName(), $data);
    }

    /**
     * 修改数据
     * 
     * @param int   $uid            
     * @param array $option            
     */
    public function updateData($id, $award)
    {
        return $this->update($this->getTableName(), $award, "id=?", $id);
    }
    
    /**
     * 获取最新一条数据
     *
     * @param int $uid
     * @param int $taskid
     */
    public function getUserTaskAwardNew($uid)
    {
        $sql = " select * from ".$this->getTableName()." where uid=? order by id desc limit 1";
        return $this->getRow($sql, array('uid'=>$this->getShardId()));
    }
}
