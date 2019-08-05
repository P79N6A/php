<?php
class Tags extends Table
{

    public function __construct()
    {
        parent::__construct();
        $this->setDBConf("MYSQL_CONF_MALL");
        $this->setTableName("tags");
        $this->setPrimary("id");
    }

    public function getList()
    {

        return $this->getRecords();

    }

}