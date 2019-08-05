<?php

class DAOUserVipMonthlyLog extends DAOProxy
{

    public function __construct($userid = '')
    {
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setTableName("user_vip_monthly_log");
    }

    public function addLog($uid, $lastlog, $newlog, $type)
    {
        $info['uid']     = $uid;
        $info['lastlog'] = $lastlog;
        $info['newlog']  = $newlog;
        $info['addtime'] = date("Y-m-d H:i:s");
        $info['type']    = $type;

        return $this->insert($this->getTableName(), $info);
    }

}
