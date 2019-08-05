<?php

class DAOTasklog extends DAOProxy
{

    public function __construct()
    {
        /* {{{ */
        $this->setDBConf("MYSQL_CONF_TASK");
        $this->setTableName("tasklog");
    }
    /* }}} */
    public function addTasklog($uid, $taskid, $income, $amount, $ext)
    {
        /* {{{ */
        $arr_info["uid"] = $uid;
        $arr_info["taskid"] = $taskid;
        $arr_info["income"] = $income;
        $arr_info["amount"] = $amount;
        $arr_info["ext"] = trim($ext);
        $arr_info["addtime"] = date("Y-m-d H:i:s");
        
        return $this->insert($this->getTableName(), $arr_info);
    } /* }}} */
}
