<?php

class DAOTask extends DAOProxy
{

    public function __construct()
    {
        /* {{{ */
        $this->setDBConf("MYSQL_CONF_TASK");
        $this->setTableName("task");
    }
    /* }}} */
    public function getTaskInfo($taskid)
    {
        /* {{{ */
        static $buffer;
        
        //if (! isset($buffer[$taskid])) {
            $sql = "select taskid, type, name, totallimit, daylimit, begintime, endtime, active, extend, addtime, modtime, totalamount, dayamount from " . $this->getTableName() . " where taskid=?";
            $task_info = $this->getRow($sql, array($taskid));
            $buffer[$taskid] = $task_info;
        //}
        
        return $buffer[$taskid];
    } /* }}} */
    
    /**
     * 获取任务列表
     */
    public function getTaskList($type)
    {
        $date = date("Y-m-d H:i:s");
        $sql  = "select * from " . $this->getTableName() . " where  ((begintime<'".$date."' and endtime>'".$date."') or (begintime='0000-00-00 00:00:00' && endtime='0000-00-00 00:00:00' )) and active='Y' and status='N' and type=?  order by score asc ";
        return $this->getAll($sql, array('type'=>$type));
    }
    
    /**
     * 获取任务列表 根据类型
     */
    public function getTaskListByTypes(array $type)
    {
        
        $types = implode(',', $type);
        
        $date = date("Y-m-d H:i:s");
        $sql  = "select * from " . $this->getTableName() . " where  ((begintime<'".$date."' and endtime>'".$date."') or (begintime='0000-00-00 00:00:00' && endtime='0000-00-00 00:00:00' )) and active='Y' and type in ($types) order by score asc ";
        
        Logger::log("task_err", "taskList_sql", [$sql]);
        return $this->getAll($sql);
    }

    public function updateTask($taskid, $name, $active, $totallimit, $daylimit, $extend, $type, $status)
    {
        $taskinfo = array(
            "name"       => $name,
            "active"     => $active == 'Y'?'Y': 'N',
            "totallimit" => $totallimit,
            "daylimit"   => $daylimit,
            "extend"     => $extend,
            "status"     => $status
        );
        if($type) {
            $taskinfo['type'] = $type;
        }
        if($taskid) {
            $taskinfo['modtime'] = date("Y-m-d H:i:s");
            return $this->update($this->getTableName(), $taskinfo, "taskid=?", $taskid);
        }else{
            $taskinfo['addtime'] = date("Y-m-d H:i:s");
            return $this->insert($this->getTableName(), $taskinfo);
        }
    }
}
?>
