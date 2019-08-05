<?php

class DAOChannelPlatform extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_CHANNEL");
        $this->setTableName('channel_platform');
    }

    public function getInfoByChannel($channel)
    {
        $sql = "SELECT * FROM {$this->getTableName()} WHERE channel=?";

        return $this->getRow($sql, $channel);
    }
}