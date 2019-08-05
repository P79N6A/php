<?php

class DAOReplayurlDetails extends DAOProxy
{

    /**
     * 构造方法
     */
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setTableName("replayurl_details");
    }

    /**
     * 添加数据
     *
     * @param array $option
     */
    public function addData($option)
    {
        return $this->replace($this->getTableName(), $option);
    }
    
    /**
     * 
     * @param unknown $liveid
     */
    public function getReplayurlListByLiveid($liveid)
    {
        $sql = "select replayurl from ".$this->getTableName()." where liveid=? order by creatime asc";
        return $this->getAll($sql, $liveid);
    }
}
