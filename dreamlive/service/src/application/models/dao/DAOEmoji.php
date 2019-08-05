<?php
class DAOEmoji extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_MESSAGE");
        $this->setTableName("emoji");
    }
    
    public function getContentById($id)
    {
        $sql = "select content from {$this->getTableName()} where id=? limit 1";
        
        return $this->getOne($sql, array($id));
    }
}

?>