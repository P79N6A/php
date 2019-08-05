<?php

class DAOUserTaskDetails extends DAOProxy
{
 
    public function __construct($userid)
    {
        $this->setDBConf("MYSQL_CONF_TASK");
        $this->setShardId($userid);
        $this->setTableName("user_task_details");
    }
    
    /**
     * 用户今日是否已签到
     */
    public function getUserTaskDetailsSign($startime,$endTime)
    {
        $sql = "select * from  " . $this->getTableName() . " where uid=? and taskid=? and addtime >'".$startime."' and addtime<'".$endTime."' order by addtime desc limit 1  ";
        return $this->getRow($sql, array($this->getShardId(),VerifyTask::TASK_LADDER_SIGN_ID));
    }
    
    /**
     * 添加数据
     *
     * @param arrray $userTaskDetails
     */
    public function addData($userTaskDetails)
    {
        return $this->insert($this->getTableName(), $userTaskDetails);
    }
    
    /**
     * 
     * @param int $type
     */
    public function getListByType($type)
    {
        $sql = "select DISTINCT taskid from ".$this->getTableName()." where uid=? and type=? order by addtime desc ";
        return $this->getAll($sql, array('uid'=>$this->getShardId(),'type'=>$type));
    }
    
    /**
     * 获取任务列表
     *
     * @param int $taskid
     */
    public function getListByLevel($taskid)
    {
        $sql = "select * from ".$this->getTableName()." where uid=? and taskid=? order by level desc ";
        return $this->getAll($sql, array('uid'=>$this->getShardId(),'taskid'=>$taskid));
    }
    
    /**
     * 获取任务
     *
     * @param unknown $taskid
     * @param unknown $level
     */
    public function getInfoByLevel($taskid,$level)
    {
        $sql = "select * from ".$this->getTableName()." where uid=? and taskid=? and level=?  order by level desc limit 1 ";
        return $this->getRow($sql, array('uid'=>$this->getShardId(),'taskid'=>$taskid,'level'=>$level));
    }
}
