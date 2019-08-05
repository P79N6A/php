<?php
class DAOBagUsed extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("promotion");
    }

    
}
?>
