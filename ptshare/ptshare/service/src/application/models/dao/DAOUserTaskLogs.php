<?php
class DAOUserTaskLogs extends DAOProxy{
    /**
     * 构造方法
     * @param unknown $userid
     */
    public function __construct($userid){
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setShardId($userid);
        $this->setTableName("user_task_logs");
    }

    /**
     * 添加任务log
     * @param int $uid
     * @param int $taskid
     * @param array $award
     * @param int $orders
     * @return 
     */
    public function addUserTaskLogs($uid, $taskid, $award){
        $option = array(
            'uid'     => $uid,
            'taskid'  => $taskid,
            'award'   => json_encode($award),
            'addtime' => date("Y-m-d H:i:s")
        );
        return $this->insert($this->getTableName(), $option);
    }

    /**
     * 修改orderid
     * @param int $logid
     * @param int $orderid
     * @return 
     */
    public function updateUserTaskLogs($logid, $orderid){
        $condition = ' id=? ';
        $params = array(
            'id' => $logid
        );
        $option = array(
            'orderid' => $orderid,
            'modtime' => date("Y-m-d H:i:s")
        );
        return $this->update($this->getTableName(), $option, $condition, $params);
    }
    
    /**
     * 任务列表
     * @param int $uid
     * @param int $num
     * @param int $offset
     */
    public function getUserTaskLogsList($uid, $num, $offset){
        $where = "";
        if ($offset > 0) {
            $where .= " and id<" . $offset . " ";
        }
        $sql = " SELECT * FROM " . $this->getTableName() . " WHERE uid=? ";
        $sql .= $where;
        $sql .= " ORDER BY id DESC LIMIT " . $num;
        return $this->getAll($sql, array('uid' => $uid));
    }
    
    /**
     * 任务总数
     * @param int $uid
     * @param date $startime
     * @param date $endtime
     */
    public function getUserTaskLogsTotal($uid){
        $sql = " SELECT count(*) as cnt FROM " . $this->getTableName() . " WHERE uid=?  ";
        return $this->getOne($sql, array('uid' => $uid));
    }
    
    /**
     * 是否有下一页数据
     * @param int $uid
     * @param string $type
     * @param int $offset
     */
    public function getMoreUserTaskLogs($uid, $offset){
        $where = "";
        if ($offset > 0) {
            $where .= " and id<" . $offset . " ";
        }
        $sql = " SELECT * FROM " . $this->getTableName() . " WHERE uid=? ";
        $sql .= $where;
        return $this->getOne($sql, array('uid' => $uid));
    }
}