<?php

class DAOAllLive extends DAOProxy
{

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setTableName("live");
    }
    
    /**
     * 直播时长超过1小时的主播
     *
     * @param  int $offset
     * @param  int $num
     * @return array
     */
    public function getList($offset, $num)
    {
        $sql = "select distinct(uid) as fid from ".$this->getTableName()."   (UNIX_TIMESTAMP(endtime) - UNIX_TIMESTAMP(addtime) ) >= 3600 order by liveid asc limit  $offset,$num";
        return $this->getAll($sql);
    }
    
    /**
     * 直播时长超过1小时的总数
     *
     * @return unknown
     */
    public function getCount()
    {
        $sql = "select count(distinct(uid)) as cnt from ".$this->getTableName()." where  (UNIX_TIMESTAMP(endtime) - UNIX_TIMESTAMP(addtime) ) >= 3600 limit 1";
        return  $this->getOne($sql);
        
    }

    public function getAllOnLive()
    {
        return $this->getAll("select uid from ".$this->getTableName()." where status=?", array('status'=>1));
    }
}