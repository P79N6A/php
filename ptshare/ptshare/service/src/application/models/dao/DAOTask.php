<?php
class DAOTask extends DAOProxy
{

    /**
     * 构造方法
     */
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setTableName("task");
    }

    /**
     * 获取任务详情
     *
     * @param int $taskid
     * @return arrray
     */
    public function getTaskInfo($taskid)
    {
        $sql = "select taskid, type, name, title, totallimit, daylimit, begintime, endtime, active, extend, addtime, modtime, totalamount, dayamount,`schema`, `describe` from " . $this->getTableName() . " where taskid=?";
        return $this->getRow($sql, array($taskid));
    }

    /**
     * 获取任务列表
     */
    public function getTaskList()
    {
        $date = date("Y-m-d H:i:s");
        $sql = "select taskid, type, name, title, totallimit, daylimit, begintime, endtime, active,score, extend, addtime, modtime,`schema`,`describe` from " . $this->getTableName() . " where  ((begintime<'" . $date . "' and endtime>'" . $date . "') or (begintime='0000-00-00 00:00:00' && endtime='0000-00-00 00:00:00' )) and active='Y' and status='Y'  order by score asc ";
        return $this->getAll($sql);
    }
}
?>
