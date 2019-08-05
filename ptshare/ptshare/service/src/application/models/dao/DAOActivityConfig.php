<?php
class DAOActivityConfig extends DAOProxy
{

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_MALL");
        $this->setTableName("activity_config");
    }

    public function isOpen($id){
        $sql = " select count(0) as cnt from ".$this->getTableName()." where id=? and status='Y'";

        return $this->getOne($sql, $id) > 0;
    }

    public function setConfig($id, $status)
    {
        $info = array(
            "status" => $status,
            "modtime" => date('Y-m-d H:i:s')
        );

        return $this->update($this->getTableName(), $info, "id=?", array($id));
    }

    public function getEnabledList()
    {
        $sql = "select id from " . $this->getTableName() . " where status = 'Y'";
        
        return $this->getAll($sql, null);
    }
}
?>
