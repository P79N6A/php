<?php

class DAOShortlinkLive extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_CHANNEL");
        $this->setTableName('shortlink_live');
    }

    public function getInfoByIp($ip)
    {
        $sql = "SELECT * FROM {$this->getTableName()} WHERE ip=? ORDER BY id DESC LIMIT 1";

        return $this->getRow($sql, $ip);
    }

    public function deleteByIp($ip)
    {
        $sql = "DELETE FROM {$this->getTableName()} WHERE ip=?";

        return $this->Execute($sql, $ip);
    }
}