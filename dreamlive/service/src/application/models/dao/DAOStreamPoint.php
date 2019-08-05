<?php
class DAOStreamPoint extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setTableName("stream_point");
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
     * 获取测速的详情
     *
     * @param  int $uid
     * @param  int $relateid
     * @return array
     */
    public function getInfoByUid($uid)
    {
        $sql = "SELECT * FROM ".$this->table." where uid=?  order by addtime desc limit 1 ";
        return $this->getRow($sql, array('uid'=>$uid));
    }
}
