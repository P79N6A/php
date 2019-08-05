<?php
class DAOCom extends DAOProxy
{
    public function __construct($table, $db = "MYSQL_CONF_PAYMENT")
    {
        $this->setDBConf($db);
        $this->setTableName($table);
    }
}
?>