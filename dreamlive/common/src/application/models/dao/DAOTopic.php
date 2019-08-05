<?php

class DAOTopic extends DAOProxy
{

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setTableName("topic_relation");
    }

    /**
     * 话题是否存在
     * 
     * @param  string $name            
     * @param  int    $type            
     * @return boolean
     */
    public function existTopic($name,$region,$relateid,$type)
    {
        $sql = " SELECT count(id) as cnt FROM " . $this->getTableName() . " WHERE name=? and relateid=? and type=? and region=? ";
        return $this->getOne($sql, array('name' => $name,'relateid'=>$relateid,'type' => $type,'region'=>$region)) ? true : false;
    }
    
    /**
     * 查询relateid列表
     *
     * @param string $name
     * @param string $region
     */
    public function getRelateidList($name,$region)
    {
        $sql = " SELECT relateid FROM " . $this->getTableName() . " WHERE name=? and region=? " ;
        return $this->getAll($sql, array('name'=>$name,'region'=>$region));
    }

    /**
     * 添加话题
     *
     * @param  string $name            
     * @param  int    $relateid            
     * @param  int    $type            
     * @return boolean
     */
    public function addTopic($name,$region, $relateid, $type)
    {
        $info = array(
            "name"     => $name,
            "region"   => $region,
            "relateid" => $relateid,
            "type"     => $type,
            "addtime"  => date("Y-m-d H:i:s")
        );
        $this->replace($this->getTableName(), $info);
    }

    /**
     * 删除话题
     *
     * @param  string $name            
     * @param  int    $relateid            
     * @param  int    $type            
     * @return boolean
     */
    public function delTopic($name,$region, $relateid, $type)
    {
        $sql = "delete from {$this->getTableName()} where name=? and relateid=? and type=? and region=? ";
        return $this->Execute($sql, array($name,$relateid,$type,$region)) ? true : false;
    }

    /**
     * 清空话题
     *
     * @param  string $name
     * @return boolean
     */
    public function cleanTopic($name,$region)
    {
        $sql = "delete from {$this->getTableName()} where name=? and region=? ";
        return $this->Execute($sql, array(trim($name),$region)) ? true : false;
    }
    
    /**
     * 查询某一资源的所有话题
     *
     * @param  int $relateid
     * @param  int $type
     * @return array
     */
    public function getTopicName($relateid,$region, $type)
    {
        $sql  = "select name from " . $this->getTableName() . " where relateid=? and type=? and region=? ";
        return $this->getAll($sql, array('relateid'=>$relateid,'type'=>$type,'region'=>$region));
    }
}    