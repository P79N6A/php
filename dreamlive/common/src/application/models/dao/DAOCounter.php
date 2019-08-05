<?php
class DAOCounter extends DAOProxy
{
    
    const TABLE_NAME_BASE = 'counter';
    
    const TABLE_SHARDINGS = 100;
    
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_COUNTER");
    }
    
    public function setCounter($product, $type, $relateid, $value, $microtime)
    {
        $tablename = $this->getTableName($product, $type, $relateid);
        $modtime = $addtime = date('Y-m-d H:i:s');
        
        $sql = "insert into {$tablename} (product, type, relateid, value, addtime, modtime, version) values(?,?,?,?,?,?,?) on duplicate key update modtime = IF(VALUES(version)> version, '{$modtime}', modtime), value = IF(VALUES(version)> version, VALUES(value), value),version = IF(VALUES(version)> version, VALUES(version), version)";
        $values = array($product, $type, $relateid, $value, $addtime, $modtime, $microtime);
        
        //Logger::log("counter_syc_db", "insert", func_get_args() + array("sql"=> $sql));
        
        $this->setAutoReconnect(true);
        return $this->execute($sql, $values);
    }
    
    protected function getTableName($product, $type, $relateid)
    {
        $md5 = md5($product . '_' . $type . '_' . $relateid);
        return self::TABLE_NAME_BASE . '_' . abs(crc32($md5)) % self::TABLE_SHARDINGS;
    }
}
