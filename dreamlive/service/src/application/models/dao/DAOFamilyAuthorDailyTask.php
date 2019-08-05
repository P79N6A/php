<?php

class DAOFamilyAuthorDailyTask extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_FAMILY");
        $this->setTableName("author_daily_task");
    }

    public function add($authorid, $begin_time, $end_time)
    {
        $info = [
            'authorid' => $authorid,
            'begin_time' => $begin_time,
            'end_time' => $end_time,
            'status' => 0,
            'addtime' => date('Y-m-d H:i:s'),
        ];

        return $this->insert($this->getTableName(), $info);
    }

}