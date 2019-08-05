<?php

class DAOWallnotice extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_CHANNEL");
        $this->setTableName('wall_notice');
    }

    public function getInfoByDeviceid($deviceid, $effective_time)
    {
        $sql = "SELECT * FROM {$this->getTableName()} WHERE deviceid=? AND addtime>=? AND callback_status=1 ORDER BY id DESC LIMIT 1";

        return $this->getRow($sql, [$deviceid, $effective_time]);
    }

    public function updateCallbackStatus($id, $status)
    {
        $sql = "UPDATE {$this->getTableName()} SET callback_status=? WHERE id=?";

        return $this->execute($sql, [$status, $id]);
    }
}