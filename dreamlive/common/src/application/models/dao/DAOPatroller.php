<?php
class DAOPatroller extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_MESSAGE");
        $this->setTableName("patroller");
    }
    
    public function addPatroller($author, $relateid)
    {
        try {
            
            $info = array(
            "author"=>$author,
            "relateid"=>$relateid,
            "addtime"=>date("Y-m-d H:i:s")
            );
            
            return $this->replace($this->getTableName(), $info);
        } catch (MySQLException $e) {
            throw new BizException(ERROR_SYS_DB_SQL);
        }
        
    }
    
    public function delPatroller($author, $relateid)
    {
        return $this->delete($this->getTableName(), "author=? and relateid=?", array($author, $relateid));
    }
    
    public function getPatrollers($author)
    {
        $sql = "select relateid from {$this->getTableName()} where author=?";
        
        return $this->getAll($sql, $author);
    }
    
    public function isPatroller($author, $relateid)
    {
        $sql = "select count(*) as cnt from {$this->getTableName()} where author=? and relateid = ? limit 1";
    
        return $this->getOne($sql, array($author, $relateid)) > 0;
    }
    
    
    public function getTotal($author)
    {
        $sql = "select count(*) as cnt from {$this->getTableName()} where author=? limit 1";
        
        return $this->getOne($sql, array($author));
    }
}
?>
