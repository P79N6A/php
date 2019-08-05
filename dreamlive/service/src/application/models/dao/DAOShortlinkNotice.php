<?php

class DAOShortlinkNotice extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_CHANNEL");
        $this->setTableName('shortlink_notice');
    }

    public function getNotice($ip, $effective_time)
    {
        $sql = "SELECT * FROM {$this->getTableName()} WHERE link_ip=? AND link_time>=? AND deviceid='' ORDER BY id DESC LIMIT 1";

        return $this->getRow($sql, [$ip, $effective_time]);
    }

    public function updatedeviceId($channelid, $activetime, $deviceid, $channel)
    {
        $sql = "UPDATE {$this->getTableName()} SET deviceid=?, activetime=?, channel=? WHERE id=?";

        return $this->execute($sql, [$deviceid, $activetime, $channel, $channelid]);
    }
}