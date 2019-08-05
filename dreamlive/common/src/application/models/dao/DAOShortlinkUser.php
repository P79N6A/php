<?php

class DAOShortlinkUser extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_CHANNEL");
        $this->setTableName('shortlink_user');
    }

    public function add($uid, $channel, $ip, $active_time, $platform, $brand, $model, $deviceid)
    {
        $info = [
            'uid' => $uid,
            'channel' => $channel,
            'ip' => $ip,
            'active_time' => $active_time,
            'platform' => $platform,
            'brand' => $brand,
            'model' => $model,
            'deviceid' => $deviceid,
        ];

        return $this->insert($this->getTableName(), $info);
    }

    public function deleteByUid($uid)
    {
        $sql = "DELETE FROM {$this->getTableName()} WHERE uid=?";

        return $this->Execute($sql, $uid);
    }
}